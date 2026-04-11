// js
console.log('cwp init');

window.addEventListener("scroll", function () {
	var scrollPosition = window.scrollY;
	var tocContainer = document.querySelector(".toc-container");
	var postContent = document.getElementById("pstDetail");
	if (postContent){

	var postContentHeight = postContent.offsetHeight;
	var postContentOffsetTop = postContent.offsetTop;

	var showTocThreshold = postContentOffsetTop + 200;
	var windowWidth = window.innerWidth;

	// console.log(windowWidth);
  //   if(windowWidth >= 1920){
  //       tocContainer.style.left = "9.5%";
  //       tocContainer.style.top = "13%";
  //   }else if(windowWidth <= 1806){
	// 		tocContainer.style.left = "8%";
	// 		tocContainer.style.top = "13%";
	// 	}else if(windowWidth <= 1680){
	// 		tocContainer.style.left = "5.5%";
	// 		tocContainer.style.top = "13%";
	// 	}else{
	// 		tocContainer.style.left = "9%";
	// 		tocContainer.style.top = "13%";
  //   }
    
  //   if (scrollPosition > 600 && scrollPosition < postContentHeight  && windowWidth >= 1645) {
	// 		$(".toc-container").show(200);
  //   } else {
	// 		$(".toc-container").hide(200);
  //   }

		if (scrollPosition > postContentOffsetTop && scrollPosition < showTocThreshold + postContentHeight) {
				$(".toc-container").show(200);
		} else {
				$(".toc-container").hide(200);
		}
	}
});

jQuery(document).ready(function ($) {
	$('.toc-fixed ul li a').on('click', function (e) {
			e.preventDefault();
			$('.toc-fixed ul li').removeClass('active');
			$(this).parent('li').addClass('active');
			var targetId = $(this).attr('href');
			$('html, body').animate({
					scrollTop: $(targetId).offset().top
			}, 500);
	});

    $(".toc-top").on('click', function (e) {
        e.preventDefault();
        $(".toc-toggle").toggleClass("active");
        $(".toc-container-content .toc-fixed ul").toggle(200);
    });
});
