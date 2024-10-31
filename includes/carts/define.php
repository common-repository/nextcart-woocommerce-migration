<?php
defined( 'ABSPATH' ) || exit;

class NCWM_Define {
    public function cartGroups() {
        return array(
            'connector' => array(
                'oscommerce',
                'opencart',
                'woocommerce'
            ),
            'api' => array(
                'shopify',
                'bigcommerce',
                'bigcommercev2',
                'ecwid',
                '3dcart',
                'americommerce',
                'lemonstand',
                'pinnaclecart',
                'neto',
                'maropost',
                'commercehq',
                'mivamerchant',
                'aspdotnetstorefront',
                'helcimcommerce',
                'wix',
                'square',
                'shopwired',
                'shopbase',
                'shift4shop',
                'wizishop',
                'vtex',
                'jumpseller',
                'cafe24',
                'gambiocloud',
                'lightspeed',
                'quickbutik',
                'ebay',
                'bluepark',
                'upgates',
                'clover',
                'lightspeedcloud',
                'vend',
                'storeden',
                'printify',
                'plentymarkets',
                'salesforce'
            ),
            'file' => array(
                'weebly',
                'volusion',
                'ekm',
                'adobebusinesscatalyst',
                'amazonstore',
                'squarespace',
                'nopcommerce',
                'ablecommerce',
                'bigcartel',
                'storenvy',
                'godaddy',
                'csv',
                'yahoo',
                'xml',
                'xlsx',
                'xls',
                'rain',
                'quickbooks',
                'wixcsv',
                'lightspeedsseries',
            )
        );
    }

    public function getCartGroup($name) {
        $groups = $this->cartGroups();
        foreach ($groups as $group => $carts) {
            foreach ($carts as $cart) {
                if ($cart == $name) {
                    return $group;
                }
            }
        }
        return 'connector';
    }

    public function getCartConnectType($name) {
        $type = $this->getCartGroup($name);
        if ($type == 'api') {
            return 'API';
        } elseif ($type == 'file') {
            return 'Files Upload';
        } else {
            return 'KitConnect';
        }
    }

