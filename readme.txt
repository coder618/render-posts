=== Render Posts ===
Contributors: coder618
Donate link: https://coder618.github.io
Tags: Show Post, Render Post
Requires at least: 4.6
Tested up to: 5.4
Stable tag: 2.0
Requires PHP: 7.0
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
This plugin will help the developer / user to show/render posts very easily regardless of the post type.
This plugin register a short-code, which developer/user can use to render any kind of posts, in a custom defined format/template.
This plugin also contains a (AJAX) load more feature, which help users to load remaining posts without leaving the current page, (Also known as Ajax Pagination).
This plugin also support with Gutenberg and other builder (Because it's just a shortcode) .

== Installation ==
- Upload the plugin folder to the `/wp-content/plugins` directory, or install the plugin through the WordPress plugins screen directly.
- Activate the plugin through the 'Plugins' screen in WordPress Dashboard.

This plugin do not have any settings page, so after install you just have to active the plugin, thats it.
Then you can use [render-post] shortcode.

== Frequently Asked Questions ==

= Is this plugin have any settings page =
No, This plugin currently do not have any settings page. you just have to active the plugin to use.

= Can I Render/Show Custom Post type by this plugin short-code =
Yes, You can, you have to provide type argument with your post type. eg [render-posts type="custom-post-type-slug"]


== How to use == 
eg. shortcode:  [render-posts type="post"]

= Available Arguments =
- *type = "You Post type " 
- number = "Posts Per Page" -- if not specify it will inherit from wordpress global posts_per_page option serttings.
- title = "Section title"
- detail = "Section Detail"
- noloadmore = "true" -- Set it if you dont want to show loadmore button
*required field.

== Technical documentation ==
How to add Custom Markup for a specific post type?
Simply follow this steps:
1. Create a folder at the root of your activated theme folder which name will be: loop-templates.
2. Create the template file in the newly created folder, name : content-{$post_type_slug}.php . then you are good to go. 
You can use all standard wordpress function, raw php, html there.

= Template Naming =
- content-post.php  -> when post type is post
- content-event.php -> when post type is event
- content-member.php  -> when post type is member

Note: Plugin will add sufficient amount of DIV with dynamic CLASS's (related with the post type name) at rendering,
which will help you to create any kind of layout very easily by using css.


== Screenshots ==
1. How To Use the shortcode.


== Changelog ==

= 1.0.0 =
* First release.

== Upgrade Notice ==
= 2.0.0 =
Changelog:
1. Remove Function base template system.
2. Add file base template system.
3. Add category filtering option, to the shortcode (eg. cat="some-category-slug").

= 1.0.0 =
First release.


Developer:
Please Visit: [Developer Profile](https://coder618.github.io)