<?php

?>
<div class="ib-tabs" id="ib-tabs">
    <?php
    echo $Layout->element($partials_path . "settings_tabs", array(
        'post_type' => $post_type,
        'active' => "License Key"));
    $layout = get_option(BREW_DEFAULT_LAYOUT_OPTION);
    ?>
</div>
<?php
?>
<div class="tabs">
    <div class="tab" id="license_key_settings">
        <div class="license-key-settings">
            <?php
            /* --inbound-brew-free-start-- */
                $upgrade_link = 'https://inboundbrew.com/signup-page/'
            ?>
            <style type="text/css">
                .ib-features-row {
                    display: flex;
                    padding-bottom: 2%;
                    border-bottom: dashed 1px #c9c9c9;
                    padding-top: 2%;
                    font-size: .75em;
                    width: 95%;
                    margin-left: auto;
                    margin-right: auto;
                }
                .ib-features-image {
                    width: 15%;
                    font-size: 6.5vw;
                    padding-top: 2%;
                    color: #f57722;
                }
                .ib-features-row div:first-child {
                    margin-right: 3%;
                }

            </style>
            
            <div class="ib-header">Licensing Info</div>
            <p class="ib-instructions" style="margin-bottom:10px;">You are currently using the free version.</p>
            <div class="ib-row ib-column ib-column-12">
                Upgrade to <a href=''>Inbound Brew Pro</a> today and put your lead capturing and nurturing on auto-pilot:

                <div class="ib-features-row">
                    <div class="ib-features-image">
                        <i class="fa fa-star-half-o" aria-hidden="true"></i>
                    </div>
                    <div class="ib-features-item">
                        <h1>Lead Scoring</h1>
                        <p>Let the system help dictate how hot a lead is. With lead scoring, you decide how a lead should be ranked based on the interactions they’ve had with you. Viewed a “buy it now” page? Score them high!&nbsp;</p>
                    </div>
                </div>

                <div class="ib-features-row">
                    <div class="ib-features-item">
                        <h1>Drip Campaigns</h1>
                        <p>Automatically enroll leads in drip campaigns so they get the emails you’ve setup, in order, at a frequency you determine. If the lead happens to take an action that is tied to a more aggressive campaign (like downloading an eBook, etc), have them automatically moved into the next campaign! Let them drive themselves down your marketing funnel!</p>
                    </div>
                    <div class="ib-features-image">
                        <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="ib-features-row">
                    <div class="ib-features-image">
                        <i class="fa fa-bullhorn" aria-hidden="true"></i>
                    </div>
                    <div class="ib-features-item">
                        <h1>Top bar and "Before You Leave" Popup CTAs</h1>
                        <p>Call To Actions (CTAs) create a entice your visitors to dig deeper. With Pro, you get access to a Top Bar CTA and Before you Leave popup CTAs.</p>
                    </div>
                </div>

                <p style="text-align: center;"><style>#inboundbrew_cta_1:hover{font-size:18px !important;text-shadow:0px 0px 0px # !important;color:#e8e8e8 !important;padding-left:14px !important;padding-right:14px !important;padding-top:7px !important;padding-bottom:7px !important;background:#42aaf5 !important;}#inboundbrew_cta_1:hover span{}</style><a id="inboundbrew_cta_1" class="cta-btn" data-role="cta-button" alt="Download Inbound Brew Pro Now" title="Download Inbound Brew Pro Now" href="<?php echo $upgrade_link; ?>" style="font-size: 18px; color: rgb(255, 255, 255); font-weight: bold; background: rgb(0, 115, 170); padding: 7px 14px; border-radius: 4px;">Get Started Now!</a></p>

            </div>
            <?php
                /* --inbound-brew-free-end-- */
            ?>

        </div>
    </div>
</div>
<script type='text/javascript'>
    jQuery(document).ready(function ($) {
        $(".dkim-verfication, .domain-verification").on("click", function () {
            $('#ib_license_key_settings').trigger('submit');
        });
    });
</script>

