<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    <h2>Next-Cart Migration Settings</h2>
    <form action="options.php" method="POST">
        <?php settings_fields('nextcart_setting'); ?>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label>Support ID</label></th>
                    <td>
                        <input style="min-width:500px;" type="text" id="nextcart_token" value="<?php echo get_option('nextcart_token', '__token__'); ?>" disabled />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="nextcart_url_redirect">Enable URL Redirects</label></th>
                    <td>
                        <input name="nextcart_url_redirect" type="checkbox" id="nextcart_url_redirect" value="1" <?php checked( 1 == $url_redirect ); ?> />
                        <p class="description"><?php _e('Enable this option to make the URL redirects work.'); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
