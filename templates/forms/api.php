<?php if (!in_array($cart_type, array('square', 'ebay', 'lightspeedcloud', 'clover'))) { ?>
<div class="ncwm-form-field">
    <label for="<?php echo esc_attr($type . '_url') ?>"><?php echo esc_html(ucfirst($type) . ' Store URL') ?></label>
    <input class="must_url style_input" type="text" name="<?php echo esc_attr($type . '_url') ?>" value="" placeholder="<?php echo $guide['placeholder'] ? esc_url($guide['placeholder']) : esc_url('http://yourstoredomain.com'); ?>">
</div>
<?php } ?>
<?php
foreach ($fields as $value => $label) :
    ?>
    <div class="ncwm-form-field">
        <label for="<?php echo esc_attr($type . '_api') ?>"><?php echo $label ?></label>
        <input class="style_input" type="text" name="<?php echo esc_attr($type . '_api[' . $value . ']') ?>" value="">
    </div>
<?php endforeach; ?>
<div class="ncwm-form-field">
    <i class="from-icon awefas align-baseline"></i>
    <a href="<?php echo $guide['guide_url'] ? esc_url($guide['guide_url']) : esc_url('https://next-cart.com/support-center/') ?>" target="_blank"><?php echo $guide['guide_text'] ? esc_html($guide['guide_text']) : esc_html('How to get API credentials?') ?></a>
</div>