    public function formInfos() {
        return array(
            'shopify' => array(
                'password' => "Access Token"
            ),
            'shopbase' => array(
                'api_key' => "API Key",
                'password' => "Password"
            ),
            'pinnaclecart' => array(
                'username' => 'Username API Access',
                'password' => 'Password API Access',
                'token' => 'Security Token'
            ),
            'weebly' => array(
                'product' => 'products.csv',
                'order' => 'orders.csv',
                'reviewf1' => 'reviewf1.txt',
                'reviewf2' => 'reviewf2.txt',
            //'coupon' => 'coupon.txt'
            ),
            'volusion' => array(
                'exchangeRates' => 'ExchangeRates.csv',
                'taxes' => 'Taxes.csv',
                'categories' => 'Categories.csv',
                'products' => 'Products.csv',
                'optionCategories' => 'OptionCategories.csv',
                'options' => 'Options.csv',
                'kits' => 'KITS.csv',
                'kitLinks' => 'KITLNKS.csv',
                'images' => 'ProductImages.csv',
                'customers' => 'Customers.csv',
                'orders' => 'Orders.csv',
                'orderDetails' => 'OrderDetails.csv',
                'trackingnumbers' => 'TrackingNumbers.csv',
                'reviews' => 'Reviews.csv'
            ),
            'ecwid' => array(
                'personal_collection_url' => 'Personal collection URL',
            ),
            'bigcommerce' => array(
                'api_path' => "API Path",
                'client_id' => "Client ID",
                'api_token' => "Access Token"
            ),
            'bigcommercev2' => array(
                'api_path' => "API Path",
                'client_id' => "Client ID",
                'api_token' => "Access Token"
            ),
            '3dcart' => array(),
            'americommerce' => array(
                'access_token' => "Access Token",
            ),
            'ekm' => array(
                'category' => 'Category.csv',
                'product' => 'Product.csv',
                'customer' => 'Customer.csv',
                'order' => 'Order.csv'
            ),
            'adobebusinesscatalyst' => array(
                'product' => 'Products(.csv)',
                'customer' => 'Customers(.csv)',
                'order' => 'Orders(.csv)'
            ),
            'amazonstore' => array(
                'product' => 'Products',
                'order' => 'Orders'
            ),
            'squarespace' => array(
                'product' => 'Products.csv',
                'order' => 'Orders.csv',
                'customer' => 'Customers.csv',
                'blog' => 'Blog.xml'
            ),
            'lemonstand' => array(
                'access_token' => "API Token",
            ),
            'nopcommerce' => array(
                'manufacturers' => 'manufacturers.xml',
                'categories' => 'categories.xml',
                'products' => 'products.xml',
                'products_excel' => 'products.xlsx',
                'customers' => 'customers.xml',
                'orders' => 'orders.xml',
                'orders_excel' => 'orders.xlsx'
            ),
            'ablecommerce' => array(
                'products' => 'PRODUCTS (.csv)',
                'options' => 'OPTIONS (.csv)',
                'variants' => 'VARIANTS (.csv)',
                'users' => 'USERS (.csv)',
                'orders' => 'ORDERS (.csv)',
            ),
            'bigcartel' => array(
                'order' => 'orders.csv (Unshipped)',
                'order_shipped' => 'orders.csv (Shipped)',
            ),
            'gambiocloud' => array(
                'email' => 'Admin Email',
                'password' => 'Password'
            ),
            'storenvy' => array(
                'products' => 'products.csv',
                'orders' => 'orders.csv'
            ),
            'neto' => array(
                'api_key' => 'API Key'
            ),
            'maropost' => array(
                'api_key' => 'API Key'
            ),
            'godaddy' => array(
                'ProductTemplate' => 'ProductTemplate.xls',
                'ProductToCategory' => 'ProductToCategory.xls',
                'ProductUpSellCrossSellTemplate' => 'ProductUpSellCrossSellTemplate.xls',
                'PricingTemplate' => 'PricingTemplate.xls',
                'OptionAssociationTemplate' => 'OptionAssociationTemplate.xls',
                'ImageExportTemplate' => 'ImageExportTemplate.xls',
                'Inventory' => 'Inventory.xls',
//                'LocationBasedTemplate' => 'LocationBasedTemplate.xls'
            ),
            'csv' => array(
                'products' => 'products.csv'
            ),
            'commercehq' => array(
                'api_key' => 'API Key',
                'api_password' => 'API Password'
            ),
            'mivamerchant' => array(
                'access_token' => 'Access Token'
            ),
            'aspdotnetstorefront' => array(
                'email' => 'Admin Email',
                'password' => 'Password'
            ),
            'helcimcommerce' => array(
                'account_id' => 'Account ID',
                'api_token' => 'API Token'
            ),
            'square' => array(
                'api_key' => 'API Key'
            ),
            'yahoo' => array(
                'products' => 'Products.csv',
                'images' => 'Objinfo.xml',
                'customers' => 'Customers.csv',
                'orders' => 'Orders.csv',
            ),
            'shopwired' => array(
                'api_key' => 'API Key',
                'api_secret' => 'API Secret'
            ),
            'wizishop' => array(
                'username' => 'Admin Username/Email',
                'password' => 'Password'
            ),
            'vtex' => array(
                'account_name' => 'Account Name',
                'app_key' => 'Application Key',
                'app_token' => 'Application Token'
            ),
            'jumpseller' => array(
                'api_login' => 'Login',
                'api_token' => 'Auth Token'
            ),
            'lightspeed' => array(
                'api_key' => 'API Key',
                'api_secret' => 'API Secret'
            ),
            'quickbutik' => array(
                'api_key' => 'API Key',
            ),
            'ebay' => array(
                'api_key' => 'API Key',
            ),
            'bluepark' => array(
                'api_username' => 'API Username',
                'api_key' => 'API Key',
            ),
            'upgates' => array(
                'api_path' => 'API URL',
                'api_login' => 'API Login',
                'api_key' => 'API Key',
            ),
            'clover' => array(
                'merchant_id' => 'Merchant ID',
                'api_token' => 'API Token'
            ),
            'lightspeedcloud' => array(
                'api_key' => 'API Key'
            ),
            'vend' => array(
                'api_key' => 'Personal Token'
            ),
            'storeden' => array(
                'api_key' => 'API Key',
                'api_exchange' => 'API Exchange'
            ),
            'printify' => array(
                'api_token' => 'API Token',
            ),
            'rain' => array(
                'categories' => 'categories.csv',
                'products' => 'products.csv',
                'images' => 'images.csv',
                'inventory' => 'inventory.csv',
                'customers' => 'customers.csv'
            ),
            'quickbooks' => array(
                'products' => 'products.xls',
                'customers' => 'customers.xls'
            ),
            'plentymarkets' => array(
                'username' => 'Admin Username',
                'password' => 'Password'
            ),
            'wixcsv' => array(
                'products' => 'products.csv',
                'contacts' => 'contacts.csv',
                'orders' => 'orders.csv'
            ),
            'lightspeedsseries'=> array(
                'products' => 'products.csv'
            ),
            'salesforce' => array(
                'access_token' => "Access Token",
            ),
        );
    }

