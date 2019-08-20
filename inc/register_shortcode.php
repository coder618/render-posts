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

    public function __construct() {
        // add_shortcode("render-posts", $this->render_posts() );
    }
    
    public function render_posts( $atts ){


        // Remove unnecessery thing from string
        foreach( $atts as $k=>$v ):
            $atts[$k] =  trim( filter_var( strip_tags($v),FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH ) ) ;
        endforeach;

        // Get all the registered post information
        global $wp_post_types;
        // store post slug in a array
        $posts_type_arr = array_keys( $wp_post_types );

        //exit if type not porvide and invalid post type
        if(  !array_key_exists('type', $atts)  || !in_array($atts['type'],$posts_type_arr) ){
            return '';
        }

        extract($atts);
    
        $html = '';
        $title_html = '';
        $load_more_html = '';
        $uid = uniqid();
        $render_func = $type.'_template';
        $total_posts = wp_count_posts($type)->publish;
        $containe_title = false;

    
    
        if( isset($number) ){
            $posts_per_page = $number;
        }else{
            $posts_per_page = get_option( 'posts_per_page' );        
        }
    
        if(isset($meta)){
            $meta_items =  explode(",",$meta);
            $arr_n =  count( $meta_items );
    
            if( $arr_n > 0 ){
                $args['meta_query'] =	[
                    [
                        'key'=> trim($meta_items[0]),
                        'value' => trim($meta_items[1]),
                        'compare' => 'IN'
                    ]
                ];
            }
        }
    
        // Get the posts
        $posts_arr = get_posts( [
            'posts_per_page'   => $posts_per_page ,
            'post_type'        => $type,   
            'paged'            => 1,
        ]);
    
    
        if( isset($title) || isset($detail) ){
            $containe_title = true;
        }
    
        // Prepair the title and the detail html if user provide
        if($containe_title===true){
            $title_html .= '<div class="post-title-section">';
                $title_html .= !empty($title) ? "<h2>$title</h2>" : '' ;
                $title_html .= !empty($detail) ? "<p>$detail</p>": '';
            $title_html .= "</div>";
        }
    
        
        
        /**
         * Prepair the loadmore button html
         * 
         */
        // Check If current shown posts is smaller than total post and no loadmore define
        if( $total_posts > count($posts_arr)  && !isset($noloadmore) ){
            $attr= [];
            $loadmore_att_str = '';
            $admin_url = admin_url('admin-ajax.php');
    
            // Prepair The ajax Request Ncessery attribute
            $attr['data-posttype'] = $type;
            $attr['data-posts_per_page'] = $posts_per_page;
            $attr['data-pagenumber'] = 1;
            $attr['data-ajax-url'] = $admin_url;
            $attr['data-container'] = $uid;
            $attr['data-functionname'] = $render_func;
            $attr['data-page'] = 1;
            $attr['data-nonce'] = wp_create_nonce("loadmore_ajax_request");
    
            $attr['class'] = "btn btn-primary btn-lg load-more-posts-btn";
    
            // Make the attribute string
            foreach($attr as $att=> $att_val){
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
        
        // Prepair wrapper class
        $wrapper_class = 'render-posts-main-wrapper '.$type.'-posts-wrapper '.$background  ;
    
        $html .= "<div class='".$wrapper_class."' >";
            $html .= "<div class='posts-wrapper ".$type."-wrapper'>";
    
                $html .= $title_html;
    
                $html .= "<div class='items items-container render-posts-items $uid post-type-".$type."'>";
    
                    foreach( $posts_arr as $sp_data ):
                        $html .= "<div class='item render-posts-item'>";                              
                            // check if there have any render_$post_type or note, if not we will use our default function 
                            if(function_exists($render_func)){
                                $html .= $render_func($sp_data->ID);
                            }else{
                                $html .= $this->default_template($sp_data->ID);
                            }
                        $html .= "</div>";
                    endforeach;
                    
                $html .= "</div>";
    
                $html .= $load_more_html;
    
            $html .= "</div>";
    
        $html .= '</div>';
    
        return $html;
    
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