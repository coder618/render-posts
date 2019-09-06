=== Render Posts ===
Contributors: coder618
Donate link: https://coder618.github.io
Tags: Show Post, Render Post
Requires at least: 4.6
Tested up to: 5.0
Stable tag: 1.0
Requires PHP: 7.0
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
This plugin will help developer to show/render posts/custom posts(CPT) very easily.
This plugin generate a shortcode, which you can use to render any kind of posts in your custom html formate where all you PHP condition available.
This Plugin also have a function to load your post's by Ajax.
This plugin also support with Gutenberg. 

== Installation ==
1. Upload the plugin folder to the `/wp-content/plugins` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.

This plugin do not have any settings page, so after install you just have to active the plugin, thats it.
Then you can use [render-post] shortcode to render/show your posts.

== Frequently Asked Questions ==

= Is this plugin have any settings page =
No, This plugin currently do not have any settings page. you just have to actived the plugin to use.

= Can i Render/Show Custom Post type by this plugin short code =
Yes, You can, you have to provide type argument with your post type. eg [render-posts type="custom-post-type-name"]

= How to render/show posts with my custom markup =
To show post with you markup you have to define a function, which name will be a specific formate. eg. event_template(), member_template() etc. When you post type is event, member. Please see the Technical Documentation for more info.


== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0.0 =
* First release.

== Upgrade Notice ==

= 1.0.0 =
First relase.

== How to use == 

Shortcode : [render-posts]
Available Arguments : 
1. *type = "You Post type " 
1. number = "Posts Per Page" -- if not specify it will inherit from wordpress global posts_per_page option serttings.
1. title = "Section title"
1. detail = "Section Detail"
1. noloadmore = "true" -- Set it if you dont want to show loadmore button

*required field.

eg. [render-posts type="post"]

== Technical documentation ==
How to add Custom Post template.

To make a custom post template you have to crate a php function with a specific name. 
And Your function have to RETURN(NOT echo) the whole markup as a string. This function will have one argument post id.

Function Name: postType_template($post_id) ,
eg: post_template($post_id) , event_template($post_id), member_template($post_id) for post, event and member Post Type.

Example function:
// Template For Post post type.
`function post_template($post_id){
    $title = get_the_title($post_id);
    $post_img_id = get_post_thumbnail_id($post_id);
    $post_img_url = return_post_img_url( $post_id , 'large' );
    $title = get_the_title($post_id);
    $html = '';

    $html .= '<a href="'.get_permalink($post_id).'" class="default-post-template">';
        $html .= '<img src="'.$post_img_url.'" alt="'.$title.'">';
        
        $html .= '<div class="text-section">';
            $html .= '<h3 class="title">'.$title.'</h3>';            
        $html .= '</div>';
    $html .= '</a>';

    return $html;
}`

Your defined  template function will receive a single post id  as its first argument. you can use the id to manupulate/make the posts markup for post/cpt template.
After create the function, you have to Attached/link your created function to function.php or via other plugin so that your created function can be access from any place from the wordpress.


Note : You have to return the markup, you cant echo the markup from the function. It can  cause error.
