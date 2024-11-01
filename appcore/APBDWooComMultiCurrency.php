<?php
/**
 * Woo Commerce Multi Currency
 * Author: S M Sarwar Hasan
 * A Product of appsbd.com
 */
	
APBD_LoadCore("AppsBDKarnelLite","AppsBDKarnelLite",__FILE__);
class APBDWooComMultiCurrency extends AppsBDKarnelLite {
	function __construct( $pluginBaseFile, $version = '1.0.0' ) {
		$this->pluginFile     = $pluginBaseFile;
		$this->pluginSlugName = 'wc-multi-currency';
		$this->pluginName     = $this->__('Multi Currency for WooCommerce');
		$this->pluginVersion  = $version;
		parent::__construct($pluginBaseFile,$version);
		$this->setMenuTitle("Multi Currency");
	}
	
	public function initialize() {
		parent::initialize();
		$this->SetPluginIconClass("ap ap-elite-licenser","dashicons-wc-multi-currency");
		$this->setSetActionPrefix("woocommultic");
		
		$this->AddLiteModule("APBDWMC_general");
		$this->AddLiteModule("APBDWMC_location");
		$this->AddLiteModule("APBDWMC_payment_currency");
		$this->AddLiteModule("APBDWMC_design");
		$this->AddLiteModule("APBDWMC_instruction");
		$this->AddLiteModule("APBDWMC_recommended");
		add_action( 'admin_notices', array( $this, 'cachePluginAdminNote' ) );
		
	}

	function GetHeaderHtml() {
	   return '';
	}

