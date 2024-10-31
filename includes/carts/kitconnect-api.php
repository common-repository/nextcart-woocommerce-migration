<?php
defined( 'ABSPATH' ) || exit;
ini_set('display_errors', false);
date_default_timezone_set('America/Los_Angeles');

class NCWM_Kitconnect {

    public $action;

    public static function run(WP_REST_Request $request) {
        $params = $request->get_params();
        $params = wp_parse_args(
                $params, array(
                    'token'         => '',
                    'action'        => '',
                    'cart'          => '',
                    'serialize'     => '',
                    'query'         => '',
                    'files'         => '',
                    'clearcaches'   => '',
                )
        );
        if (empty($params['token']) || md5($params['token']) != md5(get_option('nextcart_token', '__token__'))) {
            NCWM_Response::error('Invalid secret token');
            return;
        }

        $action = NCWM_Action::instance($params['action']);
        if (!$action) {
            NCWM_Response::error('Action not found');
            return;
        }
        $action->run($params);
        return;
    }

}

abstract class NCWM_Action {

    public static $instance = null;

    abstract public function run();

    public static function instance($action = '') {
        if (is_null(self::$instance)) {
            $class = self::getClass($action);
            if (!$class) {
                return null;
            } else {
                self::$instance = new $class();
            }
        }
        return self::$instance;
    }

    public static function getClass($action = '') {
        if ($action && is_string($action)) {
            $class = __CLASS__ . '_' . ucfirst($action);
            if (class_exists($class)) {
                return $class;
            } else {
                return null;
            }
        }
        return null;
    }

    public function getParams($key, $params, $default = null) {
        return isset($params[$key]) ? $params[$key] : $default;
    }

}

class NCWM_Action_Check extends NCWM_Action {

    public function run($params = array()) {
        $cart_type = $this->getParams('cart', $params);
        $cart = NCWM_Cart::instance($cart_type, true);
        if (!$cart) {
            NCWM_Response::error('Cart type is not specified or declared.');
            return;
        }
        $data['image_category'] = $cart->imageDirCategory;
        $data['image_product'] = $cart->imageDirProduct;
        $data['image_manufacturer'] = $cart->imageDirManufacturer;
        $data['table_prefix'] = $cart->tablePrefix;
        $data['version'] = $cart->version;
        $data['charset'] = $cart->charset;
        $data['cookie_key'] = $cart->cookie_key;
        $data['extend'] = $cart->extend;
        $data['connect'] = array(
            'result' => 'success',
            'msg' => 'Successfully connect to database!'
        );
        NCWM_Response::success('Cart type ' . $cart_type . ' is verified!', $data);
        return;
    }

}

class NCWM_Action_Query extends NCWM_Action {

    public function run($params = array()) {
        $cart_type = $this->getParams('cart', $params);
        $cart_query = $this->getParams('query', $params);
        $cart_serialize = $this->getParams('serialize', $params);
        $cart = NCWM_Cart::instance($cart_type);
        if (!$cart) {
            NCWM_Response::error('Cart type is not specified or declared.');
            return;
        }
        $dbConnect = new NCWM_Db();
        if ($cart_query && is_string($cart_query)) {
            $queries = @unserialize(base64_decode($cart_query));
            if ($cart_serialize && $queries !== false) {
                foreach ($queries as $key => $query) {
                    if (is_array($query) && isset($query['type'])) {
                        $params = isset($query['params']) ? $query['params'] : null;
                        $data[$key] = $dbConnect->processQuery($query['type'], $query['query'], $params);
                    } else {
                        $data[$key] = $dbConnect->processQuery('select', $query);
                    }
                }
            } elseif ($queries !== false) {
                $query = $queries;
                $params = isset($query['params']) ? $query['params'] : null;
                $data = $dbConnect->processQuery($query['type'], $query['query'], $params);
            } else {
                $query = base64_decode($cart_query);
                $data = $dbConnect->processQuery('select', $query);
            }
            if ($data === false || $data === null) {
                NCWM_Response::error('Cannot execute queries. Error: ' . $dbConnect->getError() . '. QUERY : ' . $query['query']);
                return;
            }
            NCWM_Response::success('', $data);
            return;
        } else {
            NCWM_Response::error('Queries is empty.');
            return;
        }
    }

}

class NCWM_Action_File extends NCWM_Action {

    public function run($params = array()) {
        $cart_files = $this->getParams('files', $params);
        $data = array();
        if ($cart_files && is_string($cart_files)) {
            $files = unserialize(base64_decode($cart_files));
            foreach ($files as $key => $file) {
                $params = isset($file['params']) ? $file['params'] : array();
                $data[$key] = $this->processFile($file['type'], $file['path'], $params);
            }
        }
        NCWM_Response::success('', $data);
        return;
    }

