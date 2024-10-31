<?php
defined( 'ABSPATH' ) || exit;

class NCWM_Main {
    
    const APP_URL = 'https://demo.next-cart.com/app/woocommerce/';
    const SITE_URL = 'https://next-cart.com/';
    
    protected static $_instance = null;


    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }
    
    private function includes() {
        include_once NCWM_PLUGIN_DIR . '/includes/core-functions.php';
        include_once NCWM_PLUGIN_DIR . '/includes/display.php';
        include_once NCWM_PLUGIN_DIR . '/includes/carts/define.php';
        include_once NCWM_PLUGIN_DIR . '/includes/carts/kitconnect-api.php';
    }
    
    private function init_hooks() {
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_menu', array($this, 'admin_menu'));
        register_activation_hook(NCWM_PLUGIN_FILE, array($this, 'install'));
        add_action('wp_ajax_ncwm_migration', array('NCWM_Display', 'displayAjax'));
        add_action('admin_enqueue_scripts', array($this, 'admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('rest_api_init', function () {
            register_rest_route('next_cart/v1', '/migration', array(
                'methods'    => WP_REST_Server::CREATABLE,
                'callback'  => array('NCWM_Kitconnect', 'run'),
                'permission_callback'   => '',
                'args'      => array(
                    'token'         => array(),
                    'action'        => array(),
                    'cart'          => array(),
                    'serialize'     => array(),
                    'query'         => array(),
                    'files'         => array(),
                    'clearcaches'   => array(),
                )
            ));
        });
        if (get_option('nextcart_url_redirect') == 1) {
            add_filter('template_redirect', function () {
                global $wp_query, $wpdb;
                if ($wp_query->is_404) {
                    global $wpdb;
                    $url = parse_url(get_bloginfo('url'));
                    $url = isset($url['path']) ? $url['path'] : '';
                    $request = trim(substr($_SERVER['REQUEST_URI'], strlen($url)), '/');
                    $request_path = parse_url($request, PHP_URL_PATH);
                    if (!$request_path) {
                        return;
                    }
                    $paths = explode('?', $request_path);
                    $sql = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}nextcart_seo_url` WHERE `request_path` IN ('" . $paths[0] . "', '" . urldecode($paths[0]) . "', '" . $request . "', '" . urldecode($request) . "')", ARRAY_A);
                    if (!$sql) {
                        $sql = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}nextcart_seo_url` WHERE `request_path` LIKE '%" . trim($paths[0], '/') . "%'", ARRAY_A);
                    }
                    if (!$sql) {
                        $sql = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}nextcart_seo_url` WHERE `request_path` LIKE '%" . $request . "%'", ARRAY_A);
                    }
                    $wpUrl = false;
                    if ($sql) {
                        $sql = reset($sql);
                        if ($sql && $sql['target_type'] == 'category') {
                            $wpUrl = get_term_link((int) $sql['target_id'], 'product_cat');
                        } elseif ($sql && $sql['target_type'] == 'post_category') {
                            $wpUrl = get_category_link((int) $sql['target_id']);
                        } elseif ($sql) {
                            $wpUrl = get_permalink((int) $sql['target_id']);
                        }
                    }
                    if ($wpUrl && !is_wp_error($wpUrl)) {
                        header('HTTP/1.1 301 Moved Permanently');
                        header('Location: ' . $wpUrl);
                        exit;
                    } else {
                        return;
                    }
                } else {
                    return;
                }
            });
        }
    }
    
    public function install() {
        // Create a license on migration system
        $register_url = self::APP_URL . 'install.php';
        $post_data = array(
            'store' => get_site_url()
        );
        $args = array(
            'body' => $post_data,
            'timeout' => '30',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
            'sslverify' => false
        );
        $response = wp_remote_post($register_url, $args);
        $this->create_tables();
        $this->create_options();
    }
    
    public function register_settings() {
        register_setting('nextcart_setting', 'nextcart_url_redirect');
        $current_token = get_option('nextcart_token');
        if (!$current_token) {
            $new_token = $this->generate_token();
            add_option('nextcart_token', $new_token);
        }
    }

    /**
     * Add menu items.
     */
    public function admin_menu() {
        add_menu_page('Next-Cart Migration', 'Next-Cart Migration', 'manage_options', 'nextcart-migration', array('NCWM_Display', 'loadMigrationPage'), NCWM_PLUGIN_URL . '/assets/images/logo16.png', '56.5');
        
        add_submenu_page('nextcart-migration', 'URL Redirects', 'URL Redirects', 'manage_options', 'nextcart-seo-url', array('NCWM_Display', 'loadSeoUrlPage'));

        add_submenu_page('nextcart-migration', 'How It Works', 'How It Works', 'manage_options', 'nextcart-how-it-works', array('NCWM_Display', 'loadHowItWorks'));
        
        add_submenu_page('nextcart-migration', 'Extra Services', 'Extra Services', 'manage_options', 'nextcart-extra-services', array('NCWM_Display', 'loadExtraServices'));
        
        add_submenu_page('nextcart-migration', 'Pro License', 'Pro License', 'manage_options', 'nextcart-unlimited-migration', array('NCWM_Display', 'loadOfficialMigration'));
        
        add_submenu_page('nextcart-migration', 'Settings', 'Settings', 'manage_options', 'nextcart-settings', array('NCWM_Display', 'loadSettings'));
    }
    
    /**
     * Enqueue styles.
     */
    public function admin_styles() {
        if ( 'toplevel_page_nextcart-migration' === get_current_screen()->id ) {
            wp_enqueue_style('ncwm-bootstrap-css', NCWM_PLUGIN_URL . '/assets/css/bootstrap.min.css', array(), NCWM_PLUGIN_VERSION);
            wp_enqueue_style('ncwm-bootstrap-select-css', NCWM_PLUGIN_URL . '/assets/css/bootstrap-select.min.css', array(), NCWM_PLUGIN_VERSION);
            wp_enqueue_style('ncwm-style-css', NCWM_PLUGIN_URL . '/assets/css/style.css', array(), NCWM_PLUGIN_VERSION);
            wp_enqueue_style('ncwm-font-css', 'https://fonts.googleapis.com/css?family=Roboto:400,500,600,700', array(), NCWM_PLUGIN_VERSION);
        }
        if (in_array(get_current_screen()->id, array('next-cart-migration_page_nextcart-how-it-works', 'next-cart-migration_page_nextcart-extra-services', 'next-cart-migration_page_nextcart-unlimited-migration'))) {
            wp_enqueue_style('ncwm-submenus-style-css', NCWM_PLUGIN_URL . '/assets/css/sub-menus.css', array(), NCWM_PLUGIN_VERSION);
        }
    }
    
    /**
     * Enqueue scripts.
     */
    public function admin_scripts() {
        if ( 'toplevel_page_nextcart-migration' === get_current_screen()->id ) {
            wp_enqueue_script('ncwm-popper-js', NCWM_PLUGIN_URL . '/assets/js/popper.min.js', array('jquery'), NCWM_PLUGIN_VERSION);
            wp_enqueue_script('ncwm-bootstrap-js', NCWM_PLUGIN_URL . '/assets/js/bootstrap.min.js', array('jquery'), NCWM_PLUGIN_VERSION);
            wp_enqueue_script('ncwm-bootstrap-select-js', NCWM_PLUGIN_URL . '/assets/js/bootstrap-select.min.js', array('jquery'), NCWM_PLUGIN_VERSION);
            wp_register_script('ncwm-cart-js', NCWM_PLUGIN_URL . '/assets/js/cart.min.js', array('jquery'), NCWM_PLUGIN_VERSION);
            wp_localize_script('ncwm-cart-js', 'ncwm_ajax_request_params', array('ajax_wp_url' => admin_url('admin-ajax.php'), 'ajax_app_url' => self::APP_URL . '?store=' . get_site_url()));
            wp_enqueue_script('ncwm-cart-js');
        }
    }
    
    private function create_tables() {
        global $wpdb;
        $wpdb->hide_errors();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $query = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}nextcart_seo_url` (`redirect_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `request_path` VARCHAR(255), `target_id` INT(11), `target_type` VARCHAR(255)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        dbDelta($query);
    }
    
    private function create_options() {
        add_option('nextcart_url_redirect', '');
    }
    
    private function generate_token($length = 50) {
        $keylist = 'abcdefghijklmnopqrstuvwxyz1234567890';
        $keylistLength = strlen($keylist);
        $token = '';
        for ($i = 0; $i < $length; $i++) {
            $token .= $keylist[rand(0, $keylistLength - 1)];
        }
        return strtoupper($token);
    }

}
//add_filter('current_screen', 'my_current_screen' );
// 
//function my_current_screen($screen) {
//    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) return $screen;
//    print_r($screen);
//    return $screen;
//}
NCWM_Main::instance();
