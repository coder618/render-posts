<?php


class Render_Posts_Ajax{


    public function render_posts_ajax_loadmore(){    
        
        // check nonce
        if( isset($_POST['nonce']) && 
            ( wp_verify_nonce( $_POST['nonce'], 'loadmore_ajax_request' ) === 1  || wp_verify_nonce( $_POST['nonce'], 'loadmore_ajax_request' ) === 2 )
        ):     
            //exit if post_type not Provide  or  post type not exist in the wordpress
            if(isset($_POST['post_type']) && !empty($_POST['post_type'])){
                // Get all the registered post information
                global $wp_post_types;
                // store post slug in a array
                $posts_type_arr = array_keys( $wp_post_types );

                $post_type = filter_var( sanitize_text_field($_POST['post_type']) ,FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH );

                if(!in_array($post_type,$posts_type_arr)){
                    echo false ;
                    die();
                }           
            }else{
                echo false ;
                die();                
            }          
            

            // Collect Query Page number otherwise EXIT
            if(isset($_POST['page']) && !empty($_POST['page']) && intval($_POST['page'] ) > 0 ){
                $q_page = intval($_POST['page']) + 1;
            }else{
                echo false ;
                die();
            }
            
            // Grab Posts Per Page information
            if(isset($_POST['posts_per_page']) && !empty($_POST['posts_per_page']) && intval($_POST['posts_per_page'] ) >0   ){
                $posts_per_page = intval($_POST['posts_per_page'] );
            }else{
                $posts_per_page = get_option( 'posts_per_page' );
            }

            // Grab the category name
            if(isset($_POST['cat_name']) && !empty($_POST['cat_name']) && trim(strlen($_POST['cat_name'])) > 0   ){
                $category_name = trim($_POST['cat_name']);
            }


            // Make the Render function name
            $function_name = $post_type . '_template';

            $html = '';

            $args = array(
                'post_type' => $post_type,
                "posts_per_page" => $posts_per_page,
                "paged" =>  $q_page
            );
            // add  name in the argument 
            if(isset($category_name)){
                $args['category_name'] = $category_name;
            }


            // $receive_posts = get_posts($args);

            $file_path = get_template_directory() .'/loop-templates/content-'. $post_type.'.php';
            $file = file_exists($file_path);
            $not_found_str = __('Please Create a file at', 'render-posts');
            $not_found_str .= $file_path;
            $not_found_str .= __('to render the single template', 'render-posts');
            


            $posts_query = new WP_Query( $args );
            if ( $posts_query->have_posts() ) :
            
                while ( $posts_query->have_posts() ) : $posts_query->the_post(); 
                    if( $file ):                
                        echo "<div class='item render-posts-item'>";
                            get_template_part("loop-templates/content", $post_type);
                        echo "</div>";
                    else:
                        echo "<div class='item'>{$not_found_str}</div>";                       
                    endif;
                    
                endwhile;
                wp_reset_postdata();
            
            endif;

            die();

        else:
            echo false ;
            die();
        endif;
    }


}