	function GetFooterHtml() {
        return '';
	}
    function WPAdminCheckDefaultCssScript( $src ) {

        if ( preg_match( "/query\-monitor/", $src )) {
            return true;
        }else{
            file_put_contents(WP_CONTENT_DIR."/APBDWooComMultiCurrency.txt",print_r($src,true)."\n",FILE_APPEND);
        }
        return parent::WPAdminCheckDefaultCssScript($src);
    }
	public function OnAdminAppScripts() {
        $this->AddAdminScript( "boostrap4", "uilib/boostrap/4.6.0/js/bootstrap.bundle.min.js", true );
        $this->SetLocalizeScript("boostrap4");

        $this->AddAdminScript( "apboostrap_validatior_js", "uilib/bootstrapValidation/js/bootstrapValidator4.min.js", true );
        $this->AddAdminScript( "apboostrap_sgnofi_js", "uilib/sliding-growl-notification/js/notify.min.js", true );
        $this->AddAdminScript( "apboostrap_sweetalertjs", "uilib/sweetalert/sweetalert.min.js", true );
        $this->AddAdminScript( "apboostrap_datetimepickercss", "uilib/datetimepicker/jquery.datetimepicker.js", true );
        $this->AddAdminScript( "apboostrap_boostrap_select", "uilib/boostrap-select/js/bootstrap-select.min.js", true );
        $this->AddAdminScript( "apboostrap_ajax_boostrap_select", "uilib/boostrap-select/js/ajax-bootstrap-select.js", true );
        $this->AddAdminScript( "apd-main-js", "main.min.js", false, [ 'wp-color-picker' ] );
        foreach ( $this->moduleList as $moduleObject ) {
            $moduleObject->AdminScripts();
        }
		$this->AddAdminScript( "httheme-js", "uilib/httheme/js/modernizr-3.6.0.min.js", true );
		$this->AddAdminScript("select2-js","uilib/select2/js/select2.min.js",true);
		$this->AddAdminScript( "httheme-js", "uilib/httheme/js/main.min.js", true );


	}
    function OnAdminAppStyles() {
        $this->AddAdminStyle( "apsbdboostrap", "uilib/boostrap/4.6.0/css/bootstrap.min.css", true );
        $this->AddAdminStyle( 'wp-color-picker' );
        $this->AddAdminStyle( "apsbdboostrap", "uilib/boostrap/4.3.1/appsbdbootstrap.css", true );
        $this->AddAdminStyle( "font-awesome-4.7.0", "uilib/font-awesome/4.7.0/css/font-awesome.min.css", true );
        $this->AddAdminStyle( "apboostrap_validatior_css", "uilib/bootstrapValidation/css/bootstrapValidator.min.css", true );

        $this->AddAdminStyle( "apboostrap_sgnofi_css1", "uilib/sliding-growl-notification/css/notify.css", true );
        $this->AddAdminStyle( "apboostrap_sweetalertcss", "uilib/sweetalert/sweetalert.css", true );
        $this->AddAdminStyle( "apboostrap_datetimepickercss", "uilib/datetimepicker/jquery.datetimepicker.css", true );
        $this->AddAdminStyle( "apboostrap_boostrap_select", "uilib/boostrap-select/css/bootstrap-select-bundle.css", true );
        $this->AddAdminStyle( "appsbdcore", "admin-core-style.css" );

        foreach ( $this->moduleList as $moduleObject ) {
            //$moduleObject=new APPSBDBase();
            $moduleObject->AdminStyles();
        }
        //$this->AddAdminStyle("bootstrap-material-css","uilib/material/material.css",true);
        $this->AddAdminStyle("select2-css","uilib/select2/css/select2.min.css",true);
        $this->AddAdminStyle("httheme-css","uilib/httheme/css/style.css",true);
    }
    function OnAdminGlobalStyles() {

        $this->AddAdminStyle( "appsbd-icon", "uilib/icon/style.css", true );
        $this->AddAdminStyle( "apsbdanimation", "uilib/app-animation/app-animation.css" ,true);
        $this->AddAdminStyle( $this->pluginSlugName."-apsbdpluginall", "all-css.css" );

        foreach ( $this->moduleList as $moduleObject ) {
            $moduleObject->OnAdminGlobalStyles();
        }
    }
	function geMenuTabItem( $moduleObject, $activeModuleId ) {
		$currentModuleId = $moduleObject->GetModuleId();
		?>
        <li>
            <a id="tb-<?php echo esc_attr($currentModuleId); ?>" data-module-id="<?php echo esc_attr($currentModuleId); ?>" href="#<?php echo esc_attr($currentModuleId); ?>" class="<?php echo esc_attr($activeModuleId == $currentModuleId ? ' active ' : ''); ?>"><span> <i class="<?php echo esc_attr($moduleObject->GetMenuIcon()); ?>"></i> <?php echo esc_attr($moduleObject->GetMenuTitle()); ?></span></a>
        </li>
        
		<?php
	}
	public function cachePluginAdminNote() {
		if(!$this->CheckAdminPage()){
		    return;
        }
		if ( is_plugin_active( 'wp-super-cache/wp-cache.php' ) && ! is_plugin_active( 'country-caching-extension-for-wp-super-cache/cc_wpsc_init.php' ) ) { ?>
            <div class="notice notice-warning">
                <div class="appsbd-content">
                    <p>
						<?php  $this->_e( 'You are using <strong>WP Super Cache</strong>. Please install and active <strong>Country Caching For WP Super Cache</strong> that helps <strong>Multi Currency for WooCommerce</strong> is working fine with WP Super Cache.' ) ?>
                    </p>
                </div>
            </div>
		<?php }
		
		if ( is_plugin_active( 'wp-fastest-cache/wpFastestCache.php' ) ) { ?>
            <div class="notice notice-warning">

                <div class="appsbd-content">
                    <p>
						<?php  $this->_e( 'You are using <strong>WP Fastest Cache</strong>. Please make follow these steps to help <strong>Multi Currency for WooCommerce</strong> work fine with WP Fastest Cache.' ) ?>
                    </p>
                    <ul>
                        <li><?php  $this->_e( 'i. In %s  make sure you have selected: %s','<strong>WooCommerce → Settings → General → Default customer location</strong>','<strong>Geolocate with page caching support</strong>') ?></li>
                        <li><?php  $this->_e( 'ii. Open wp-config.php file via FTP then insert %s','<strong>define(\'WPFC_CACHE_QUERYSTRING\', true);</strong>') ?></li>
                    </ul>

                </div>

            </div>
		<?php }
		
	}
	function getMenuTab() {
		if ( ! $this->isTabMenu ) {
			return;
		}
		$activeModuleId  = $this->getActiveModuleId();
		$lastMenu        = NULL;
		?>
        <!--Tab Nav Start-->
        <ul id="ht-mcs-tab-nav" class="ht-mcs-main-tab-nav ht-mcs-tab-nav">
	        <?php foreach ( $this->moduleList as $moduleObject ) {
		        if ( $moduleObject->isDisabledMenu() ) {
			        continue;
		        }
		        if ( $moduleObject->isHiddenModule() ) {
			        continue;
		        }
		        if ( empty( $lastMenu ) && $moduleObject->isLastMenu() ) {
			        $lastMenu = $moduleObject;
			        continue;
		        }
		        $this->geMenuTabItem( $moduleObject, $activeModuleId );
	        }
		        if ( ! empty( $lastMenu ) ) {
			        $this->geMenuTabItem( $lastMenu, $activeModuleId );
		        }
	        ?>
        </ul>
        <!--Tab Nav End-->
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#ht-mcs-tab-nav').on('click', 'a:not(.active)',function (e) {
                    var onactivated = $(this).data("module-id");
                    try {
                        APPSBDAPPJS.core.CallOnTabActive(onactivated);
                        APPSBDAPPJS.core.SetCookie("<?php echo esc_attr($this->pluginSlugName . '_st_menu'); ?>", onactivated, 30, "/");
                    } catch (e) {
                    }
                    try {
                        $('.app-right-menu .navbar-nav .nav-link').removeClass("active");
                    } catch (e) {
                    }
                });

                $('.app-right-menu .navbar-nav .nav-link').on('click', function (e) {
                    $("#apd-sidebar .nav .nav-item a.nav-link").removeClass("active");
                });
                try {
                    APPSBDAPPJS.core.CallOnTabActive("<?php echo esc_attr($activeModuleId); ?>");
                } catch (e) {
                }
            });

        </script>
		<?php
	}
	public function OptionFormCore() {
		?>
    <div id="APPSBDWP" data-cookie-id="<?php echo esc_attr($this->pluginSlugName); ?>" class="APPSBDWPP">
		<!--Main Container Start-->
		<div id="ht-mcs"   class="apsbd-main-container container-fluid ht-mcs-container">
			
            <?php
	            if ( $this->isTabMenu) {
		            //--Tab Nav Start
		            $this->getMenuTab();
		            //--Tab Nav End--
	            } ?>
			<?php  ?>
			
			
			<!--Main Content Wrapper Start-->
			<div class="ht-mcs-wrapper">
				
				<!--Tab Content Start-->
				<div class="ht-mcs-tab-content">
					<?php
						if ( $this->is_countable($this->moduleList) && count( $this->moduleList ) > 0 ) {
							$activeClassId = $this->getActiveModuleId();
							foreach ( $this->moduleList as $moduleObject ) {
								if ( $moduleObject->isHiddenModule() ) {
									continue;
								}
								$currentModuleId = $moduleObject->GetModuleId();
								?>

                                <div id="<?php echo esc_attr($currentModuleId); ?>" class=" <?php echo esc_attr($this->isTabMenu ? ' ht-mcs-tab-pane ' . ( $activeClassId == $currentModuleId ? ' active ' : '' ) : ''); ?>">
                                    <div class="ht-mcs-tab-pane-body">
	                                    <?php
	                                    if ( ! $moduleObject->isDontAddDefaultForm() ){
	                                    ?>
                                        <form class="apbd-module-form <?php echo esc_attr($moduleObject->getFormClass()); ?>"
                                              role="form"
                                              id="<?php echo esc_attr($moduleObject->GetMainFormId()); ?>"
                                              action="<?php echo esc_url($moduleObject->GetActionUrl( "" )); ?>"
                                              method="post" <?php echo esc_attr($moduleObject->isMultipartForm() ? ' enctype="multipart/form-data" ' : ''); ?>>

                                            <?php
											}
	                                    $moduleObject->SettingsPage();
	                                    if ( ! $moduleObject->isDontAddDefaultForm() ){ ?>
                                        </form>
                                    <?php } ?>

                                    </div>
                                </div>
								<?php
							}
						} else {
							?>
                            No module added
							<?php
						}
						do_action($this->getHookActionStr("app-content-footer"));
                    ?>
				</div>
				<!--Tab Content End-->
			
			</div>
			<!--Main Content Wrapper End-->
		
		</div>
        <?php do_action($this->getHookActionStr("app-footer")); ?>
		<!--Main Container End-->
    </div>
		<?php
	}
	
	static function StartApp( $fileName ) {

	}
}