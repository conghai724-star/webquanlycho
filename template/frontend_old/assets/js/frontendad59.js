var processing = 1;

$(document).ready(function($) {
    /*$('.format_number').numeric({ negative: false });
    $('.number_vnd').number( true, 0);
    $(".desc img").removeAttr('style');*/
    $(".desc table").wrap("<div class='table-responsive'></div>");
    
	  // popup address footer
    $(".linkMap a").fancybox({
        baseClass : 'designMap',
        width  : '100%',
        height : '100%',
        padding: 0,
        margin: 0,
        type:'ajax',
    });

    $(".popupLGRG").click(function(e) {
      var url_popup = $(this).attr('data-link');
      if (url_popup) {
        $.fancybox.open({
          src: url_popup,
          baseClass: 'popupSty',
          type:'iframe',
          toolbar : false,
          toolsbar: false,
          smallBtn : true,
          // padding     : 0,
          // maxWidth    : 630,
          // maxHeight   : 710,
          // width       : '98%',
          fitToView   : false,
          autoSize    : true,
          autoHeight  : true,
          autoWidth   : true,
          closeClick  : false,
          clickSlide : 'false',
          clickOutside : 'false',
          touch: false,
          openEffect  : 'elastic',
          closeEffect : 'elastic',
          helpers : {
            overlay : {closeClick: false}
          }
        });
      }else{
        vnTScript.alert_notifi(js_lang['empty_link_mem'], 'error');
      }
    });

    // ĐẶT LỊCH HẸN
    $(".popupBookAll").fancybox({
      type:'ajax',
      baseClass:'popupSty',
      toolsbar:false,
      smallBtn:true,
      // padding     : 0,
      // maxWidth    : 630,
      // maxHeight   : 710,
      // width       : '98%',
      fitToView   : false,
      autoSize    : true,
      autoHeight  : true,
      autoWidth   : true,
      closeClick  : false,
      clickSlide : 'false',
      clickOutside : 'false',
      touch: false,
      openEffect  : 'elastic',
      closeEffect : 'elastic',
      helpers : {
        overlay : {closeClick: false}
      }
    });

    // Đi đến vị trí. Select all links with hashes
    $('.scroll a[href*="#"], .desc a[href*="#"]')
      // Remove links that don't actually link to anything
      .not('[href="#"]')
      .not('[href="#0"]')
      .click(function(event) {
        // On-page links
        if (
          location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
          && 
          location.hostname == this.hostname
        ) {
          // Figure out element to scroll to
          var target = $(this.hash);
          target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
          // Does a scroll target exist?
          if (target.length) {
            // Only prevent default if animation is actually gonna happen
            event.preventDefault();
            $('html, body').animate({
              scrollTop: target.offset().top - 100
            }, 1000, function() {
              // Callback after animation
              // Must change focus!
              var $target = $(target);
              $target.focus();
              if ($target.is(":focus")) { // Checking if the target was focused
                return false;
              } else {
                $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
                $target.focus(); // Set focus again
              };
            });
          }
        }
    });

    // Gọi điện
    /*$(".grphonehead").click(function(e) {
      if ($(".subaddphone").hasClass('active')) {
        $(".subaddphone").removeClass('active');
      }else{
        $(".subaddphone").addClass('active')
      }
    });*/

    // QTY
    /*$(document).on('keyup', '.nb-qty-ip', function(){
      var id = $(this).attr('data-id');
      var type = $(this).attr('data-type');

      vnTScript.ipQuantity(id, type);
    });

    $(document).on('click', '.nb-qty', function(){
      var id = $(this).attr('data-id');
      var type = $(this).attr('data-type');

      vnTScript.ipQuantity(id, type);
    });*/

    // Add Cart
    $("html").on("click", ".addFBCart", function(e){
      e.preventDefault();
      vnTScript.close_alert();
      var _this = $(this);
      var pid = parseInt(_this.attr('data-id'));
      var ctid = parseInt(_this.attr('data-ctid'));
      var size = parseInt(_this.attr('data-sz'));
      var type = _this.attr('data-type');

      var quantity = 0;
      if (type == 'size') {
        quantity = parseInt($("#quantity_sz_"+ctid).val());
      }else{
        quantity = parseInt($("#quantity_"+pid).val());
      }

      if (processing == 1) {
        processing = 0;

        var mydata = 'id='+pid+'&ct_id='+ctid+'&size='+size+'&type='+type+'&quantity='+quantity;
        _this.addClass('disabled').append("&nbsp;<i class='fa fa-spinner fa-spin'></i>");
        setTimeout(function(){
          // _this.addClass('fa-spin');
          // _this.addClass('disabled').append("<i class='fa fa-spinner fa-spin'></i>");

          $.ajax({
              async: true,
              dataType: 'json',
              url:  ROOT_PROD + "/ajax/add_cart.html",
              type: 'POST',
              data: mydata ,
          })
          .done(function(data) {
              if(data.ok == 1){
                /*vnTScript.alert_notifi(js_lang['fly_cart_success'], 'success');*/

                /*setTimeout(function(){
                  vnTScript.close_alert();
                  $(".cartTop.click-popup .trigger").trigger('click');
                }, 150);*/

                vnTScript.popup_succ_cart(data.product);
              }else{
                vnTScript.alert_notifi(data.mess, 'error');
              }
              vnTScript.do_showCart();
          })
          .fail(function() {})
          .always(function() {
            processing = 1;
            // _this.removeClass('fa-spin');
            _this.removeClass('disabled').children('i').remove();
          });
        }, 1000);
      }else{
        vnTScript.alert_notifi(err_mess_processing, 'error');
      }
    });

    $(".searchTop input").focus(function(){
      $(this).parents(".searchTop").addClass("active");
    });
    $(".searchTop input").blur(function(){
      $(this).parents(".searchTop").removeClass("active");
    });

    var stopLoadTime;
    var stopSearchAjax;
    var loadOverlay = 0;
    $(".box_search input1").keyup(function(e) {
      var keyword = $(this).val();
      var mydata = "keyword="+keyword+"&lang="+lang+"&url="+url;

      if (loadOverlay == 0) {
        $(".ajax_search").html('<div class="suggess"><div class="suggessWrap"><div class="fkoutput"><div class="outscroll"><div class="oprow"><div class="itfindkey"><ul><li></li></ul></div></div></div></div></div></div>');
      }
      
      // if (keyword) {
        var _search = 0;
        clearTimeout(stopLoadTime);
        clearTimeout(stopSearchAjax);

        if (loadOverlay == 0) {
          $(".ajax_search .itfindkey").addClass('loadOverlay loadOverlayCenter');
        }
        loadOverlay = 1;

        // Xử lý gõ xong mới gọi ajax
        stopLoadTime = setTimeout(function(){
          _search = 1;
        }, 1000);

        stopSearchAjax = setTimeout(function(){
          if (_search == 1) {
            $.ajax({
              url: ROOT_PROD + '/ajax/product_search.html',
              type: 'POST',
              dataType: 'json',
              data: mydata,
              beforeSend: function(){},
            })
            .done(function(data) {
              $(".ajax_search").show();
              $(".ajax_search").html(data.html);
            })
            .always(function() {
              $(".ajax_search .itfindkey").removeClass('loadOverlay loadOverlayCenter');
              loadOverlay = 0;
            });
          }
        }, 1100);
      // }
    });

    /*vnTScript.do_showCart();*/

    // Hiden password
    $(".show-hide-password").click(function(e) {
      var _this = $(this);
      var iptype = _this.parents('.div_input').find('.ip-pass').attr('type');

      if (iptype == 'password') {
        _this.children('i').attr('class', 'fa fa-eye-slash');
        _this.parents('.div_input').find('.ip-pass').attr('type', 'text');
      }else{
        _this.children('i').attr('class', 'fa fa-eye');
        _this.parents('.div_input').find('.ip-pass').attr('type', 'password');
      }
    });

    // MEMBER
    $(".bnClkLR").click(function(e) {
      var type = $(this).attr('data-type');
      if (type == 'login') {
        $(".showpopLogin").trigger('click');
      }else {
        $(".showpopLogin").trigger('click');
        $(".showpopRegis").trigger('click');
      }
    });

    // Hide/Show Header
    var offsetTop = 0;
    $(window).scroll(function (e) {
        var offsetW = $(window).scrollTop();
        var offsetE =  $('.vntheader').innerHeight();
        if(offsetE < offsetW) {
            $("#mmFixScroll").addClass("active");
        } else {
            $("#vnt-header").removeClass("active");
            $("#mmFixScroll").removeClass("active");
        }
        if (offsetTop < offsetW) {
            $("#mmFixScroll").removeClass("effectActive");
       
        } else {
            $("#mmFixScroll").addClass("effectActive");
        }
        offsetTop = offsetW ;
    });
    $(window).on("scroll",function(){
        var inner =  $('.vntheader').innerHeight();
        if($(window).scrollTop() > inner){
            $("#mmFixScroll:not(.notfix)").addClass("fixed");
        }
        if($(window).scrollTop() <= inner){
            $("#mmFixScroll").removeClass("fixed");
            $("#mmFixScroll").addClass("effectActive");
        }
    });

    // View more
    setTimeout(function(){
      $(".w_content_show_hidden").each(function () {
        var h_content = $(this).find(".over_hidden").outerHeight();
        if (h_content <= 145) {
            $(this).find(".button_show_hidden").hide();
            $(this).find(".content_show_hidden").removeClass("click_showhide");
        } else {
            $(this).find(".button_show_hidden").show();
            $(this).find(".content_show_hidden").addClass("click_showhide");
        }
        var get_height = $(this).find(".content_show_hidden").outerHeight();
        $(this)
          .find(".button_show_hidden a")
          .click(function (e) {
              if (!$(this).hasClass("active_hidden")) {
                  $(this).addClass("active_hidden");
                  $(this).find("span").text($(this).find("span").data("hidden"));
                  $(this).parents(".w_content_show_hidden").find(".content_show_hidden").addClass("active_show").css("max-height", "inherit");
              } else {
                  vnTScript.scrolltop($(this).attr("data-id"), -150);

                  $(this).removeClass("active_hidden");
                  $(this).find("span").text($(this).find("span").data("show"));
                  $(this).parents(".w_content_show_hidden").find(".content_show_hidden").removeClass("active_show").css("max-height", get_height);
                  //$(this).parents(".productContact").find(".content_show_hidden").removeClass("active_show").css('max-height','145');
                  // jQuery('html,body').animate({scrollTop: ($(this).parents(".w_content_show_hidden").offset().top - 70)},50);
              }
          });
      });
    }, 2000);
});

