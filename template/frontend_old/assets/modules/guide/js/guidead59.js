$(document).ready(function(){
    $('.tphdkbsma').each(function(){
        var slhdkbsma = $(this).find('.slhdkbsma');
        var slhdkbsmb = $(this).find('.slhdkbsmb');
        slhdkbsma.slick({
            autoplay: false,
            autoplaySpeed: 5000,
            speed: 1000,
            arrows: true,
            dots: false,
            slidesToShow : 3,
            swipeToSlide: true,
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow : 2,
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow : 1,
                    }
                }
            ]
        });
        slhdkbsmb.slick({
            autoplay: false,
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
    });
    $('.slhdkbsmb').each(function(){
        var that = $(this);
        $(window).on("load resize scroll",function(){
            var inheght = that.find('.thumb').innerHeight();
            var intop = inheght / 2 - 22 ;
            that.find(".slick-arrow").css({
                "top": intop,
                "transform" : "initial"
            })
        });
    })
    // 
    $('.slortherpg').slick({
        autoplay: true,
        autoplaySpeed: 4000,
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
    if ($('.vnttabpc .menuTab').length) {
        $(".vnttabpc .menuTab").mnfixed({
            limit: '.tphdkbsmm',
            zindex: 110,
            top: 45
        });
    }
    $('.vnttabpc .menuTab a[href^="#"]').on('click', function(event) {
        var target = $( $(this).attr('href') );
        if( target.length ) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: target.offset().top - 110
        }, 1000);
        }
    });
    $(window).scroll(function () {
        var scrollDistance = $(window).scrollTop();
        $(".mmtabcont").each(function (i) {
            if ($(this).offset().top - 120 <= scrollDistance) {
                $(".vnttabpc .menuTab ul li.current").removeClass("current");
                $(".vnttabpc .menuTab ul li").eq(i).addClass("current");
            }
        });
    }).scroll();
})