<?php
/* Short code for all post type
[
  render-posts
  type="posttype"
  number=6
  loadmore=true
]
*/
function rp_render_posts( $atts ){

    foreach( $atts as $k=>$v ):
        $atts[$k] =  trim(strip_tags($v)) ;
    endforeach;
    extract($atts);

    $html = '';
    $title_html = '';
    $load_more_html = '';
    $uid = uniqid();
    $render_func = $type.'_template';
    $total_posts = wp_count_posts($type)->publish;
    $containe_title = false;

    // check if function post template avaiable otherwise we will use default one
    if(!function_exists($render_func)){
        $render_func = 'rp_default_post_template';
    }


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

    // Prepair the title html if user provide
    if($containe_title===true){
        $title_html .= '<div class="page-title-section">';
            $title_html .= !empty($title) ? "<h2>$title</h2>" : '' ;
            $title_html .= !empty($title) ? "<p>$detail</p>": '';
        $title_html .= "</div>";
    }

    
    
    // Check If current shown post is gratter than total post and no loadmore define
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

    $wraper_class = 'posts-wraper '.$type.'-posts-wraper '.$background  ;

    $html .= "<div class='".$wraper_class."' >";
        $html .= "<div class='container'>";

            $html .= $title_html;

            $html .= "<div class='items items-container render-posts-items $uid post-type-".$type."'>";

                foreach( $posts_arr as $sp_data ):
                    $html .= "<div class='item render-posts-item'>";     
                        $html .= $render_func($sp_data);	
                    $html .= "</div>";
                endforeach;
                
            $html .= "</div>";

            $html .= $load_more_html;

        $html .= "</div>";

    $html .= '</div>';

    return $html;

}
add_shortcode( 'render-posts', 'rp_render_posts' );