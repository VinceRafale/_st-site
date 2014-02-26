$(document).ready(function (){

	resizeFullHeight();
	resizePrevNext();

	$(".main-text p").fitVids()
});
$(function() {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});

/* GALLERY
 * ------------------------------------------------ */

 $(document).ready(function () {

	$(".project-images li").fitVids();



});
var cnp_gallery_ready = true;
$(document).ready(function () {

	num_slides = $(".project-images .slides li").length;

	if(num_slides > 0) {

		if(num_slides == 1) {
			$(".project-images").addClass("single-image");
		}
		else if(num_slides == 2) {
			$(".project-images .slides").find("li:nth-child(1)").clone().appendTo($(".project-images .slides"));
			$(".project-images .slides").find("li:nth-child(2)").clone().appendTo($(".project-images .slides"));

			cnp_gallery_prep();
			cnp_gallery_position_images();

			$(".project-images").on("click", ".next" , function () {
				cnp_gallery_next();
			}).on("mouseenter", ".next", function () {
				cnp_gallery_move_next_in()
			}).on("mouseleave", ".next", function () {
				cnp_gallery_move_next_out()
			})
			$(".project-images").on("click", ".prev" , function () {
					cnp_gallery_prev();
			}).on("mouseenter", ".prev", function () {
				cnp_gallery_move_prev_in()
			}).on("mouseleave", ".prev", function () {
				cnp_gallery_move_prev_out()
			})
			$(window).resize(function () {
				cnp_gallery_position_images();
			})

		}
		else {
			cnp_gallery_prep();
			cnp_gallery_position_images();

			$(".project-images").on("click", ".next" , function () {
				cnp_gallery_next();
			}).on("mouseenter", ".next", function () {
				cnp_gallery_move_next_in()
			}).on("mouseleave", ".next", function () {
				cnp_gallery_move_next_out()
			})
			$(".project-images").on("click", ".prev" , function () {
					cnp_gallery_prev();
			}).on("mouseenter", ".prev", function () {
				cnp_gallery_move_prev_in()
			}).on("mouseleave", ".prev", function () {
				cnp_gallery_move_prev_out()
			}).swipe({
		        //Generic swipe handler for all directions
		        swipe:function(event, direction, distance, duration, fingerCount) {
		          if(direction == "left") {
			          cnp_gallery_next();
		          }
		          if(direction == "right") {
			          cnp_gallery_prev();
		          }
		        },
		        //Default is 75px, set to 0 for demo so any distance triggers swipe
		         threshold:0
		      })
			$(window).resize(function () {
				cnp_gallery_position_images();
			})
		}

	}





});




function cnp_gallery_position_images() {
		var window_width = $(window).width();
		var $container = $(".project-images").find(".slides");
		var main_image_width = $container.find("li:first").width();

		//Set Margins
		$container.find("li").css("padding", "0 "+ (((window_width - main_image_width) / 4) - 25) +"px");

		//Resize .slides
		var total_slides = $container.find("li").length;
		var images_width = $container.find("li").outerWidth();
		$container.width(total_slides * images_width);

		//Position
		var start_image_position = $container.find("li:nth-child(2)").position();
		var image_offset = ((window_width - $container.find("li").outerWidth()) / 2) - start_image_position.left;
		$container.css("left", image_offset + "px");
}
function cnp_gallery_prep() {
	var $container = $(".project-images").find(".slides");

	//Move last image
	$container.find("li:last").insertBefore($container.find("li:first"));
	$(".project-images").append('<div class="next gallery-nav" />');
	$(".project-images").append('<div class="prev gallery-nav" />')
}
function cnp_gallery_next() {
	if(cnp_gallery_ready) {
		cnp_gallery_ready = false;
		var window_width = $(window).width();
		var $container = $(".project-images").find(".slides");
		$container.find("li:nth-child(3)  > *").animate({"marginLeft":"0px"}, 100)
		$container.find("li:first").clone().appendTo($container);

		//Position
		var start_image_position = $container.find("li:nth-child(3)").position();
		var image_offset = ((window_width - $container.find("li").outerWidth()) / 2) - start_image_position.left;

		$container.animate({"left":image_offset + "px"}, function () {
			$container.find("li:first").remove();
			//Position
			var start_image_position = $container.find("li:nth-child(2)").position();
			var image_offset = ((window_width - $container.find("li").outerWidth()) / 2) - start_image_position.left;
			$container.css("left", image_offset + "px");
			cnp_gallery_ready = true;
		});
	}

}

function cnp_gallery_prev() {
	if(cnp_gallery_ready) {
		cnp_gallery_ready = false;

		var window_width = $(window).width();
		var $container = $(".project-images").find(".slides");
		var main_image_width = $container.find("li:first").width();
		$container.find("li:first  > *").animate({"marginLeft":"0px"}, 100)
		$container.find("li:last").clone().prependTo($container);
		//Position
		var start_image_position = $container.find("li:nth-child(3)").position();
		var image_offset = ((window_width - $container.find("li").outerWidth()) / 2) - start_image_position.left;
		$container.css("left", image_offset + "px");

		var start_image_position = $container.find("li:nth-child(2)").position();
		var image_offset = ((window_width - $container.find("li").outerWidth()) / 2) - start_image_position.left;

		$container.animate({"left":image_offset + "px"}, function () {
			$container.find("li:last").remove();
			//Position

			var start_image_position = $container.find("li:nth-child(2)").position();
			var image_offset = ((window_width - $container.find("li").outerWidth()) / 2) - start_image_position.left;
			$container.css("left", image_offset + "px");

			cnp_gallery_ready = true;
		});

	}

}

function cnp_gallery_move_prev_in() {
	var $container = $(".project-images").find(".slides");
	$container.find("li:first > *").animate({"marginLeft":"30px"}, 100)
}
function cnp_gallery_move_prev_out() {
	var $container = $(".project-images").find(".slides");
	$container.find("li:first  > *").animate({"marginLeft":"0px"}, 100)
}

function cnp_gallery_move_next_in() {
	var $container = $(".project-images").find(".slides");
	$container.find("li:nth-child(3)  > *").animate({"marginLeft":"-30px"}, 100)
}
function cnp_gallery_move_next_out() {
	var $container = $(".project-images").find(".slides");
	$container.find("li:nth-child(3)  > *").animate({"marginLeft":"0px"}, 100)
}


$(window).load(function() {

});

$(window).resize(function() {
	resizeFullHeight();
	resizePrevNext()
});

//RESIZE .full-height
function resizeFullHeight() {
	$(".full-height").height($(window).height());
}
function resizePrevNext() {
	var navWidth = ($(window).width() - $(".project-images > div:first").width()) / 2;
	$("#prev, #next").width(navWidth);
}