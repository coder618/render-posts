<?php
add_action( 'wp_ajax_nopriv_load_more_posts', 'load_more_posts' );
add_action( 'wp_ajax_load_more_posts', 'load_more_posts' );

function load_more_posts(){    
    // check nonce
    if( isset($_POST['nonce']) && 
        wp_verify_nonce( $_POST['nonce'], 'loadmore_ajax_request' ) === 1  || 
        wp_verify_nonce( $_POST['nonce'], 'loadmore_ajax_request' ) === 2 ) :
        
        // filter input
        foreach($_POST as $k => $i_v):
            $_POST[$k] = trim(strip_tags($i_v));
        endforeach;
        
        $post_type        = sanitize_text_field($_POST['post_type']);
        $page             = (int)(trim($_POST['page'])) + 1 ;
        $post_per_page    = sanitize_text_field(trim($_POST['posts_per_page']));
        $function_name    = sanitize_text_field($_POST['function_name']);
        
        $html             = '';

        // check if function post template avaiable otherwise we will use default one
        if(!function_exists($function_name)){
            $function_name = 'default_post_template';
        }

        $args = array(
            'post_type' => $post_type,
            "posts_per_page" => $post_per_page,
            "paged" => $page,
        );

        $the_query = new WP_Query( $args );

        $receive_posts = get_posts($args);

        foreach( $receive_posts as $s_p ):                
            $html .= "<div class='item'>";
                $html .= $function_name($s_p);
            $html .= "</div>";
        endforeach;

        echo  $html;
        die();

    else:
        echo false ;
        die();
    endif;
    
}