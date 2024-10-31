<?php
/**
 * Plugin Name: OS WooCommerce Shop Design
 * Plugin URI: http://offshorent.com/blog/extensions/os-wc-shop-design
 * Description: OS WC Shop Design is giving some additional features and effects to the default woocommerce shop.
 * Version: 1.2
 * Author: Offshorent Solutions Pvt Ltd. | Jinesh.P.V
 * Author URI: http://offshorent.com/
 * Requires at least: 4.3
 * Tested up to: 4.7.4
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WCShopDesign' ) ) :

/**
 * Main WCShopDesign Class
 *
 * @class WCShopDesign
 * @version	1.2
 */

final class WCShopDesign {
	
	/**
	* @var string
	* @since 1.2
	*/
	 
	public $version = '1.2';

	/**
	* @var WCShopDesign The single instance of the class
	* @since 1.2
	*/
	 
	protected static $_instance = null;

	/**
	* Main WCShopDesign Instance
	*
	* Ensures only one instance of WCShopDesign is loaded or can be loaded.
	*
	* @since 1.2
	* @static
	* @return WCShopDesign - Main instance
	*/
	 
	public static function init_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
	}

	/**
	* Cloning is forbidden.
	*
	* @since 1.2
	*/

	public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wc-shop-design' ), '1.2' );
	}

	/**
	* Unserializing instances of this class is forbidden.
	*
	* @since 1.2
	*/
	 
	public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wc-shop-design' ), '1.2' );
	}
        
	/**
	* Get the plugin url.
	*
	* @since 1.2
	*/

	public function plugin_url() {
        return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	* Get the plugin path.
	*
	* @since 1.2
	*/

	public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	* Get Ajax URL.
	*
	* @since 1.2
	*/

	public function ajax_url() {
        return admin_url( 'admin-ajax.php', 'relative' );
	}
        
	/**
	* WCShopDesign Constructor.
	* @access public
	* @return WCShopDesign
	* @since 1.2
	*/
	 
	public function __construct() {
		
        register_activation_hook( __FILE__, array( $this, 'shop_design_install' ) );

        // Define constants
        self::shop_design_constants();

        // Include required files
        self::shop_design_includes();

        // Action Hooks
        add_action( 'init', array( $this, 'shop_design_init' ) );
        add_filter( 'body_class', array( $this, 'shop_design_body_class' ), 1 );
        add_action( 'admin_enqueue_scripts', array( $this, 'shop_design_admin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'shop_design_frontend_styles' ) );   
        add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'shop_design_template_loop_product_thumbnail' ), 10);
        add_action( 'woocommerce_before_shop_loop_item', array( $this, 'shop_design_wrap_div_before_shop_loop_item' ), 10 );      
        add_action( 'woocommerce_after_shop_loop_item', array( $this, 'shop_design_wrap_div_after_shop_loop_item' ), 10 ); 
        add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'shop_design_before_shop_loop_item_title' ), 10 );            
        add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'shop_design_after_shop_loop_item_title' ), 10 );            

        // Fliter Hooks
        add_filter( 'woocommerce_locate_template', array( $this, 'shop_design_woocommerce_locate_template' ), 10, 3 );
		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'shop_design_cart_button_text' ) );

	}
        
	/**
	* Install WCShopDesign
	* @since 1.2
	*/
	 
	public function shop_design_install (){
		
        // Flush rules after install
        flush_rewrite_rules();

        // Redirect to welcome screen
        set_transient( '_shop_design_activation_redirect', 1, 60 * 60 );
	}
        
	/**
	* Define WCShopDesign Constants
	* @since 1.2
	*/
	 
	private function shop_design_constants() {
		
		define( 'WCSD_PLUGIN_FILE', __FILE__ );
		define( 'WCSD_PLUGIN_BASENAME', plugin_basename( dirname( __FILE__ ) ) );
		define( 'WCSD_PLUGIN_URL', plugins_url() . '/' . WCSD_PLUGIN_BASENAME );
		define( 'WCSD_VERSION', $this->version );
		define( 'WCSD_TEXT_DOMAIN', 'shop_design' );
		define( 'WCSD_PERMALINK_STRUCTURE', get_option( 'permalink_struture' ) ? '&' : '?' );
		
	}
        
	/**
	* includes defaults files
	*
	* @since 1.2
	*/
	 
	private function shop_design_includes() {
		
		if( is_admin() ) {

			include_once( 'includes/admin/os-wc-shop-design-about.php' );
			include_once( 'includes/admin/os-wc-shop-design-settings.php' );
		}
	}
        
	/**
	* Init WCShopDesign when WordPress Initialises.
	* @since 1.2
	*/
	 
	public function shop_design_init() {
            
        self::shop_design_do_output_buffer();
        self::shop_design_alter_woo_functions();
	}
    
    /**
	* Alter WooCommerce default function
	* @since 1.2
	*/

	public function shop_design_alter_woo_functions() {

        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 15 );		
	}

    /**
	* Add web portfolio image sizes to WP
	* @since 1.2
	*/

	public function add_image_sizes() {

		add_image_size( 'shop_design', 197, 197, true );
	}

	/**
	* Clean all output buffers
	*
	* @since  1.2
	*/
	 
	public function shop_design_do_output_buffer() {
            
        ob_start( array( $this, "shop_design_do_output_buffer_callback" ) );
	}

	/**
	* Callback function
	*
	* @since  1.2
	*/
	 
	public function shop_design_do_output_buffer_callback( $buffer ){
        return $buffer;
	}
	
	/**
	* Clean all output buffers
	*
	* @since  1.2
	*/
	 
	public function shop_design_flush_ob_end(){
        ob_end_flush();
	}

	/**
	* Add new class for body tag.
	*
	* @return string
	*/

	public function shop_design_body_class( $classes ) {

        $classes[] = 'os-wc-shop-design';

		return $classes;
	}

	/**
	* woocommerce_before_shop_loop_item
	*
	* @since  1.2
	*/

	public function shop_design_wrap_div_before_shop_loop_item () {
		?>
		<div class="shop_design_wrap">
		<?php
	}

	/**
	* woocommerce_after_shop_loop_item
	*
	* @since  1.2
	*/

	public function shop_design_wrap_div_after_shop_loop_item () {
		?>
		</div>
		<?php
	}

	/**
	* woocommerce_before_shop_loop_item_title
	*
	* @since  1.2
	*/

	public function shop_design_before_shop_loop_item_title () {
		?>
		<div class="shop_design_title">
		<?php
	}

	/**
	* woocommerce_after_shop_loop_item_title
	*
	* @since  1.2
	*/

	public function shop_design_after_shop_loop_item_title () {
		?>
		</div>
		<?php
	}

	/**
	* WooCommerce Loop Product Thumbs
	**/

	public function shop_design_template_loop_product_thumbnail() {
		echo self::shop_design_get_product_thumbnail();
	} 

	/**
	 * Get the product thumbnail, or the placeholder if not set.
	*
	* @since  1.2
	*/

	public function shop_design_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
		
		global $post, $woocommerce, $product;
	
		$output = '<ul class="shop_design_thumbnail">';
		$attachment_ids = $product->get_gallery_attachment_ids();

		if ( has_post_thumbnail() ) {		
			$output .= '<li>' . get_the_post_thumbnail( $post->ID, $size ) . '</li>'; 			
		} else {		
			$output .= '<li><img src="'. woocommerce_placeholder_img_src() .'" alt="Placeholder" width="" height="" /></li>';		
		}
		
		foreach( $attachment_ids as $attachment_id ) {
			$image_full_attr = wp_get_attachment_image_src( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'full' ) );

			if( !empty( $image_full_attr[0] ) ) {
				$output .= '<li><img src="' . $image_full_attr[0] . '" alt="" /></li>';
			}
		}

		$output .= '</ul>';
		
		return $output;
	}

	/**
	* Change WooCommerce add to cart text
	*
	* @since  1.2
	*/

	public function shop_design_cart_button_text() {

		$options = get_option( 'shop_design_settings' );
		$add_to_cart_text = isset( $options['add_to_cart_text'] ) ? esc_attr( $options['add_to_cart_text'] ) : 'Add to Cart';
		
		return __( $add_to_cart_text, 'woocommerce' );
	}
	/**
	* admin style hook for WCShopDesign
	*
	* @since  1.2
	*/
	 
	public function shop_design_admin_styles() {	 

        wp_enqueue_style( 'admin-style', plugins_url( 'css/admin/style-min.css', __FILE__ ) );   
	}

	public function shop_design_woocommerce_locate_template( $template, $template_name, $template_path ) {
 
		global $woocommerce;

		$_template = $template;

		if ( ! $template_path ) $template_path = $woocommerce->template_url;
			$plugin_path  = self::plugin_path() . '/woocommerce/';

		// Look within passed path within the theme - this is priority
		$template = locate_template(
										array(
											$template_path . $template_name,
											$template_name
										)
									);
		// Modification: Get the template from this plugin, if it exists
		$full_path = $plugin_path . $template_name;

		if ( !$template && file_exists( $full_path ) )
			$template = $plugin_path . $template_name;

		// Use default template
		if ( ! $template )
			$template = $_template;

		return $template;
	}

	/**
	* Frontend style hook for WCShopDesign
	*
	* @since  1.2
	*/
	 
	public function shop_design_frontend_styles() {
		
		if( !is_admin() ){

			$options = get_option( 'shop_design_settings' );

			// General option values
			$font_family = isset( $options['font_family'] ) ? esc_attr( $options['font_family'] ) : '';

			// Heading option values
			$heading_font_size = isset( $options['heading_font_size'] ) ? esc_attr( $options['heading_font_size'] ) : '';
			$heading_font_color = isset( $options['heading_font_color'] ) ? esc_attr( $options['heading_font_color'] ) : '';         

			// Content option values
			$content_font_size = isset( $options['content_font_size'] ) ? esc_attr( $options['content_font_size'] ) : '';
			$content_font_color = isset( $options['content_font_color'] ) ? esc_attr( $options['content_font_color'] ) : '';

			// Color option values
			$bg_color = isset( $options['bg_color'] ) ? esc_attr( $options['bg_color'] ) : '';
			$button_color = isset( $options['button_color'] ) ? esc_attr( $options['button_color'] ) : '';
			$button_hover_color = isset( $options['button_hover_color'] ) ? esc_attr( $options['button_hover_color'] ) : '';
			$button_font_color = isset( $options['button_font_color'] ) ? esc_attr( $options['button_font_color'] ) : '';
			$back_custom_css = isset( $options['custom_css'] ) ? esc_attr( $options['custom_css'] ) : '';
			$custom_css = " .woocommerce.os-wc-shop-design ul.products.os-wcsd-product-wrap,
							.woocommerce.os-wc-shop-design .woocommerce-ordering select {
								font: {$content_font_size} {$font_family};
							}
							.woocommerce.os-wc-shop-design ul.products.os-wcsd-product-wrap li:hover {
								background: {$bg_color};
							}
							.woocommerce.os-wc-shop-design ul.products.os-wcsd-product-wrap li h3 {
								font-size: {$heading_font_size};
								color: {$heading_font_color};
								font-weight: 300;
							}
							.woocommerce.os-wc-shop-design ul.products.os-wcsd-product-wrap li h3:hover {
								color: {$content_font_color};
							}
							.os-wc-shop-design.woocommerce ul.products li.product .price-tag {
    							background-color: {$bg_color};
    						}
    						.os-wc-shop-design.woocommerce ul.products li.product a.add_to_cart_button.button {
    							background: {$button_color};
    							color: {$button_font_color};
    						}
    						.os-wc-shop-design.woocommerce ul.products li.product a.add_to_cart_button:before {
    							background: {$button_hover_color};
    						}
    						.os-wc-shop-design.woocommerce ul.products li.product a.add_to_cart_button:hover, 
							.os-wc-shop-design.woocommerce ul.products li.product a.add_to_cart_button:focus, 
							.os-wc-shop-design.woocommerce ul.products li.product a.add_to_cart_button:active {
							    color: #fff;
							}";
			$custom_css = $custom_css . $back_custom_css;

	        wp_enqueue_style( 'os-wcsd-fonts', '//fonts.googleapis.com/css?family=Oswald|Open+Sans|Fontdiner+Swanky|Crafty+Girls|Pacifico|Satisfy|Gloria+Hallelujah|Bangers|Audiowide|Sacramento|Roboto+Condensed' );
	        wp_enqueue_style( 'os-wcsd-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css' );
	        wp_enqueue_style( 'os-wcsd-main', plugins_url( 'css/style-min.css', __FILE__ ) );
	        wp_enqueue_style( 'os-wcsd-bxslider', plugins_url( 'plugins/bxslider/jquery.bxslider.css', __FILE__ ) );
	        wp_add_inline_style( 'os-wcsd-main', $custom_css );

	        wp_enqueue_script( 'os-wcsd-bxslider', plugins_url( 'plugins/bxslider/jquery.bxslider.js', __FILE__ ), array(), '3.0.8', true );  
	        wp_enqueue_script( 'os-wcsd-init', plugins_url( 'plugins/bxslider/init.js', __FILE__ ), array(), '3.0.8', true );  
        }      
	}
}

endif;

/**
 * Returns the main instance of WCShopDesign to prevent the need to use globals.
 *
 * @since  1.2
 * @return WCShopDesign
 */
 
return new WCShopDesign;
?>