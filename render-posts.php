 
<?php
/**
 * Plugin Name: Render Posts
 * Description: Render Posts Easily
 * Author: coder618
 * Author URI: https://coder618.github.io
 * Version: 1.0.0 
*/

require_once 'inc/ajax-loader.php'; // ajax request handler
require_once 'inc/enque.php'; // enque all the necessery file
require_once 'inc/post_template.php'; // default post template
require_once 'inc/register_shortcode.php'; // shortcode register

class Render_Posts_Main{

    public function __construct() {
        $this->load_dependencies();
        $this->reg_hooks();        

	}


    private function load_dependencies() {

		/**
		 * ajax request handler
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/ajax-loader.php';

        /**
		 *  default post template
		*/
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/post_template.php'; 
        
        /**
		 *  shortcode register
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/register_shortcode.php';

	}


    private function reg_hooks(){



        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

    }

    /**
     * Enque all Necessery assets
     * 
     */
    public function enqueue_assets() {
        wp_enqueue_script( 'render-posts-js', plugins_url( 'dist/script.js', dirname( __FILE__ ) ) , ['jquery'], 1,true );
        wp_enqueue_style( 'render-posts-styles', plugins_url( 'dist/style.css', dirname( __FILE__ ) ), [], 1, 'all' );
	}



}

new Render_Posts_Main();