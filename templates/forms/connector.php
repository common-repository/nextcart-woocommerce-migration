<div class="ncwm-form-field">
    <label for="<?php echo esc_attr($type . '_url') ?>"><?php echo esc_html(ucfirst($type) . ' Store URL') ?></label>
    <input class="must_url style_input" type="text" name="<?php echo esc_attr($type . '_url') ?>" value="" placeholder="http://yourstoredomain.com">
</div>
<div class="ncwm-form-field" style="margin-top: 30px;">
    <a class="download-url" href="<?php echo esc_url('https://demo.next-cart.com/app/woocommerce/download.php?download=kitconnect&key=' . md5(get_site_url())) ?>">
        <i class="download-icon awefal"></i>
        <div>
            <b>DOWNLOAD</b>
            <p>Kitconnect Package</p>
        </div>
    </a>
</div>
<div class="ncwm-form-field">
    <p>Download, unzip and upload the Kitconnect package to the website root folder of your Source Store. Please refer to this <a href="https://next-cart.com/faq/what-is-kitconnect-package/" target="_blank">detailed guide</a>.</p>
</div>
<div class="ncwm-form-field">
    <p>Go to this URL: <i>[your_store_url]/[the_kitconnect_folder_name]/kitconnect.php</i>, you will get <b>"Hi! I am Kitconnect."</b> message if it is uploaded successfully.</p>
</div>
<div class="ncwm-form-field">
    <p><i>*Note: Kitconnect package is a PHP script that allows the Next-Cart Migration plugin to establish a connection to your Source Store.</i></p>
</div>