    public function processFile($type, $path, $params = array()) {
        $result = false;
        switch ($type) {
            case 'download':
                $result = $this->download($path, $params);
                break;
            case 'delete':
                $result = $this->delete($path, $params);
                break;
            case 'info':
                $result = $this->info($path);
                break;
            case 'size':
                $result = $this->size($path);
                break;
            default:
                break;
        }
        return $result;
    }

    public function download($path, $params = array(), $time = 5) {
        $result = false;
        if (!$time) {
            return $result;
        }
        $override = $this->getParams('override', $params);
        $rename = $this->getParams('rename', $params);
        $url = $this->getParams('url', $params);
        $list_images = $this->getParams('list_images', $params);
        if (!$url) {
            return $result;
        }
        if ($this->exists($path)) {
            if ($rename) {
                $path = $this->rename($path);
            } else {
                if (!$override) {
                    return $path;
                }
                $delete_file = $this->delete($path);
                if (!$delete_file) {
                    return $path;
                }
            }
        }
        $full_path = $this->getRealPath($path);
        $check_extension = pathinfo($full_path, PATHINFO_EXTENSION);
        $executable_files = array('sh', 'asp', 'cgi', 'php', 'ph', 'phtm', 'shtm', 'pl', 'py', 'jsp');
        foreach ($executable_files as $e_ext) {
            if (stripos($check_extension, $e_ext) !== false) {
                return $result;
            }
        }
        $this->createParentDir($full_path);
        $data = @file_put_contents($full_path, fopen($url, 'r'));
        if ($data) {
            $result = $path;
        } else {
            $fp = fopen($full_path, 'w');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:86.0) Gecko/20100101 Firefox/86.0');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            if (curl_errno($ch)) {
                return $result;
            }
            curl_close($ch);
            fclose($fp);
            if (@filesize($full_path) > 0) {
                $result = $path;
            } else {
                sleep(1);
                $time--;
                $result = $this->download($path, $params, $time);
            }
        }
        if ($list_images && $result) {
            foreach ($list_images as $image) {
                $desc_img = $this->getRealPath($image['path']);
                list($src_width, $src_height, $type) = getimagesize($full_path);
                $ratio = $image['width'] / $src_width;
                if ($image['height'] / $src_height < $ratio) {
                    $ratio = $image['height'] / $src_height;
                }
                $destinationWidth = $nextWidth = round($src_width * $ratio);
                $destinationHeight = $nextHeight = round($src_height * $ratio);
                $dst_x = $dst_y = 0;
                if ($image['height'] >= $src_height && $image['width'] >= $src_width) {
                    if ($image['width'] > $src_width) {
                        $dst_x = ($destinationWidth - $src_width) / 2;
                        $nextWidth = $src_width;
                    }
                    if ($image['height'] > $src_height) {
                        $dst_y = ($destinationHeight - $src_height) / 2;
                        $nextHeight = $src_height;
                    }
                }
                //imagecopyresized($desc_img, $full_path, 0, 0, 0, 0, $image['width'], $image['height'], $src_width, $src_height);
                $new_img = imagecreatetruecolor($destinationWidth, $destinationHeight);
                if ($type == IMAGETYPE_PNG) {
                    imagealphablending($new_img, false);
                    imagesavealpha($new_img, true);
                    $transparent = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
                    imagefilledrectangle($new_img, 0, 0, $destinationWidth, $destinationHeight, $transparent);
                    $original_image = imagecreatefrompng($full_path);
                } else {
                    $white = imagecolorallocate($new_img, 255, 255, 255);
                    imagefilledrectangle($new_img, 0, 0, $destinationWidth, $destinationHeight, $white);
                    $original_image = imagecreatefromjpeg($full_path);
                }
                $new_path = $desc_img;
                imagecopyresized($new_img, $original_image, (int) $dst_x, (int) $dst_y, '0', '0', $nextWidth, $nextHeight, $src_width, $src_height);
                if ($type == IMAGETYPE_PNG) {
                    imagepng($new_img, $new_path);
                } else {
                    imagejpeg($new_img, $new_path, 100);
                }
            }
        }
