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

            // Make the Render function name
            $function_name = $post_type . '_template';

            $html = '';

            $args = array(
                'post_type' => $post_type,
                "posts_per_page" => $posts_per_page,
                "paged" =>  $q_page
            );

            $receive_posts = get_posts($args);

            foreach( $receive_posts as $s_p ):                
                $html .= "<div class='item render-posts-item'>";

                    // check if user define any function for the post template, otherwise we will use plugin default one
                    if(function_exists($function_name)){
                        $html .= $function_name($s_p->ID);    
                    }else{
                        $html .= $this->default_template($s_p->ID);
                    }

                $html .= "</div>";
            endforeach;

            echo  $html;
            die();

        else:
            echo false ;
            die();
        endif;
        
        
    }

    /**
     * Default Post template 
     * It will called when user will not provide any post template 
     * related with his post_type
     */
    private function  default_template($id){
        $c_id = $id; 
        $post_img_url = get_the_post_thumbnail_url($c_id, 'large');
        $title = esc_html(get_the_title($c_id));
        $html = '';

        $html .= '<a href="'.get_permalink($c_id).'" class="default-post-template">';
            if($post_img_url):
                $html .= '<img src="'.$post_img_url.'" alt="'.$title.'">';
            endif;
            
            $html .= '<div class="text-section">';
                $html .= '<h3 class="title">'.$title.'</h3>';
                $html .= '<p>'.get_the_excerpt($c_id).'</p>';
            $html .= '</div>';
        $html .= '</a>';

        return $html;

    }

}
