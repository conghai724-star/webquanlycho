$(document).ready(function(){
    $('.slortherpg').slick({
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
    $('.tpdetailmm .mmdecss').each(function(){
        var that = $(this);
        
        // Edit
        that.parents(".vcontsdt").append('<div class="dsview"><div class="vhviewtt"><div class="showview"><a class="" href="javascript:;" rel="nofollow"><span>'+view_more+'</span></a></div><div class="hideview"><a class="" href="javascript:;" rel="nofollow"><span>'+view_less+'</span></a></div></div></div>');

        var innerhpc = that.innerHeight();
        if(innerhpc > 200){
            that.css({
                'height' : 200,
                'overflow' : 'hidden'
            })
            that.addClass('vchange');
            that.siblings('.dsview').find('.showview').show();
            that.siblings('.dsview').find('.showview a').on('click', function(){
                that.css({
                    'height' : 'auto',
                })
                that.removeClass('vchange');
                that.siblings('.dsview').find('.showview').hide();
                that.siblings('.dsview').find('.hideview').show();
            })
            that.siblings('.dsview').find('.hideview a').on('click', function(){
                that.css({
                    'height' : 200,
                })
                that.addClass('vchange');
                that.siblings('.dsview').find('.showview').show();
                that.siblings('.dsview').find('.hideview').hide();
            })
        }
    }) 
    // 
    $('.sldetailhh').slick({
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
    // 
    $('.sldetailbb').slick({
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
    $('.sldetailtt').slick({
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
})