//        if(!$result){
//            $time--;
//            $result = $this->download($path, $params, $time);
//        }
        return $result;
    }

    public function info($path) {
        $full_path = $this->getRealPath($path);
        if (file_exists($full_path)) {
            return @getimagesize($full_path);
        }
        return false;
    }
    
    public function size($path) {
        $full_path = $this->getRealPath($path);
        if (file_exists($full_path)) {
            return @filesize($full_path);
        }
        return false;
    }

    public function exists($path, $params = array()) {
        $full_path = $this->getRealPath($path);
        return file_exists($full_path);
    }

    public function rename($path, $params = array()) {
        $path = ltrim($path, '/');
        $new_path = $path;
        $full_path = $this->getRealPath($new_path);
        $i = 1;
        while (file_exists($full_path)) {
            $new_path = $this->createFileSuffix($path, $i);
            $full_path = $this->getRealPath($new_path);
            $i++;
        }
        return $new_path;
    }

    public function delete($path, $params = array()) {
        $result = true;
        if (!$this->exists($path)) {
            return $result;
        }
        $full_path = $this->getRealPath($path);
        $result = @unlink($full_path);
        return $result;
    }

    public function content($path, $params = array()) {
        $result = '';
        $full_path = $this->getRealPath($path);
        if (!$this->exists($path)) {
            return $result;
        }
        $result = @file_get_contents($full_path);
        return $result;
    }

    public function copy($path, $params = array()) {
        $result = false;
        $override = $this->getParams('override', $params);
        $copy_path = $this->getParams('copy', $params);
        if (!$copy_path) {
            return $result;
        }
        if (!$this->exists($path)) {
            return $result;
        }
        if ($this->exists($copy_path)) {
            if (!$override) {
                return true;
            }
            $delete_file = $this->delete($copy_path);
            if (!$delete_file) {
                return true;
            }
        }
        $full_path = $this->getRealPath($path);
        $full_copy_path = $this->getRealPath($copy_path);
        $this->createParentDir($full_copy_path);
        $result = @copy($full_path, $full_copy_path);
        return $result;
    }

    public function move($path, $params = array()) {
        $result = false;
        $override = $this->getParams('override', $params);
        $move_path = $this->getParams('move', $params);
        if (!$move_path) {
            return $result;
        }
        if (!$this->exists($path)) {
            return $result;
        }
        if ($this->exists($move_path)) {
            if (!$override) {
                return true;
            }
            $delete_file = $this->delete($move_path);
            if (!$delete_file) {
                return true;
            }
        }
        $full_path = $this->getRealPath($path);
        $full_move_path = $this->getRealPath($move_path);
        $this->createParentDir($full_move_path);
        $result = rename($full_path, $full_move_path);
        return $result;
    }

    public function getRealPath($path) {
        $real_path = ABSPATH . ltrim($path, '/');
        return $real_path;
    }

    public function createParentDir($path, $mode = 0777) {
        $result = true;
        if (!is_dir(dirname($path))) {
            $result = @mkdir(dirname($path), 0777, true);
        }
        return $result;
    }

    public function createFileSuffix($file_path, $suffix, $character = '_') {
        $new_path = '';
        $dir_name = pathinfo($file_path, PATHINFO_DIRNAME);
        $file_name = pathinfo($file_path, PATHINFO_FILENAME);
        $file_ext = pathinfo($file_path, PATHINFO_EXTENSION);
        if ($dir_name && $dir_name != '.')
            $new_path .= $dir_name . '/';
        $new_path .= $file_name . $character . $suffix . '.' . $file_ext;
        return $new_path;
    }

}

class NCWM_Response {

    public static function displayResponse($result, $msg, $data) {
        $response = array();
        $response['result'] = $result;
        $response['msg'] = $msg;
        $response['data'] = $data;
        header("Content-Type: text/plain");
        echo base64_encode(serialize($response));
        exit();
    }

    public static function error($msg = null, $data = null) {
        self::displayResponse('error', $msg, $data);
    }

    public static function success($msg = null, $data = null) {
        self::displayResponse('success', $msg, $data);
    }

}

class NCWM_Db {

    public $error = null;

    public function query($query) {
        global $wpdb;
        $results = $wpdb->query($query);
        if (false === $results) {
            $this->error = $wpdb->last_error;
        }
        return $results;
    }

    public function select($query) {
        global $wpdb;
        $results = $wpdb->get_results($query, ARRAY_A);
        if ($results === null) {
            $this->error = $wpdb->last_error;
        }
        return $results;
    }

    public function insert($query, $params) {
        global $wpdb;
        $results = $wpdb->query($query);
        if (false === $results) {
            $this->error = $wpdb->last_error;
        }
        return $wpdb->insert_id;
    }

    public function getError() {
        return $this->getMsgError();
    }

    public function getMsgError() {
        return $this->error;
    }

    public function processQuery($type, $query, $params = null) {
        $result = null;
        switch ($type) {
            case 'select':
                $result = $this->select($query);
                break;
            case 'insert':
                $result = $this->insert($query, $params);
                break;
            case 'query':
                $result = $this->query($query);
                break;
            default:
                $result = $this->query($query);
                break;
        }
        return $result;
    }

}

abstract class NCWM_Cart {