    public function getFormFields($name) {
        $infos = $this->formInfos();
        return isset($infos[$name]) ? $infos[$name] : array();
    }

    public function cartInstructions() {
        return array(
            'shopify' => array(
                'placeholder' => "https://hostname.myshopify.com",
                'guide_url' => "https://next-cart.com/blog/how-to-get-shopify-api-credentials/",
                'guide_text' => "How to get Shopify URL and Access Token?"
            ),
            'bigcommerce' => array(
                'guide_url' => "https://next-cart.com/blog/how-to-get-bigcommerce-api-credentials/",
                'guide_text' => "How to get BigCommerce API credentials?"
            ),
            'bigcommercev2' => array(
                'guide_url' => "https://next-cart.com/blog/how-to-get-bigcommerce-api-credentials/",
                'guide_text' => "How to get BigCommerce API credentials?"
            ),
            '3dcart' => array(
                'placeholder' => "https://your3dcartstore.com",
                'guide_url' => "https://next-cart.com/blog/how-to-use-3dcart-rest-api/",
                'guide_text' => "How to set up 3dcart connection?"
            ),
            'shift4shop' => array(
                'placeholder' => "https://yourshift4shopstore.com",
                'guide_url' => "https://next-cart.com/blog/how-to-use-3dcart-rest-api/",
                'guide_text' => "How to set up Shift4Shop connection?"
            ),
            'americommerce' => array(
                'placeholder' => "https://hostname.americommerce.com",
                'guide_url' => "https://next-cart.com/blog/how-to-get-americommerce-api-credentials/",
                'guide_text' => "How to get AmeriCommerce REST API credentials?"
            ),
            'lemonstand' => array(
                'placeholder' => "http://hostname.lemonstand.com",
                'guide_url' => "https://next-cart.com/blog/how-to-get-lemonstand-api-token/",
                'guide_text' => "How to get LemonStand API token?"
            ),
            'pinnaclecart' => array(
                'placeholder' => "http://hostname.mypinnaclecart.com",
                'guide_url' => "https://next-cart.com/blog/how-to-get-pinnacle-cart-api-access/",
                'guide_text' => "How to get Pinnacle Cart API access?"
            ),
            'ecwid' => array(
                'placeholder' => "https://store-name.ecwid.com/",
                'guide_url' => "https://next-cart.com/blog/how-to-get-api-credentials-from-ecwid/",
                'guide_text' => "How to get Personal collection URL from Ecwid?"
            ),
            'gambiocloud' => array(
                'placeholder' => "https://shop-name.gambiocloud.com",
                'guide_text' => ""
            ),
            'storenvy' => array(
                'placeholder' => "https://store-name.storenvy.com",
            ),
            'neto' => array(
                'placeholder' => "https://www.yournetosite.com.au",
                'guide_url' => "https://next-cart.com/blog/how-to-get-neto-api-key/",
                'guide_text' => "How to get Neto API key?"
            ),
            'maropost' => array(
                'placeholder' => "https://www.yourmaropostsite.com.au",
                'guide_url' => "https://next-cart.com/blog/how-to-get-neto-api-key/",
                'guide_text' => "How to get Maropost API key?"
            ),
            'bigcartel' => array(
                'placeholder' => "https://store-name.bigcartel.com",
            ),
            'commercehq' => array(
                'placeholder' => "https://storename.commercehq.com",
                'guide_url' => 'https://next-cart.com/blog/how-to-get-commercehq-api-credentials/',
                'guide_text' => 'How to get CommerceHQ API Credentials?'
            ),
            'mivamerchant' => array(
                'guide_url' => 'https://next-cart.com/blog/how-to-get-miva-merchant-api-access-token/',
                'guide_text' => 'How to get Miva Merchant API Access Token?'
            ),
            'wix' => array(
                'guide_url' => 'https://next-cart.com/faq/how-to-set-up-wix-connection/',
                'guide_text' => 'How to set up Wix connection?'
            ),
            'wixcsv' => array(
                'guide_url' => 'https://next-cart.com/faq/how-to-export-data-from-wix/',
                'guide_text' => 'How to export data from Wix?'
            ),
            'volusion' => array(
                'guide_url' => 'https://next-cart.com/faq/how-to-export-data-from-volusion/',
                'guide_text' => 'How to export data from Volusion?'
            ),
            'weebly' => array(
                'guide_url' => 'https://next-cart.com/faq/how-to-export-data-from-weebly/',
                'guide_text' => 'How to export data from Weebly?'
            ),
            'ekm' => array(
                'guide_url' => 'https://next-cart.com/faq/how-to-export-data-from-ekm/',
                'guide_text' => 'How to export data from EKM?'
            ),
            'nopcommerce' => array(
                'guide_url' => 'https://next-cart.com/faq/how-to-export-data-from-nopcommerce/',
                'guide_text' => 'How to export data from nopCommerce?'
            ),
            'squarespace' => array(
                'guide_url' => 'https://next-cart.com/faq/how-to-export-data-from-squarespace/',
                'guide_text' => 'How to export data from Squarespace?'
            ),
            'square' => array(
                'placeholder' => "https://squareup.com",
                'guide_url' => 'https://next-cart.com/faq/how-to-set-up-square-api-key/',
                'guide_text' => 'How to get Square API Key?'
            ),
            'shopwired' => array(
                'placeholder' => "https://shop-name.myshopwired.co.uk",
                'guide_url' => 'https://next-cart.com/blog/how-to-get-shopwired-api-key-and-secret/',
                'guide_text' => 'How to get Shopwired API Key and Secret?'
            ),
            'godaddy' => array(
                'placeholder' => "https://shop-name.com",
                'guide_url' => 'https://next-cart.com/faq/how-to-export-products-from-godaddy/',
                'guide_text' => 'How to export products from Godaddy?'
            ),
            'shopbase' => array(
                'placeholder' => "https://hostname.onshopbase.com",
                'guide_url' => "https://next-cart.com/blog/how-to-get-shopbase-api-credentials/",
                'guide_text' => "How to get ShopBase API credentials?"
            ),
            'vtex' => array(
                'placeholder' => "https://accountname.myvtex.com",
                'guide_url' => "https://next-cart.com/blog/how-to-get-vtex-api-credentials/",
                'guide_text' => "How to get VTEX Application Key and Token?"
            ),
            'jumpseller' => array(
                'placeholder' => "https://shop-name.jumpseller.com",
                'guide_url' => "https://next-cart.com/blog/how-to-get-jumpseller-api-token/",
                'guide_text' => "How to get Jumpseller API Login and Token?"
            ),
            'cafe24' => array(
                'placeholder' => 'https://shop-name.cafe24shop.com',
                'guide_url' => "https://next-cart.com/faq/how-to-set-up-cafe24-connection/",
                'guide_text' => "How to set up Cafe24 connection?"
            ),
            'lightspeed' => array(
                'guide_url' => "https://next-cart.com/blog/how-to-get-lightspeed-api-credentials/",
                'guide_text' => "How to get Lightspeed eCom API credentials?"
            ),
            'quickbutik' => array(
                'guide_url' => "https://next-cart.com/blog/how-to-get-quickbutik-api-key/",
                'guide_text' => "How to get Quickbutik API Key?"
            ),
            'ebay' => array(
                'guide_url' => "https://next-cart.com/faq/how-to-set-up-ebay-api-key/",
                'guide_text' => "How to get eBay API Key?"
            ),
            'bluepark' => array(
                'guide_url' => "https://next-cart.com/blog/how-to-get-bluepark-api-username-and-key/",
                'guide_text' => "How to get Bluepark API Username and Key?"
            ),
            'upgates' => array(
                'guide_url' => "https://next-cart.com/blog/how-to-create-upgates-api-access/",
                'guide_text' => "How to create Upgates API access?"
            ),
            'clover' => array(
                'guide_url' => "https://next-cart.com/blog/how-to-create-clover-api-token/",
                'guide_text' => "How to create Clover API token?"
            ),
            'lightspeedcloud' => array(
                'guide_url' => "https://next-cart.com/blog/how-to-get-lightspeed-api-credentials/#lightspeed-r-series",
                'guide_text' => "How to get Lightspeed R-Series API Key?"
            ),
            'vend' => array(
                'placeholder' => "https://accountname.vendhq.com",
                'guide_url' => "https://next-cart.com/blog/how-to-get-lightspeed-api-credentials/#lightspeed-x-series-vend",
                'guide_text' => "How to get Vend Personal Token?"
            ),
            'storeden' => array(
                'guide_url' => 'https://next-cart.com/blog/how-to-get-storeden-api-keys/',
                'guide_text' => 'How to get Storeden API Keys?'
            ),
            'rain' => array(
                'guide_url' => 'https://next-cart.com/faq/how-to-export-data-from-rain-pos/',
                'guide_text' => 'How to export data from Rain?'
            ),
            'quickbooks' => array(
                'guide_url' => 'https://next-cart.com/faq/how-to-export-data-from-quickbooks/',
                'guide_text' => 'How to export data from QuickBooks?'
            ),
            'lightspeedsseries' => array(
                'guide_url' => 'https://next-cart.com/faq/how-to-export-data-from-lightspeed-retail-s-series/',
                'guide_text' => 'How to export data from Lightspeed S-Series?'
            ),
            'salesforce' => array(
                'placeholder' => "Instance Url",
                'guide_url' => 'https://next-cart.com/blog/how-to-generate-an-access-token-for-salesforce-api/',
                'guide_text' => 'How to get Salesforce URL and Access Token?'
            ),
        );
    }

