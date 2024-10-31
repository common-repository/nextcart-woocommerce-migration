<?php
defined( 'ABSPATH' ) || exit;

class NCWM_Display {
    
    public static function displayAjax(){
        if(!isset($_POST['func'])){
            wp_die();
        }
        $action = sanitize_text_field($_POST['func']);
        $function = 'ajax' . ucfirst($action);
        if ($action && method_exists('NCWM_Display', $function)) {
            self::$function();
        }
        return;
    }
    
    public static function ajaxChangecart() {
        if (!isset($_POST['cart_type']) || !$_POST['cart_type']) {
            return;
        }
        $type = 'source';
        $cart_type = sanitize_text_field($_POST['cart_type']);
        if ($cart_type) {
            $defineModel = new NCWM_Define();
            $html = ncwm_get_template(
                            'forms/' . $defineModel->getCartGroup($cart_type) . '.php',
                            array(
                                'type'      => $type,
                                'fields'    => $defineModel->getFormFields($cart_type),
                                'cart_type' => $cart_type,
                                'guide'     => $defineModel->getCartInstruction($cart_type)
                            )
            );
            $response = array(
                'html' => $html
            );
        } else {
            $response = array(
                'html' => false
            );
        }
        self::responseJson($response);
    }
    
    public static function ajaxMigration() {
        @set_time_limit(0);
        $app_url = NCWM_Main::APP_URL . '?store=' . get_site_url() . '&token=' . get_option('nextcart_token', '__token__');
        $ch = curl_init($app_url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:86.0) Gecko/20100101 Firefox/86.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 3000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            $response_error = array(
                'result' => 'error',
                'error' => 'A problem occurred while sending request to migration server! Error: ' . $error_msg
            );
            self::responseJson($response_error);
            exit();
        }
        curl_close($ch);
        die($response);
        exit();
    }
    
    public static function ajaxSetup() {
        self::ajaxMigration();
    }
    
    public static function ajaxConfig() {
        self::ajaxMigration();
    }
    
    public static function ajaxClear() {
        self::ajaxMigration();
    }
    
    public static function ajaxMigrate() {
        self::ajaxMigration();
    }
    
    public static function ajaxReindex() {
        self::ajaxMigration();
    }
    
    public static function loadMigrationPage() {
        ncwm_display_template('index.php');
    }
    
    public static function loadHowItWorks() {
        ncwm_display_template('how-it-works.php');
    }
    
    public static function loadExtraServices() {
        ncwm_display_template('extra-services.php');
    }
    
    public static function loadOfficialMigration() {
        ncwm_display_template('pro-license.php');
    }
    
    public static function loadSettings() {
        $options['url_redirect'] = get_option('nextcart_url_redirect');
        ncwm_display_template('settings.php', $options);
    }
    
    public static function responseJson($response) {
        die(json_encode($response));
    }
    
    public static function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
    public static function loadSeoUrlPage() {
        if (!current_user_can('manage_options')) {
            wp_die(
                    '<h1>You need a higher level of permission.</h1>' .
                    '<p>Sorry, you are not allowed to view URL redirects.</p>', 403
            );
        }
        $messages = $errors = array();
        if (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
            if (!current_user_can('manage_options')) {
                wp_die(
                        '<h1>You need a higher level of permission.</h1>' .
                        '<p>Sorry, you are not allowed to delete URL redirects.</p>', 403
                );
            }
            $result = self::process_delete_redirect();
            if (is_wp_error($result)) {
                foreach ($result->get_error_messages() as $error) {
                    $errors[] = $error;
                }
            } elseif ($result !== false) {
                $messages[] = 'URL redirect updated.';
            }
        } else {
            if (isset($_GET['update']) && $_GET['update'] == 'del') {
                $delete_count = isset($_GET['deleted_count']) ? (int) $_GET['deleted_count'] : 0;
                $failed_count = isset($_GET['failed_count']) ? (int) $_GET['failed_count'] : 0;
                $fail = $success = '';
                if ($delete_count == 1) {
                    $success = 'URL redirect deleted.';
                } elseif ($delete_count > 1) {
                    $success = sprintf('%s URL redirects deleted.', $delete_count);
                } else {
                    $success = '0 URL redirect deleted.';
                }
                if ($failed_count > 0) {
                    $fail = sprintf(' %s URL redirects could not be deleted.', $failed_count);
                }
                $messages[] = $success . $fail;
            }
        }
        ncwm_display_template('url-redirects.php', array('messages' => $messages, 'errors' => $errors));
    }
    
    private static function process_delete_redirect() {
        if (empty($_REQUEST['urlredirects']) && empty($_REQUEST['urlredirect'])) {
            $redirect = sprintf('?page=%s', $_REQUEST['page']);
            wp_redirect($redirect);
            exit();
        }
        if (empty($_REQUEST['urlredirects'])) {
            $urlredirect_ids = array(intval($_REQUEST['urlredirect']));
        } else {
            $urlredirect_ids = array_map('intval', (array) $_REQUEST['urlredirects']);
        }
        
        $update = 'del';
	$deleted_count = $failed_count = 0;
        
        foreach ($urlredirect_ids as $id) {
            $result = self::delete_redirect($id);
            if ($result === true) {
                ++$deleted_count;
            } else {
                ++$failed_count;
            }
        }
        
        $redirect = sprintf('?page=%s&update=%s&deleted_count=%s&failed_count=%s', $_REQUEST['page'], $update, $deleted_count, $failed_count);
        wp_redirect($redirect);
        exit();
    }
    
    private static function delete_redirect($id) {
        global $wpdb;
        $result = $wpdb->delete($wpdb->prefix . 'nextcart_seo_url', array('redirect_id' => $id));
        if (false === $result) {
            return new WP_Error('cannot_delete_redirect', __('<strong>ERROR</strong>: Could not delete URL Redirect ID: ' . $id));
        }
        return true;
    }

}
