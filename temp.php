<?php 

add_action( 'media_buttons', 'add_my_media_button', 99 );
function add_my_media_button() {

    $post = $GLOBALS['post_ID'];
    echo "<a href='#' id='insert-my-media' data-post-id='{$post}' class='button'>Own content</a>";

}

// Removed Line
// add_action( 'wp_ajax_my_action', 'updateContent' );
// Code Added
add_action( 'wp_ajax_nopriv_updateContent', 'updateContent' );
add_action( 'wp_ajax_updateContent', 'updateContent' );
// Finish adding code
function updateContent() {

    $post_id = intval( $_POST['post_id'] );

    wp_die(); // this is required to terminate immediately and return a proper response
    $post = array(
        'ID'           => $post_id,
        'post_content' => 'Insert this content',
    );

    // Update the post into the database
    wp_update_post( $post );
}

add_action( 'admin_footer', 'my_media_button_script' );
function my_media_button_script() {

    ?>
    <script>
        jQuery(document).ready(function ($) {
            $('#insert-my-media').click(function () {
                var post_id = $(this).attr('data-post-id');
                var data = {
                    'action': 'updateContent',
                    'post_id': post_id
                };
                console.log("test: " + ajaxurl)
                console.log(data)
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function (response) {
                    alert('Got this from the server: ' + response);
                });
            });
        });
    </script>

    <?php
}