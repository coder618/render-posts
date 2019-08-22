<?php


class Render_Posts_Ajax{


    public function render_posts_ajax_loadmore(){    
        
        // check nonce
        if( isset($_POST['nonce']) && 
            wp_verify_nonce( $_POST['nonce'], 'loadmore_ajax_request' ) === 1  || 
            wp_verify_nonce( $_POST['nonce'], 'loadmore_ajax_request' ) === 2 ) :            
            // filter input
            foreach($_POST as $k => $v):
                $k = filter_var( sanitize_text_field($k) ,FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH );
                $v = filter_var( sanitize_text_field($v) ,FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH );
                $_POST[$k] = $v;
            endforeach;
            
            $post_type        = filter_var(sanitize_text_field($_POST['post_type']) ,FILTER_SANITIZE_STRING    );
            $post_per_page    = filter_var(sanitize_text_field($_POST['posts_per_page']),FILTER_SANITIZE_STRING    );
            $function_name    = filter_var(sanitize_text_field($_POST['function_name']),FILTER_SANITIZE_STRING    );
            
            // collect posts per page
            if( is_numeric( (int) $page ) ){
                $page             = (int)(trim($_POST['page'])) + 1 ;
                $posts_per_page = $page;
            }else{
                $posts_per_page = get_option( 'posts_per_page' );        
            }
            
            $html = '';

            

            $args = array(
                'post_type' => $post_type,
                "posts_per_page" => $post_per_page,
                "paged" => $page,
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

        $html .= '<a href="'.get_permalink().'" class="default-post-template">';
            if($post_img_url):
                $html .= '<img src="'.$post_img_url.'" alt="'.$title.'">';
            endif;
            
            $html .= '<div class="text-section">';
                $html .= '<h3 class="title">'.$title.'</h3>';
            $html .= '</div>';
        $html .= '</a>';

        return $html;

    }

}