    public $tablePrefix = '';
    public $imageDir = '';
    public $imageDirCategory = '';
    public $imageDirProduct = '';
    public $imageDirManufacturer = '';
    public $version = '';
    public $charset = 'utf8';
    public $cookie_key = '';
    public $extend = '';
    public $check;
    public static $instance = null;

    abstract public function loadConfig();

    public static function instance($cart_type = '', $check = false) {
        if (is_null(self::$instance)) {
            $class = self::getClass($cart_type);
            if (!$class) {
                return null;
            } else {
                self::$instance = new $class($check);
            }
        }
        return self::$instance;
    }

    public static function getClass($cart_type = '') {
        if ($cart_type && is_string($cart_type)) {
            $class = __CLASS__ . '_' . ucfirst($cart_type);
            if (class_exists($class)) {
                return $class;
            } else {
                return null;
            }
        }
        return null;
    }

    public static function relativePath($from, $to, $ps = DIRECTORY_SEPARATOR) {
        $arFrom = explode($ps, rtrim($from, $ps));
        $arTo = explode($ps, rtrim($to, $ps));
        $check = false;
        while (count($arFrom) && count($arTo) && ($arFrom[0] == $arTo[0])) {
            array_shift($arFrom);
            array_shift($arTo);
            $check = true;
        }
        if ($check) {
            return str_pad("", count($arFrom) * 3, '..' . $ps) . implode($ps, $arTo);
        } else {
            return $to;
        }
    }

    public function __construct($check = false) {
        $this->check = $check;
        $this->loadConfig();
    }

}

class NCWM_Cart_Woocommerce extends NCWM_Cart {

    public function loadConfig() {
        global $wpdb;
        if ($this->check) {
            $this->charset = $wpdb->charset;
            $this->tablePrefix = $wpdb->prefix;
            $upload_dir = wp_upload_dir();
            $this->imageDir = self::relativePath(ABSPATH, $upload_dir['basedir']);
            $this->imageDirCategory = $this->imageDir;
            $this->imageDirProduct = $this->imageDir;
            $this->imageDirManufacturer = $this->imageDir;
            $this->version = defined('WC_VERSION') ? WC_VERSION : '1.0';
            if (in_array('polylang/polylang.php', (array) get_option('active_plugins', array()), true) || in_array('polylang-pro/polylang.php', (array) get_option('active_plugins', array()), true)) {
                $this->version .= ':pll';
            }
            if (in_array('woocommerce-multilingual/wpml-woocommerce.php', (array) get_option('active_plugins', array()), true)) {
                $this->version .= ':wpml';
            }
        }
    }

}

class NCWM_Cart_Wordpress extends NCWM_Cart {

    public function loadConfig() {
        global $wpdb;
        if ($this->check) {
            $this->charset = $wpdb->charset;
            $this->tablePrefix = $wpdb->prefix;
            $upload_dir = wp_upload_dir();
            $this->imageDir = self::relativePath(ABSPATH, $upload_dir['basedir']);
            $this->imageDirCategory = $this->imageDir;
            $this->imageDirProduct = $this->imageDir;
            $this->imageDirManufacturer = $this->imageDir;
            $this->version = defined('WC_VERSION') ? WC_VERSION : '1.0';
        }
    }

}

class NCWM_Action_Clearcache extends NCWM_Action {

    public function run($params = array()) {
        $cart_clearcaches = $this->getParams('clearcaches', $params);
        $data = array();
        if ($cart_clearcaches && is_string($cart_clearcaches)) {
            $clearcaches = unserialize(base64_decode($cart_clearcaches));
            foreach ($clearcaches as $key => $clear_cache) {
                $data = $this->processClearCache($clear_cache['type']);
            }
            if ($data) {
                NCWM_Response::success('', $data);
            } else {
                NCWM_Response::error('Cannot reindex the Target Store.');
            }
        } else {
            NCWM_Response::success('');
        }
        return;
    }

    public function processClearCache($type) {
        $func = strtolower($type) . 'ClearCache';
        $result = $this->$func();
        return $result;
    }

    ########

    protected function _removeDirRec($dir, $removeDir = true, $fileExclude = '') {
        if (!@file_exists($dir)) {
            return true;
        }

        $result = true;
        if ($objs = glob($dir . '/*')) {
            foreach ($objs as $obj) {
                if ((trim($fileExclude) != '') && strpos($obj, $fileExclude) !== false) {
                    continue;
                }
                if (is_dir($obj)) {
                    $this->_removeDirRec($obj, true, $fileExclude);
                } else {
                    if (!@unlink($obj)) {
                        $result = false;
                    }
                }
            }
        }

        if ($removeDir && !@rmdir($dir)) {
            $result = false;
        }

        return $result;
    }

}
