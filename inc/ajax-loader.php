<?php


class Render_Posts_Ajax{


    public function render_posts_ajax_loadmore(){    
        
        // check nonce
        if( isset($_POST['nonce']) && 
            ( wp_verify_nonce( $_POST['nonce'], 'loadmore_ajax_request' ) === 1  || wp_verify_nonce( $_POST['nonce'], 'loadmore_ajax_request' ) === 2 )
        ):

            /**
             * SANITIZE Inputs
             * 
            */     
            foreach($_POST as $k => $v):
                $k = filter_var( sanitize_text_field($k) ,FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH );
                $v = filter_var( sanitize_text_field($v) ,FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH );
                $_POST[$k] = $v;
            endforeach;

            /**
             * Validate some Input Information
             * 
            */        
            // Get all the registered post information
            global $wp_post_types;
            // store post slug in a array
            $posts_type_arr = array_keys( $wp_post_types );

            //exit if post_type not Provide  or  post type not exist in the database
            if(  !array_key_exists('post_type', $_POST)  || !in_array($_POST['post_type'],$posts_type_arr) ){
                return '';
            }

            // collect posts per page
            if(  array_key_exists('posts_per_page', $_POST)  && is_numeric($_POST['posts_per_page'] ) ){
                $posts_per_page = $_POST['posts_per_page'];
            }else{
                $posts_per_page = get_option( 'posts_per_page' );
            }

            $function_name    = $_POST['function_name'];
            
            // Collect Page number
            $q_page = array_key_exists('page',$_POST) && is_numeric($_POST['page']) ? ($_POST['page'] + 1) : 1;
            
            $html = '';

            $args = array(
                'post_type' => $_POST['post_type'],
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
