<?php
/**
 * Plugin Name: Render Posts
 * Description: Render Posts Easily
 * Author: coder618
 * Author URI: https://coder618.github.io
 * Version: 1.0.0 
*/

class Render_Posts_Main{

    public function __construct() {
        $this->load_dependencies();
        $this->reg_hooks();
	}

    private function load_dependencies() {

		/**
		 * ajax request handler
		 */
        require plugin_dir_path( __FILE__ ) .  'inc/ajax-loader.php';

        /**
		 *  shortcode register
		 */
        require plugin_dir_path( __FILE__ ) .  'inc/register_shortcode.php';

    }
    
    /**
     * All the Hook of this plugin will register within this methode
     * 
     */
    private function reg_hooks(){
        // enqueue scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

        // register shortcode
        add_shortcode("render-posts", [ new Render_Post_Register_shortcode(), 'render_posts'] );

        // Register Ajax Callback
        add_action( 'wp_ajax_nopriv_render_posts_ajax_loadmore', [ new Render_Posts_Ajax() , 'render_posts_ajax_loadmore'] );
        add_action( 'wp_ajax_render_posts_ajax_loadmore',[ new Render_Posts_Ajax() , 'render_posts_ajax_loadmore'] );
        
        
    }

    /**
     * Enque all Necessery assets
     * 
     */
    private function enqueue_assets() {
        wp_enqueue_script( 'render-posts-js', plugin_dir_url( __FILE__ ). 'dist/render-posts-script.js'  , ['jquery'], 1,true );
        wp_enqueue_style( 'render-posts-styles', plugin_dir_url( __FILE__ ). 'dist/style.css', [], 1, 'all' );
	}



}

new Render_Posts_Main();