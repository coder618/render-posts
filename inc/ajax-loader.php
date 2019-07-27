<?php


class Render_Posts_Ajax{


    public function render_posts_ajax_loadmore(){    
        // echo "LOL";
        // die();
        
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

            

            $args = array(
                'post_type' => $post_type,
                "posts_per_page" => $post_per_page,
                "paged" => $page,
            );

            $the_query = new \WP_Query( $args );

            $receive_posts = get_posts($args);

            foreach( $receive_posts as $s_p ):                
                $html .= "<div class='item'>";

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
     * related with his posttype
     */
    private function  default_template($id){
        $c_id = $id; 
        $title = get_the_title($c_id);
        $post_img_id = get_post_thumbnail_id($c_id);
        $post_img_url = return_post_img_url( $c_id , 'large' );
        $title = get_the_title($c_id);
        $html = '';

        $html .= '<a href="'.get_permalink().'" class="default-post-template">';
            $html .= '<img src="'.$post_img_url.'" alt="'.$title.'">';
            
            $html .= '<div class="text-section">';
                $html .= '<h3 class="title">'.$title.'</h3>';            
            $html .= '</div>';
        $html .= '</a>';

        return $html;

    }

}
