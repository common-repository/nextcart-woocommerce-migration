<div id="ncwm-woocommerce-migration">
    <div id="ncwm-main-process">
        <div class="ncwm-container">
            <div class="ncwm-progress-m clear">
                <div class="tab setup active">
                    <a class="circle" onClick="window.location.reload();" title="Go to step 1">
                        <i class="setup-icon awefas"></i>
                    </a>
                    <span class="title">Setup</span>
                </div>
                <span class="bar"></span>
                <div class="tab config">
                    <span class="circle">
                        <i class="config-icon awefas"></i>
                    </span>
                    <span class="title" style="margin-left: -14px;">Configuration</span>
                </div>
                <span class="bar"></span>
                <div class="tab run">
                    <span class="circle">
                        <i class="run-icon awefas"></i>
                    </span>
                    <span class="title">Migration</span>
                </div>
            </div>
            <div class="notice notice-info">
                <p>Migrate your Online Store to WooCommerce: <a href="https://next-cart.com/blog/migrate-your-online-store-to-woocommerce/" target="_blank">Must Things You Need You Know</a></p>
            </div>
            <div class="ncwm-process-content">
                <ul id="ncwm-error-messages"></ul>
                <input type="hidden" name="controller" value="migration">
                <div id="ncwm-process-form-step">
                    <div id="ncwm-process-child-setup">
                        <?php ncwm_display_template('setup.php'); ?>
                    </div>
                    <div id="ncwm-process-child-config"></div>
                    <div class="clearfix"></div>
                </div>
                <div class="ncwm-process-area">
                    <a class="ncwm-process-button fill-button" id="ncwm-setup-submit" data-loading-text="<i class='spinner-icon awefal loading'></i> Processing">
                        Next Step
                    </a>
                    <a class="ncwm-process-button fill-button" id="ncwm-config-submit" data-loading-text="<i class='spinner-icon awefal loading'></i> Processing" style="display: none">
                        Next Step
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="ncwm-demo-message">
        <div class="ncwm-container">
            <div class="notice-box">
                <p>
                    <b>Notice:</b>
                <ul>
                    <li><b>***Important</b>: Please read the instructions in the <b><a href="<?php echo get_admin_url() . 'admin.php?page=nextcart-how-it-works'; ?>">How It Works</a></b> page carefully before performing the migration.</li>
                    <li>Would you like our technicians to do the demo migration for you? Please <a target="_blank" href="https://support.next-cart.com/support/tickets/new">click here</a> to request a test migration that is taken care of by our experienced technicians. This is completely free!</li>
                    <li><b>If you continuously get the 504 Gateway Timeout error message, please contact your hosting provider to upgrade the PHP settings related to memory limit and execution time limit.</b></li>
                    <li><b>If you have any problems with the migration, please don't hesitate to <a target="_blank" href="https://next-cart.com/support-center/">submit a ticket</a>. We will solve all issues until you are pleased with the results.</b></li>
                    <li>Cannot find your shopping cart here? Please feel free to <a target="_blank" href="https://next-cart.com/contact/">send us a message</a>. Your new migration tool will be ready within 2 days.</li>
                    <li>The free version of Next-Cart Migration Tool consist of some limitations: no background process, slow migration speed, limited entity at 50, daily quota limit: 5 migrations per day (EST timezone). You could buy Pro License here: <a href="https://next-cart.com/supported-carts/woocommerce/" target="_blank">WooCommerce Migration</a> to unlock all features: <b>run in background, optimized migration speed, unlimited re-migration</b>.</li>
                </ul>
                </p>
            </div>
        </div>
    </div>
    <div class="ncwm-footer">
        <span>&copy; Shopping Cart Migration Tool developed by <a target="_blank" href="https://next-cart.com">Next-Cart</a></span>
    </div>
    <div class="ncwm-confirm-box-container">
        <div class="confirm-box">
            <div class="cb-info"></div>
            <h2>Are you sure?</h2>
            <span>Start the migration process now!</span>
            <div class="button-container">
                <button class="cancel ncwm-cancel-btn ncwm-box-btn">Cancel</button>
                <button class="confirm ncwm-confirm-btn ncwm-box-btn">Yes, do it!</button>
            </div>
        </div>
    </div>
</div>
