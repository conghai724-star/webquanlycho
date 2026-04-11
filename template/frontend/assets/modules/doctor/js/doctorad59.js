$(document).ready(function(){
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
})