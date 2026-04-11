$(document).ready(function(){
    $('.slscreeha').slick({
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 1000,
        arrows: true,
        dots: false,
        slidesToShow : 3,
        responsive: [
            {
                breakpoint: 991,
                settings: {
                    autoplay: false,
                    slidesToShow : 20000,
                    arrows: false,
                    touchMove: false,
                    infinite: false,
                    variableWidth: false,
                }
            }
        ]
    }); 
    $('.slscreehb').slick({
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 500,
        arrows: true,
        dots: false,
        slidesToShow : 4,
        slidesToScroll: 10,
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
    $('.slscreehb').each(function(){
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
    $('.slpacketha').slick({
        autoplay: true,
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
                    autoplay: false,
                    slidesToShow : 20000,
                    arrows: false,
                    touchMove: false,
                    infinite: false,
                    variableWidth: false,
                }
            }
        ]
    }); 
    $('.slpackethb').slick({
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 1000,
        arrows: true,
        dots: false,
        slidesToShow : 4,
        slidesToScroll: 10,
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
    $('.slpackethb').each(function(){
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
    if ($('.vnttabpc .menuTab').length) {
        $(".vnttabpc .menuTab").mnfixed({
            limit: '.tpservicmm',
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
    if ($('.vnttabmb .menuTab').length) {
        $(".vnttabmb .menuTab").mnfixed({
            limit: '.tpservicmm',
            zindex: 110,
            top: 60
        });
    }
    $('.vnttabmb .menuTab a[href^="#"]').on('click', function(event) {
        var target = $( $(this).attr('href') );
        if( target.length ) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: target.offset().top - 120
        }, 1000);
        }
    });
    $(window).scroll(function () {
        var scrollDistance = $(window).scrollTop();
        $(".mmtabcont").each(function (i) {
            if ($(this).offset().top - 130 <= scrollDistance) {
                $(".vnttabmb .menuTab ul li.current").removeClass("current");
                $(".vnttabmb .menuTab ul li").eq(i).addClass("current");
            }
        });
    }).scroll();
    // 
    $('.slortherpg').slick({
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
})