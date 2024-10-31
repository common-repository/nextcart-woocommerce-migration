<?php
$defineModel = new NCWM_Define();
$allSourceTypes = $defineModel->allSourceTypes();

$source_cart_type = array_keys($allSourceTypes)[0];
?>
<form action="" method="POST" id="ncwm-setup-form">
    <input type="hidden" name="func" value="setup">
    <div class="ncwm-area-full source-area">
        <div class="ncwm-area-content">
            <h3>Source Cart Setup</h3>
            <i class="from-icon awefas"></i>
            <div class="static-group">
                <div class="ncwm-form-field">
                    <label for="source_type">Source Cart Type</label>
                    <select id="ncwm-cart-select" name="source_type" class="form-control cart-select selectpicker" data-type="source" data-live-search="true">
                        <?php echo ncwm_option_to_html($allSourceTypes, $source_cart_type, true); ?>
                    </select>
                </div>
            </div>
            <div class="dynamic-group">
                <?php
                ncwm_display_template(
                        'forms/api.php',
                        array(
                            'cart_type' => $source_cart_type,
                            'type'      => 'source',
                            'fields'    => $defineModel->getFormFields($source_cart_type),
                            'guide'     => $defineModel->getCartInstruction($source_cart_type)
                        )
                );
                ?>
            </div>
            <div class="ncwm-setup-loader"></div>
        </div>
    </div>
</form>
    