<?php
/* Short code for all post type
[
  render-posts
  type="posttype"
  number=6
  loadmore=true
]
*/

class Render_Post_Register_shortcode{

    public function render_posts( $atts ){

        /**
         * SANITIZE Inputs
         * 
        */     
        $valid_vars = [];
        foreach( $atts as $k=>$v ):            
            $k = trim(sanitize_text_field($k));
            $v = trim(sanitize_text_field($v));                        
            $valid_vars[$k] =  $v;
        endforeach;

        extract($valid_vars);

        /**
         * Validate Input Information
         * 
        */        

        // Get all the registered post information
        global $wp_post_types;
        // store post slug in a array
        $posts_type_arr = array_keys( $wp_post_types );

        //exit if type not Provide OR invalid post type
        if(  !isset($type)  || !in_array($type,$posts_type_arr) ){
            return '';
        }
        // collect posts per page
        if( isset($number) && intval( $number ) > 0 ){
            $posts_per_page = $number ;
        }else{
            $posts_per_page = get_option( 'posts_per_page' );        
        }

        $title_html = '';
        $load_more_html = '';
        $uid = uniqid();        
        
        $contain_title = false;

        // query argument 
        $args = [
            'posts_per_page'   => $posts_per_page ,
            'post_type'        => $type,   
            'paged'            => 1,
        ];

        if(isset( $cat ) && strlen($cat)){
            $args['category_name'] = trim($cat);
        }

    
        // Get the posts
        // $posts_arr = get_posts( $args );
        $posts_query = new WP_Query( $args );
    
        if( isset($title) || isset($detail) ){
            $contain_title = true;
        }
    
        // Prepare the title and the detail html if user provide
        if($contain_title===true){
            $title_html .= '<div class="post-title-section">';
                $title_html .= !empty($title) ? "<h2>$title</h2>" : '' ;
                $title_html .= !empty($detail) ? "<p>$detail</p>": '';
            $title_html .= "</div>";
        }

        $total_posts = $posts_query->found_posts;
        
        /**
         * Prepare the loadmore button html
         * 
         */
        // Check If current shown posts is smaller than total post and no loadmore define
        if( $total_posts > ($posts_query->post_count)  && !isset($noloadmore) ){
            $attr= [];
            $loadmore_att_str = '';
            $admin_url = admin_url('admin-ajax.php');
    
            // Prepare The ajax Request Necessary attribute
            $attr['data-posttype'] = $type;
            $attr['data-posts_per_page'] = $posts_per_page;            
            $attr['data-ajax-url'] = $admin_url;
            $attr['data-container'] = $uid;
            $attr['data-page'] = 1;
            $attr['data-nonce'] = wp_create_nonce("loadmore_ajax_request");
            $attr['data-total_posts'] = $total_posts;
    
            $attr['class'] = "btn btn-primary btn-lg load-more-posts-btn";

            if(isset( $cat ) && strlen($cat)){
                $attr['data-cat-name'] = trim($cat);
            }
    
            // Make the attribute string
            foreach($attr as $att => $att_val){
                $loadmore_att_str .= $att . " = '".$att_val."'";                    
            }
    
            $load_more_html .= '<div class="render-posts-loadmore-btn-container button-container">';
                $load_more_html .= '<button '.$loadmore_att_str.' >Load More</button>';            
            $load_more_html .= "</div>";
        } 
    
        
        $background = '';
        if( isset($bg) && !empty($bg) ){
            $background = 'bg-gray' ;
        }
        
        // Prepare wrapper class
        $wrapper_class = 'render-posts-main-wrapper '.$type.'-posts-wrapper '.$background  ;

        ob_start();
        ## check the file existency
        $file_path = get_template_directory() .'/loop-templates/content-'. $type.'.php';
        $file = file_exists($file_path);
        $not_found_str = __('Please Create a file at ', 'render-posts');
        $not_found_str .= $file_path;
        $not_found_str .= __(' to render the single template', 'render-posts');
        ?>

        <?php if ( $posts_query->have_posts() ) : ?>
        <div class="<?php echo $wrapper_class ?>">
            <div class='posts-wrapper <?php echo $type ?>-wrapper'>
                <?php echo $title_html ?>
                <div class='items items-container render-posts-items <?php echo $uid ?> post-type-<?php echo  $type ?>'>

                    <!-- the loop -->
                    <?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
                        
                        <?php 
                            if( $file ): // if file(template) exist                            
                                echo "<div class='item'>";
                                    get_template_part("loop-templates/content", $type);                                   
                                echo "</div>";
                            else:
                                echo "<div class='item'>{$not_found_str}</div>";                       
                            endif;
                        ?>      

                    <?php endwhile; ?>
                    

                    <?php wp_reset_postdata(); ?>

        
                </div><!-- items-container wrapper END -->    
                <?php echo $load_more_html ?>            
            </div>
        </div>

        <?php 
        endif; 
        return ob_get_clean();
    }
}