    public function getCartInstruction($name) {
        $default = array(
            'placeholder' => "http://yourstoredomain.com",
            'guide_url' => "https://next-cart.com/contact/",
            'guide_text' => ""
        );
        $instructions = $this->cartInstructions();
        return isset($instructions[$name]) ? array_merge($default, $instructions[$name]) : $default;
    }

    public function allSourceTypes() {
        $single = '__source_cart_type__';
        $carts = array(
            'ubercart' => 'Ubercart (Drupal)',
            'nopcommerce' => 'nopCommerce',
            'volusion' => 'Volusion',
            'oscommerce' => 'OsCommerce',
            'oscmax' => 'osCmax',
            'virtuemart' => 'VirtueMart (Joomla)',
            'zencart' => 'Zen Cart',
            'interspire' => 'Interspire',
            'shopify' => 'Shopify',
            'bigcommerce' => 'BigCommerce',
            'bigcommercev2' => 'BigCommerce V2 (Old Version)',
            'mivamerchant' => 'Miva Merchant',
            'woocommerce' => 'WooCommerce (WordPress)',
            'prestashop' => "PrestaShop",
            'opencart' => 'OpenCart',
//            '3dcart' => '3dCart',
            'bigcartel' => 'Big Cartel',
            'amazonstore' => 'Amazon Store',
//            'yahoostore' => 'Yahoo Store/Aabaco',
            'jigoshop' => 'Jigoshop (WordPress)',
            'weebly' => 'Weebly',
            'squarespace' => 'Squarespace',
            'magento' => 'Magento (Adobe Commerce)',
            'adobecommerce' => 'Adobe Commerce',
            'pinnaclecart' => 'Pinnacle Cart',
            'wpecommerce' => 'WP e-Commerce (WordPress)',
            'cubecart' => "CubeCart",
            'oxideshop' => "OXID eShop",
            'cscart' => "CS-Cart",
            'hikashop' => "Hikashop (Joomla)",
            'xcart' => "X-Cart",
//            'jshop' => "Jshop Server",
//            'wix' => 'Wix',
            'americommerce' => 'AmeriCommerce',
            'ecwid' => "Ecwid (Lightspeed E-Series)",
            'adobebusinesscatalyst' => "Adobe Business Catalyst",
            'ekm' => "Ekm",
            'xtcommerce' => 'xt:Commerce',
            '3dcart' => '3dCart',
            'mijoshop' => 'MijoShop (Joomla)',
            'abantecart' => 'AbanteCart',
            'loadedcommerce' => 'Loaded Commerce',
            'lemonstand' => 'LemonStand',
            'shopp' => 'Shopp (WordPress)',
            'ablecommerce' => 'AbleCommerce',
            'litecart' => 'LiteCart',
            'gambio' => 'Gambio',
            'easydigitaldownloads' => 'Easy Digital Downloads (WordPress)',
            'storenvy' => 'Storenvy',
            'neto' => 'Neto (Maropost)',
            'maropost' => 'Maropost',
            'wix' => 'WIX',
            'godaddy' => 'GoDaddy',
            'wordpress' => 'WordPress',
            'joomla' => 'Joomla',
            'shopscript' => 'Shop-Script',
            'commercehq' => 'CommerceHQ',
            'aspdotnetstorefront' => 'AspDotNetStorefront',
            'helcimcommerce' => 'Helcim Commerce',
            'eshop' => 'EShop (Joomla)',
            'drupal' => 'Drupal',
            'kabiacommerce' => 'Kabia Commerce',
            'shopware' => 'Shopware',
            'joocart' => 'JooCart (Joomla)',
            'j2store' => 'J2Store (Joomla)',
            'sylius' => 'Sylius',
            'square' => 'Square',
            'yahoo' => 'Yahoo',
            'shopwired' => 'Shopwired',
            'bagisto' => 'Bagisto',
            'shopbase' => 'ShopBase',
            'shift4shop' => 'Shift4Shop',
            'vtex' => 'VTEX',
            'sunshop' => 'Sunshop',
            'jumpseller' => 'Jumpseller',
            'wpeasycart' => 'WP EasyCart (WordPress)',
            'gambiocloud' => 'Gambio Cloud',
            'wizishop' => 'WiziShop',
            'lightspeed' => 'Lightspeed eCom',
            'quickbutik' => 'Quickbutik',
            'ebay' => 'eBay',
            'bluepark' => 'Bluepark',
            'upgates' => 'Upgates',
            'clover' => 'Clover',
            'csv' => 'CSV',
            'xlsx' => 'XLSX',
            'xls' => 'XLS',
            'xml' => 'XML',
            'joomshopping' => 'JoomShopping (Joomla)',
            'lightspeedcloud' => 'Lightspeed R-Series',
            'lightspeedsseries' => 'Lightspeed S-Series',
            'vend' => 'Vend (Lightspeed X-Series)',
            'storeden' => 'Storeden',
            'printify' => 'Printify',
            'rain' => 'Rain POS',
            'quickbooks' => 'QuickBooks',
            'plentymarkets' => 'Plentymarkets',
            'wixcsv' => 'WIX CSV',
            'easystore' => 'EasyStore (Joomla)',
            'salesforce' => "Salesforce",
            'djcatalog2' => "DJ-Catalog2 (Joomla)",
        );
        ksort($carts);
        return isset($carts[$single]) ? array($single => $carts[$single]) : $carts;
    }

    public function allTargetTypes() {
        $single = '__target_cart_type__';
        $carts = array(
            'shopify' => 'Shopify',
            'bigcommerce' => 'BigCommerce',
            'xcart' => 'Xcart',
            'opencart' => 'Opencart',
            'magento' => 'Magento',
            '3dcart' => '3dCart',
            'oscommerce' => 'OsCommerce',
            'woocommerce' => 'WooCommerce',
            'prestashop' => "Prestashop",
            'cscart' => "CS-Cart",
            'americommerce' => 'AmeriCommerce',
            'gambio' => 'Gambio',
            'oscmax' => 'osCmax',
            'zencart' => 'ZenCart',
            'wordpress' => 'Wordpress',
            'joomla' => 'Joomla',
            'eshop' => 'EShop',
            'virtuemart' => 'VirtueMart',
            'loadedcommerce' => 'Loaded Commerce',
            'wix' => 'WIX',
        );
        ksort($carts);
        return isset($carts[$single]) ? array($single => $carts[$single]) : $carts;
    }
}
