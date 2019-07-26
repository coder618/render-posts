Render Posts

Rneder Your Posts easily.
This plugin will help you to show/render you posts/custom posts(CPT) very easily.
This plugin generate a shortcode, which you can use to render any type posts in your custom html formate with all you PHP condition available.
This Plugin have a function to load your post by Ajax.
This plugin also support with Gutenberg. 

---- How to install ----
This plugin do not have any settings page, so after install you just have to active the plugin, thats it.
Then you can you the [render-post] shortcode very easily.


---- How to use ----
Shortcode : [render-posts]
Available Arguments : 
1. *type = "You Post type " 
2. number = "Posts Per Page" -- if not specify it will inherit from wordpress global posts_per_page option serttings
3. title = "Section title"
4. detail = "Section Detail"
5. noloadmore = "true" -- Set it if you dont want to show loadmore button

*required field.

----- How to add Custom Post template (Technical documentation) -----

To make a custom post template you have to crate a php function with a specific name. And Your function has to RETURN(NOT ECHO) the whole markup as a string.
After create the function you have to attatched/link your created function to function.php or via other plugin so that your created function can be access from any place from wordpress backend.

Function Name: $postType_template()
eg: post_template() , event_template(), member_template() etc

example function:
// Default post template 
function rp_default_post_template($data){
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


Your defined  template function will receive a single post id  as its first argument. you can use the id to manupulate/make the posts mark template.

Note : You have to return the markup, you cant echo the markup from the function.