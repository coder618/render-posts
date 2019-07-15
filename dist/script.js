jQuery(document).ready(function( $ ){
  
    console.log("ajax post loader loaded")
  
    $("button.load-more-posts-btn").on("click", function(e){
      e.preventDefault()
      var $this = $(this);
      console.log("post loader clicked")
  
      $this.attr("disabled", true); // Disable The BTN first
      $this.addClass("loading-state")
      var $old_btn_text = $this.text(); // Grabe the text it have to use later
      $this.text("Loading");
  
      var $page = $this.attr('data-page');
      var $post_type = $(this).data('posttype');
      var $posts_per_page = $(this).data('posts_per_page');
      var ajaxurl = $this.data('ajax-url');
      var function_name = $this.data('functionname');
      var posts_container = $this.data("container") // Container to append the result
      var nonce = $this.data("nonce")
      $.ajax({
        url: ajaxurl,
        type: 'post',
        cache: false,
        data: {
          page: $page,
          post_type: $post_type,
          function_name : function_name,
          posts_per_page: $posts_per_page,
          nonce: nonce,
          action: 'load_more_posts'
        },
        error: function (response) {
          // console.log("error ", response)
          console.log("error > ", response)
        },
        success: function (response) {
          console.log(response)
          if(response){
  
            // append the html to the container
            $("."+posts_container).append(response)
  
            // Change the buttons attributes for next request
            $this.attr("data-page", parseInt($page) + 1 )
            $this.text($old_btn_text)
            $this.attr("disabled", false);
  
          }else{
            $this.remove()
  
          }
  
  
          
  
  
        }
  
      }); // ajax call end
    });
  
})