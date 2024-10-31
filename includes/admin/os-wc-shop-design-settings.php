<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Back end settings
 *
 * Back end settings
 *
 * @class 		shopDesignSettings
 * @version		1.2
 * @category	Class
 * @author 		Jinesh, Senior Software Engineer
 */
 
if ( ! class_exists( 'shopDesignSettings' ) ) :

	class shopDesignSettings { 
	
		/**
		 * Constructor
		 */
		
		public function __construct() { 
			
			add_action( 'admin_init', array( $this, 'shop_design_admin_init' ) );
			add_action( 'admin_menu', array( $this, 'shop_design_admin_settings' ) );
		}

		/**
		 * Creating a admin setting menu for design maker
		 */
		 
		public function shop_design_admin_settings() {
			
			add_submenu_page( 'woocommerce', __( 'Product Design Settings', WCSD_TEXT_DOMAIN ),  __( 'Design Settings', WCSD_TEXT_DOMAIN ) , 'manage_woocommerce', 'wc-design-settings', array( $this, 'wc_design_settings_page' ) );
		}

		/**
		* Setting function for ourteam blog
		*
		* @since  1.2
		*/
		 
		public function wc_design_settings_page () {
	
			ob_start();
	
			$options = get_option( 'shop_design_settings' );
				 
			// General option values
			$font_family = isset( $options['font_family'] ) ? esc_attr( $options['font_family'] ) : 'Open Sans';
			$add_to_cart_text = isset( $options['add_to_cart_text'] ) ? esc_attr( $options['add_to_cart_text'] ) : 'Add to Cart';
			
			// Heading option values
			$heading_font_size = isset( $options['heading_font_size'] ) ? esc_attr( $options['heading_font_size'] ) : '24px';
			$heading_font_color = isset( $options['heading_font_color'] ) ? esc_attr( $options['heading_font_color'] ) : '#000000';			
	
			// Content option values
			$content_font_size = isset( $options['content_font_size'] ) ? esc_attr( $options['content_font_size'] ) : '14px';
			$content_font_color = isset( $options['content_font_color'] ) ? esc_attr( $options['content_font_color'] ) : '#999999';
			
			// Color option values
			$bg_color = isset( $options['bg_color'] ) ? esc_attr( $options['bg_color'] ) : '#ED6409';
			$button_color = isset( $options['button_color'] ) ? esc_attr( $options['button_color'] ) : '#f6c542';
			$button_hover_color = isset( $options['button_hover_color'] ) ? esc_attr( $options['button_hover_color'] ) : '#cccccc';
			$button_font_color = isset( $options['button_font_color'] ) ? esc_attr( $options['button_font_color'] ) : '#666666';
			$custom_css = isset( $options['custom_css'] ) ? esc_attr( $options['custom_css'] ) : '';
			?>
			<div class="wrap">
				<h2><?php _e( "Product Design Settings", WCSD_TEXT_DOMAIN );?></h2>           
				<form method="post" action="options.php">
					<?php settings_fields( 'shop_design' ); ?>
					<div class="form-table">
						<div class="form-widefat">
							<h3><?php _e( "General Settings", WCSD_TEXT_DOMAIN );?></h3>
							<div class="row-table">
								<label><?php _e( "Font Family:", WCSD_TEXT_DOMAIN );?></label>
								<select id="font_family" name="shop_design_settings[font_family]">
									<option value="Arial" <?php selected( $font_family, 'Arial' ); ?>>Arial</option>
									<option value="Verdana" <?php selected( $font_family, 'Verdana' ); ?>>Verdana</option>
									<option value="Helvetica" <?php selected( $font_family, 'Helvetica' ); ?>>Helvetica</option>
									<option value="Roboto Condensed" <?php selected( $font_family, 'Roboto Condensed' ); ?>>Roboto Condensed</option>
									<option value="Comic Sans MS" <?php selected( $font_family, 'Comic Sans MS' ); ?>>Comic Sans MS</option>
									<option value="Georgia" <?php selected( $font_family, 'Georgia' ); ?>>Georgia</option>
									<option value="Trebuchet MS" <?php selected( $font_family, 'Trebuchet MS' ); ?>>Trebuchet MS</option>
									<option value="Times New Roman" <?php selected( $font_family, 'Times New Roman' ); ?>>Times New Roman</option>
									<option value="Tahoma" <?php selected( $font_family, 'Tahoma' ); ?>>Tahoma</option>
									<option value="Oswald" <?php selected( $font_family, 'Oswald' ); ?>>Oswald</option>
									<option value="Open Sans" <?php selected( $font_family, 'Open Sans' ); ?>>Open Sans</option>
									<option value="Fontdiner Swanky" <?php selected( $font_family, 'Fontdiner Swanky' ); ?>>Fontdiner Swanky</option>
									<option value="Crafty Girls" <?php selected( $font_family, 'Crafty Girls' ); ?>>Crafty Girls</option>
									<option value="Pacifico" <?php selected( $font_family, 'Pacifico' ); ?>>Pacifico</option>
									<option value="Satisfy" <?php selected( $font_family, 'Satisfy' ); ?>>Satisfy</option>
									<option value="Gloria Hallelujah" <?php selected( $font_family, 'TGloria Hallelujah' ); ?>>TGloria Hallelujah</option>
									<option value="Bangers" <?php selected( $font_family, 'Bangers' ); ?>>Bangers</option>
									<option value="Audiowide" <?php selected( $font_family, 'Audiowide' ); ?>>Audiowide</option>
									<option value="Sacramento" <?php selected( $font_family, 'Sacramento' ); ?>>Sacramento</option>
								</select>
								<div class="clear"></div>
							</div>
							<div class="row-table">
								<label><?php _e( "Add to Cart text:", WCSD_TEXT_DOMAIN );?></label>
								<input type="text" name="shop_design_settings[add_to_cart_text]" value="<?php echo $add_to_cart_text;?>" />
								<div class="clear"></div>
							</div>
							<h3><?php _e( "Heading Settings", WCSD_TEXT_DOMAIN );?></h3>                            
							<div class="row-table">
								<label><?php _e( "Font Size:", WCSD_TEXT_DOMAIN );?></label>
								<select name="shop_design_settings[heading_font_size]">
									<?php for( $i = 16; $i < 33; $i++ ) { ?> 
									<option value="<?php echo $i;?>px" <?php selected( $heading_font_size, $i . 'px' ); ?>><?php echo $i;?>px</option>
									<?php } ?>
								</select>
								<div class="clear"></div>
							</div>
							<div class="row-table">
								<label><?php _e( "Font Color:", WCSD_TEXT_DOMAIN );?></label>
								<input type="color" name="shop_design_settings[heading_font_color]" value="<?php echo $heading_font_color;?>" class="small" />
								<div class="clear"></div>
							</div>
							<h3><?php _e( "Content Settings", WCSD_TEXT_DOMAIN );?></h3>     
							<div class="row-table">
								<label><?php _e( "Font Size:", WCSD_TEXT_DOMAIN );?></label>
								<select name="shop_design_settings[content_font_size]">
									<?php for( $j = 10; $j < 21; $j++ ) { ?> 
									<option value="<?php echo $j;?>px" <?php selected( $content_font_size, $j . 'px' ); ?>><?php echo $j;?>px</option>
									<?php } ?>
								</select>
								<div class="clear"></div>
							</div>
							<div class="row-table">
								<label><?php _e( "Content Font Color:", WCSD_TEXT_DOMAIN );?></label>
								<input type="color" name="shop_design_settings[content_font_color]" value="<?php echo $content_font_color;?>" class="small" />
								<div class="clear"></div>
							</div>                        
							<h3><?php _e( "Display Settings", WCSD_TEXT_DOMAIN );?></h3>
							<div class="row-table">
								<label><?php _e( "Background Color:", WCSD_TEXT_DOMAIN );?></label>
								<input type="color" name="shop_design_settings[bg_color]" value="<?php echo $bg_color;?>" class="small" />
								<div class="clear"></div>
							</div>
							<div class="row-table">
								<label><?php _e( "Button Color:", WCSD_TEXT_DOMAIN );?></label>
								<input type="color" name="shop_design_settings[button_color]" value="<?php echo $button_color;?>" class="small" />
								<div class="clear"></div>
							</div>
							<div class="row-table">
								<label><?php _e( "Button Hover Color:", WCSD_TEXT_DOMAIN );?></label>
								<input type="color" name="shop_design_settings[button_hover_color]" value="<?php echo $button_hover_color;?>" class="small" />
								<div class="clear"></div>
							</div>
							<div class="row-table">
								<label><?php _e( "Button Font Color:", WCSD_TEXT_DOMAIN );?></label>
								<input type="color" name="shop_design_settings[button_font_color]" value="<?php echo $button_font_color;?>" class="small" />
								<div class="clear"></div>
							</div>
                            <h3><?php _e( "Custom CSS", WCSD_TEXT_DOMAIN );?></h3>     
							<div class="row-table">
								<textarea name="shop_design_settings[custom_css]" class="custom_css"><?php echo $custom_css; ?></textarea>
								<div class="clear"></div>
							</div>                    
						</div>
					</div>	                				
					<?php submit_button(); ?>
				</form>
			</div>
			<?php 
	
			return ob_get_contents();
		}  
	
		/**
		* Admin init ourTeam when WordPress Initialises.
		* @since  1.2
		*/
		 
		public function shop_design_admin_init() {
	
			register_setting(
				'shop_design', // Option group
				'shop_design_settings', // Option name
				array( $this, 'sanitize' ) // Sanitize
			);
		}
		
		/**
		* Sanitize each setting field as needed
		* @since 1.2
		*/
			 
		public function sanitize( $input ) {		
			 
			$new_input = array(); 

			foreach ( $input as $key => $value ) {
				if( isset( $input[$key] ) )
					$new_input[$key] = sanitize_text_field( $input[$key] );
			}		
				
			return $new_input;
		}
	}
	
endif;

/**
 * Returns the main instance of shopDesignSettings to prevent the need to use globals.
 *
 * @since  2.0
 * @return shopDesignSettings
 */
 
return new shopDesignSettings();
?>
