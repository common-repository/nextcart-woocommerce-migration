<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class NCWM_Url_Redirect_Table extends WP_List_Table {
    
    public function __construct($args = array()) {
        parent::__construct(array(
            'singular'  => 'urlredirect',
            'plural'    => 'urlredirects',
            'screen'   => isset($args['screen']) ? $args['screen'] : null
        ));
    }
    
    public function get_title() {
        return array(
            'singular'  => ucfirst($this->_args['singular']),
            'plural'    => ucfirst($this->_args['plural'])
        );
    }
    
    public function get_columns() {
        $columns = array(
            'cb'            => '<input type="checkbox" />', //Render a checkbox instead of text
            'from'          => 'Request Path',
            'to'            => 'Target ID',
            'type'          => 'Target Type'
        );
        return $columns;
    }
    
    protected function get_default_primary_column_name() {
        return 'to';
    }

    protected function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="%s[]" value="%d" />',
            $this->_args['plural'],
            absint($item['redirect_id'])
        );
    }
    
    protected function column_default($item, $column_name) {
        switch ($column_name) {
            case 'from':
                return $item['request_path'];
            case 'to':
                return $item['target_id'];
            case 'type':
                return $item['target_type'];
            default:
                return '';
        }
    }


    protected function get_sortable_columns() {
        $c = array(
            'to'        => array('target_id', false),
            'type'      => array('target_type', false),
        );
        return $c;
    }
    
    protected function get_bulk_actions() {
        $actions = array();
        if (current_user_can('edit_posts')) {
            $actions = array(
                'delete' => 'Delete'
            );
        }
        return $actions;
    }
    
    public function prepare_items() {
        $licenses_per_page = 20;
        $paged = $this->get_pagenum();
        
        $licensesearch = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';
        
        $args = array(
            'number' => $licenses_per_page,
            'offset' => ( $paged - 1 ) * $licenses_per_page,
            'search' => $licensesearch,
            'fields' => 'all_with_meta'
        );
        
        if (isset($_REQUEST['orderby'])) {
            $args['orderby'] = $_REQUEST['orderby'];
        }

        if (isset($_REQUEST['order'])) {
            $args['order'] = $_REQUEST['order'];
        }
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $primary = $this->get_primary_column();
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);
        
        $data = $this->get_seo_url_search($args);
        $this->items = $data['items']; //array arrays
        $this->set_pagination_args(array(
            'total_items' => $data['total'],
            'per_page' => $licenses_per_page,
        ));
    }
    
    public function get_seo_url_search($args) {
        global $wpdb;
        
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM {$wpdb->prefix}nextcart_seo_url WHERE 1=1";
        
        $search = isset($args['search']) ? $args['search'] : '';
        if ($search) {
            $leading_wild = ( ltrim($search, '*') != $search );
            $trailing_wild = ( rtrim($search, '*') != $search );
            if ($leading_wild && $trailing_wild)
                $wild = 'both';
            elseif ($leading_wild)
                $wild = 'leading';
            elseif ($trailing_wild)
                $wild = 'trailing';
            else
                $wild = 'both';
            $search = strtolower(trim($search, '*'));
            $search_columns = array('request_path', 'target_id', 'target_type');
            $query .= $this->get_search_sql($search, $search_columns, $wild);
        }
        
        $order = isset($args['order']) ? $this->parse_order($args['order']) : 'DESC';
        $valid_ordersby = array('request_path', 'target_id', 'target_type');
        if (empty($args['orderby']) || !in_array($args['orderby'], $valid_ordersby)) {
            $ordersby = 'redirect_id';
        } else {
            $ordersby = $args['orderby'];
        }
        $query .= ' ORDER BY ' . $ordersby . ' ' . $order;
        
        if (isset($args['number']) && $args['number'] > 0) {
            if ($args['offset']) {
                $query .= ' ' . $wpdb->prepare("LIMIT %d, %d", $args['offset'], $args['number']);
            } else {
                $query .= ' ' . $wpdb->prepare("LIMIT %d, %d", 0, $args['number']);
            }
        }
        
        $items = $wpdb->get_results($query, ARRAY_A);
        $total = (int) $wpdb->get_var('SELECT FOUND_ROWS()');
        
        return array(
            'items' => $items,
            'total' => $total
        );
    }
    
    public function no_items() {
        _e('No URL redirects found.');
    }
    
    private function get_search_sql($string, $cols, $wild = false) {
        global $wpdb;

        $searches = array();
        $leading_wild = ( 'leading' == $wild || 'both' == $wild ) ? '%' : '';
        $trailing_wild = ( 'trailing' == $wild || 'both' == $wild ) ? '%' : '';
        $like = $leading_wild . $wpdb->esc_like($string) . $trailing_wild;

        foreach ($cols as $col) {
            if (in_array($col, array('target_id'))) {
                $searches[] = $wpdb->prepare("$col = %s", $string);
            } else {
                $searches[] = $wpdb->prepare("$col LIKE %s", $like);
            }
        }

        return ' AND (' . implode(' OR ', $searches) . ')';
    }
    
    private function parse_order($order) {
        if (!is_string($order) || empty($order)) {
            return 'DESC';
        }

        if ('ASC' === strtoupper($order)) {
            return 'ASC';
        } else {
            return 'DESC';
        }
    }

}
?>
<div class="wrap">
    <h2>Add New Redirect</h2>
    <form id="dataForm" name="dataform" method="POST" action="#">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label>Request Path:</label></th>
                    <td><input class="" type="text" placeholder="New Request Path" name="addrequest" style="min-width:20em;"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label>Target ID:</label></th>
                    <td><input class="" type="text" placeholder="Target ID" name="targetid" style="min-width:20em;"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label>Target Type:</label></th>
                    <td>
                        <select name="targettype">
                            <option value="category">Category</option>
                            <option value="product">Product</option>
                            <option value="page">Page</option>
                            <option value="post">Post</option>
                            <option value="post_category">Post Category</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <button class="button button-primary" id="submit" type="submit" value="submit">Submit</button>
        </p>
    </form>   
</div>
<?php
global $wpdb;
$table_name = $wpdb->prefix . 'nextcart_seo_url';
if ((isset($_POST['addrequest']) && $_POST['addrequest']) && (isset($_POST['targetid']) && $_POST['targetid']) && 
    (isset($_POST['targettype']) && $_POST['targettype']))
{
    $request_path = $_POST['addrequest'];
    $target_id = $_POST['targetid'];
    $target_type = $_POST['targettype'];
    $results = $wpdb->insert($table_name, 
        array(
            'request_path' => $request_path,
            'target_id' => $target_id,
            'target_type' => $target_type,
        ), 
        array(
            '%s',
            '%d',
            '%s',
        )
    );
    if ($results === false) {
        echo '<div class="error"><p>Failed to add new redirect.</p></div>';
    } else {
        echo '<div id="message" class="updated notice is-dismissible"><p>1 URL redirect added.</p></div>';
    }
}
