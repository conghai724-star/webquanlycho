$(document).ready(function(){
    /*$('.datepicker').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        yearRange: "1900:+200",
    });*/
    // 
    $(".menuTab .mc-menu").click(function(){
        if(!$(this).parents(".menuTab").hasClass("active")){
            $(this).parents(".menuTab").addClass("active");
            $(this).parent().find("ul").stop().slideDown();
        }
        else{
            $(this).parents(".menuTab").removeClass("active");
            $(this).parent().find("ul").stop().slideUp();
        }
    });
    // 
    $("#scroll_comment").click(function() {			
		$('html, body').animate({				
		scrollTop: $("#comments").offset().top-65	}, 1000);		
    });
    // 
    $("#contentComment").focus(function(){
        $(this).parents(".w_content").find(".content-info").stop().slideDown(700);
    });
    // 
    $("#btn-close").click(function(){
        $(this).parents(".w_content").find(".content-info").stop().slideUp(700);
    });
    // 
    $(".vhbothead ").mnfixed({
        break: 992,
        zindex: 112,
        top: 0,
    });
    // 
    $('.slblockalm').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        dots: true,
        fade: true,
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 1000, 
    });
    // 
    $('.cclinkview a[href^="#"]').on('click', function(event) {
        var target = $( $(this).attr('href') );
        if( target.length ) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 80
            }, 1000);
        }
    });
    $(".cclinkview .vname").click(function(){
        if(!$(this).parents(".cclinkview").hasClass("active")){
            $(this).parents(".cclinkview").addClass("active");
            $(this).parents(".cclinkview").find(".vcont").stop().slideDown();
        }
        else{
            $(this).parents(".cclinkview").removeClass("active");
            $(this).parents(".cclinkview").find(".vcont").stop().slideUp();
        }
    });
    $('.clshowsub').on('click', function(){
        $('.hpmenufix').addClass('active')
    })
    $('.hpmenufix').each(function(){
        var that = $(this);
        that.find('.clmenu a').on('click', function(){
            $(this).parents('.hpmenufix').removeClass('active')
        })
        that.find('.ctmenu li a').on('click', function(){
            $(this).parents('.hpmenufix').removeClass('active')

        })
    })
    $('.hpmenufix .ctmenu a[href^="#"]').on('click', function(event) {
        var target = $( $(this).attr('href') );
        if( target.length ) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 80
            }, 1000);
        }
    });
    $(".cclinktabs .lttabsc .vvicon").mnfixed({
        limit: '.cclinktabs .ltconts',
        zindex: 111,
        top: 100,
    });
    //
    $(window).scroll(function () {
        var scrollDistance = $(window).scrollTop();
        $(".vviewtab").each(function (i) {
            if ($(this).offset().top - 80 <= scrollDistance) {

                $(".hpmenufix .ctmenu li.current").removeClass("current");
                $(".hpmenufix .ctmenu li").eq(i).addClass("current");
            }
        });
    }).scroll()
    // 
    // $(".chosen-select").chosen({disable_search_threshold: 100});
    // 
});
