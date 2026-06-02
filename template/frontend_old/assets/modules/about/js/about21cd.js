$(document).ready(function(){
    $('.slaboutbb').slick({
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 1000,
        arrows: true,
        dots: false,
        slidesToShow : 3,
        swipeToSlide: true,
        responsive: [
            {
                breakpoint: 767,
                settings: { 
                    slidesToShow : 2,
                }
            }
        ]
    }); 
    // 
    $('.slabouttt').slick({
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 1000,
        arrows: true,
        dots: false,
        slidesToShow : 4,
        swipeToSlide: true,
        responsive: [
            {
                breakpoint: 991,
                settings: { 
                    slidesToShow : 3,
                }
            },
            {
                breakpoint: 767,
                settings: { 
                    slidesToShow : 2,
                }
            }
        ]
    }); 
    // 
    $('.slaboutnn').slick({
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 1000,
        arrows: true,
        dots: false,
        slidesToShow : 3,
        swipeToSlide: true,
        responsive: [
            {
                breakpoint: 767,
                settings: { 
                    slidesToShow : 2,
                }
            }
        ]
    });
    // 
    $('.tpcontinfo').each(function(){
        var inner = $(this).innerHeight();
        var hefexd = $('.tptabmenus').innerHeight();
        if(inner > hefexd){
            $(".tptabmenus").mnfixed({
                break: 992,
                limit: '.tpcontinfo',
                zindex: 12,
                top: 60,
            });
        }
    })
    $('.tptabmenus a[href^="#"]').on('click', function(event) {
        var target = $( $(this).attr('href') );
        if( target.length ) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: target.offset().top - 65
        }, 1000);
        }
    });
    $(window).scroll(function () {
        var scrollDistance = $(window).scrollTop();
        $(".tpaboutpg").each(function (i) {
            if ($(this).offset().top - 70 <= scrollDistance) {
                $(".tptabmenus ul li.current").removeClass("current");
                $(".tptabmenus ul li").eq(i).addClass("current");
            }
        });
    }).scroll();
})