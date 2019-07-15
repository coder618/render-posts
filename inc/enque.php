<?php 
function render_posts_assets(){
	wp_enqueue_script( 'render-posts-js', plugins_url( 'dist/script.js', dirname( __FILE__ ) ) , ['jquery'], 1,true );
	wp_enqueue_style( 'render-posts-styles', plugins_url( 'dist/style.css', dirname( __FILE__ ) ), [], 1, 'all' );
}
add_action( 'init', 'render_posts_assets' );
