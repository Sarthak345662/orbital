<?php
require get_template_directory() . '/vendor/admin-notices/persist-admin-notices-dismissal.php';
add_action( 'admin_head', function () { ?>
    <style>
       .orbital-banner-promo,.orbital-banner-promo-full{display:flex;padding:0}.orbital-banner-promo-full{margin:0;padding:0}.orbital-banner-promo-logo{width:90px;display:flex;align-items:center;justify-content:center;padding-left:10px}.orbital-banner-promo-full .orbital-banner-promo-logo{margin:0;width:90px;height:90px}.orbital-banner-promo-content{margin-left:25px}.orbital-banner-promo-full .orbital-banner-promo-content{width:75%}.orbital-banner-promo-content h1{font-weight:600;color:#538ac6;margin-top:10px}.orbital-banner-title{font-size:1.3em;margin:8px 0 5px 0}.orbital-banner-promo-slacklogo{width:75px;height:75px;display:inline-block;padding:0;flex:0 0 5%}.orbital-banner-promo .orbital-banner-promo-slack-line1{font-size:18px;margin-top:0;line-height:21px}.orbital-banner-promo .orbital-banner-promo-slack-textlink{color:#e59544;text-decoration:none}.orbital-banner-promo .orbital-banner-promo-slack-textlink:hover{opacity:.8}.orbital-banner-promo-slack-line2{font-size:15px;margin:0;line-height:.75em}.orbital-banner-promo-slack-link{color:#888}a.orbital-btn-xs.orbital-banner-promo-slack-btn{margin:0 5px}
    </style>
<?php } );

function echo_promo($is_dismissible){
    $data_dismissible =($is_dismissible)? "support-orbital-banner":"";
    $class_dismissible =($is_dismissible)? "is-dismissible":"";
    ?>
    <div data-dismissible="<?php echo $data_dismissible; ?>" class="orbital-banner-promo notice notice-info <?php echo $class_dismissible;?>">
        <div class="orbital-banner-promo-logo"><img width="90" height="90" src="<?php echo get_template_directory_uri() . "/assets/images/logo.png";?>"></div>

        <div class="orbital-banner-promo-content">
            <h3 class="orbital-banner-title"><?php echo __( 'Welcome to Orbital Themes', 'orbital' ) ; ?></h3>
            <div class="orbital-banner-description">
                <div class="orbital-banner-description-padding-right-15">
                    <p class="orbital-banner-desciption-content">
                        <?php echo __( 'Do you have any question?', 'orbital' ) ; ?>
                        <?php echo __( 'Don\'t hesitate to contact our support team.', 'orbital' ); ?>
                    </p>
                    <p class="orbital-banner-promo-slack-line2">
                       <?php  _e('Click on the link below', 'orbital'); ?>
                    </p>
                </div>
                <div>
                    <h3 class="orbital-banner-button-link">
                        <a href="https://orbitalthemes.net/documentacion/#jump-soporte" target="_blank">
                            <?php echo __( 'Go to Support', 'orbital' ) ; ?>
                        </a>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function author_admin_notice(){
    if(isset($_GET["page"]) && $_GET["page"] === "orbital-licenses"){ 
        echo_promo(false);
    }elseif (  PAnD::is_admin_notice_active( 'support-orbital-banner' ) ) {
		echo_promo(true);
	}
}
add_action( 'admin_init', array( 'PAnD', 'init' ) );
add_action('admin_notices', 'author_admin_notice');