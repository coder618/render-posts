jQuery(document).ready(function ($) {
    $("button.load-more-posts-btn").on("click", function (e) {
        e.preventDefault();
        var $this = $(this);

        $this.attr("disabled", true); // Disable The BTN first to prevent 2x clicck
        $this.addClass("loading-state");
        var $old_btn_text = $this.text(); // Grabe the text it have, to use later
        $this.text("Loading");

        var $page = parseInt($this.attr("data-page"));
        var $post_type = $(this).data("posttype");
        var $posts_per_page = parseInt($(this).data("posts_per_page"));
        var ajaxurl = $this.data("ajax-url");
        var posts_container = $this.data("container"); // Container to append the result
        var nonce = $this.data("nonce");
        var cat_name = $this.data("cat-name");
        var total_posts = parseInt($this.data("total_posts"));
        var total_page = Math.ceil(total_posts / $posts_per_page);
        $.ajax({
            url: ajaxurl,
            type: "post",
            cache: false,
            data: {
                page: $page,
                post_type: $post_type,
                posts_per_page: $posts_per_page,
                nonce: nonce,
                cat_name: cat_name,
                action: "render_posts_ajax_loadmore",
            },
            error: function (response) {
                // console.log("error ", response)
                console.log("error > ", response);
            },
            success: function (response) {
                var current_page = parseInt($page) + 1;
                console.log(total_page, current_page);
                if (total_page == current_page) {
                    $this.remove();
                }

                if (response && response.length > 6) {
                    // append the html to the container
                    $("." + posts_container).append(response);

                    // Change the buttons attributes for next request
                    $this.attr("data-page", current_page);
                    $this.text($old_btn_text);
                    $this.attr("disabled", false);
                } else {
                    $this.remove();
                }
            },
        }); // ajax call end
    });
});
