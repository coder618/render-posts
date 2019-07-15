<?php 

// Default post template 
function default_post_template($data){
    $c_id = $data->ID; 
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
