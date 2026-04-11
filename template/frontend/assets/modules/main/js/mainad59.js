$(document).ready(function(){
    $('.slscreeha').slick({
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 500,
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
    $('.slscreehb').slick({
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 500,
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
    $('.slcenterhh').slick({
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
    $('.slcenterhh').each(function(){
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
    $('.sltetimohh').slick({
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 1000,
        arrows: false,
        dots: true,
        fade: true,
        slidesToShow : 1,
        responsive: [
            {
                breakpoint: 767,
                settings: { 
                    dots: false,
                }
            }
        ]
    }); 
});