vnTScript = {
    close_alert : function(){
        $('iao-alert > iao-alert-close').trigger('click');
    },

    alert_notifi: function(mess, type = 'success', pos = 'top-center', time = '5000'){
      vnTScript.close_alert();
      $.iaoAlert({
          msg: mess,
          position: pos,
          type: type,
          mode: "dark",
          alertTime: time
      });
    },

    load_script: function(){
      /*$(".btn-closecart").click(function(){
        if ($(this).parents(".cartTop").hasClass('active')) {
            $(this).parents('.cartTop').removeClass("active");
            $(this).parents("body").removeClass("noscroll");      
        }
        if ($(this).parents(".cart-popup").hasClass('active')) {
            $(this).parents('.cart-popup').removeClass("active");
            $(this).parents("body").removeClass("noscroll");    
        }
      });*/
      $(".btnClosePop a").click(function(e) {
        $(".vnt-bg-over").removeClass("active");
        $("#popupCartFix").removeClass("active");
      });
    },

    do_showCart: function(){
      $.ajax({
          async: false,
          dataType: 'json',
          url:  ROOT_PROD + "/ajax/show_cart.html",
          type: 'POST',
          success: function (data) {
            $('#popupCartFix').html(data.box_cart.pc);
            // $('#box-cart').html(data.box_cart.pc);
            // $("#num_cart").html(data.box_cart.num_cart);
            $(".nCart").html(data.box_cart.num_cart);
            $(".lnkcart").attr('href', data.box_cart.link_cart);
            vnTScript.load_script();
          }
      });
    },

    del_ajaxCart: function(id){
      var mydata = 'id='+id;

      $("#popupCartFix").addClass('loadOverlay loadOverlayCenter');
      setTimeout(function(){
          $.ajax({
            async: false,
            dataType: 'json',
            url:  ROOT_PROD + "/ajax/del_cart.html",
            type: 'POST',
            data: mydata,
              beforeSend: function() {},
          })
          .done(function(data) {
            vnTScript.do_showCart();
            $(".cartTop .trigger").trigger('click');
          })
          .fail(function() {

          })
          .always(function() {
              $("#popupCartFix").removeClass('loadOverlay loadOverlayCenter');
          });
      }, 500);
    },

    scrolltop: function(scoll_id = "myId", min = 100){
      if($('#' + scoll_id).length > 0)
        $('html, body').animate({scrollTop:($('#' + scoll_id).position().top - min)}, 'slow');
    },

    format_number: function(price){
      var v = Number(price);
      if (isNaN(v)) { return price; }
      var sign = (v < 0) ? '-' : '';
      var res = Math.abs(v).toString().split('').reverse().join('').replace(/(\d{3}(?!$))/g, '$1,').split('').reverse().join('');
      return sign + res;
    },

    ipQuantity: function (id, type){
      vnTScript.close_alert();

      if (processing == 1) {
        processing = 0;

        var cur_qty = parseInt($("#quantity_"+id).val());

        if(type == "between") {
            cur_qty = parseInt($("#quantity_"+id).val());
            if(cur_qty < 1){
                $("#quantity_"+id).val(1);
            }
        }else if(type == "down") {
            if(cur_qty > 1){
                $("#quantity_"+id).val(cur_qty - 1);
            }
        }else{
            $("#quantity_"+id).val(cur_qty + 1);
        }

        $("#product"+id).addClass('loadOverlay loadOverlay-45 minHauto');
        var qty_new = parseInt($("#quantity_"+id).val());
        var mydata = 'id='+id+'&quantity='+qty_new;

        $.ajax({
            async: true,
            dataType: 'json',
            url:  ROOT_PROD + "/ajax/price_qty.html",
            type: 'POST',
            data: mydata,
        })
        .done(function(data) {
            if (data.ok) {
              var price_dt = (data.arr_price.txt_price_tt) ? data.arr_price.txt_price_tt : '';
              var price_old_dt = (data.arr_price.txt_price_old_tt) ? data.arr_price.txt_price_old_tt : '';
              var ribbon_dt = (data.arr_price.ribbon) ? data.arr_price.ribbon : '';

              $("#product"+id+" .discount").remove();
              $("#product"+id+" .imgWrap").append(ribbon_dt);
              $("#product"+id+" .m-price").html(price_dt);
              $("#product"+id+" .f-price").html(price_old_dt);
            }
        })
        .fail(function() {})
        .always(function() {
            $("#product"+id).removeClass('loadOverlay loadOverlay-45 minHauto');
            processing = 1;
        });
      }else{
        vnTScript.alert_notifi(err_mess_processing, 'error');
      }
    },

    popup_succ_cart: function(product){
      if (product.name) {
        /*$("#hppopformCart .itemCart").find('.code').children('span').html(product.maso);*/
        $("#hppopformCart .itemCart").find('.pname').children('a').html(product.name);
        $("#hppopformCart .itemCart").find('a').attr('href', product.link);
        $("#hppopformCart .itemCart").find('img').attr({
          'src': product.src,
          'alt': product.name
        });

        $('.vhtcarttc').addClass('active');
        
        setTimeout(function(){
          $(".vhtcarttc .clcart a").trigger('click');
        }, 3000);
      }
    },

    remove_option: function(elv){
      $(elv+' option').each(function() {
        if ($(this).val() != '') {
            $(this).remove();
        }
      });
    }
}

//click ra ngoai box
$(document).bind('click', function (e) {
    var clicked = jQuery(e.target);
    /*if (!clicked.parents().hasClass("hotlineTop")) {
      $('.hotlineTop').removeClass('active');
    }
    if (!clicked.parents().hasClass("membertop")) {
      $(".hotlineTop").css('z-index', 20);
    }*/
    if (!clicked.parents().hasClass("tpsearchhead")) {
      $('.scfindkey').removeClass('active');
    }
});