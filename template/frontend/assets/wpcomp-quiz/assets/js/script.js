(function($) {
    'use strict';

    // Khởi tạo Slick slider từ data-slick attribute
    function initSlickFromData() {
        $('.slickjs[data-slick]').each(function() {
            const $carousel = $(this);
            
            // Kiểm tra nếu đã được khởi tạo
            if ($carousel.hasClass('slick-initialized')) {
                return;
            }
            
            try {
                // Lấy JSON từ data-slick attribute
                const slickData = $carousel.attr('data-slick');
                const settings = JSON.parse(slickData);
                
                // Đảm bảo có prevArrow và nextArrow nếu arrows = true
                if (settings.arrows !== false) {
                    settings.prevArrow = settings.prevArrow || '<button class="slick-prev"><svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none; stroke-width: 1px; stroke: #707070;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg></button>';
                    settings.nextArrow = settings.nextArrow || '<button class="slick-next"><svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none; stroke-width: 1px; stroke: #707070;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg></button>';
                }
                
                // Xử lý equal height nếu có class slickjs--equalheight
                if ($carousel.hasClass('slickjs--equalheight')) {
                    $carousel.on('init', function() {
                        setTimeout(function() {
                            equalizeSlickHeights($carousel);
                        }, 100);
                    });
                    
                    $carousel.on('setPosition', function() {
                        setTimeout(function() {
                            equalizeSlickHeights($carousel);
                        }, 50);
                    });
                }
                
                // Khởi tạo Slick
                $carousel.slick(settings);
                
            } catch (e) {
                console.error('Error initializing Slick slider:', e);
            }
        });
    }
    
    // Hàm equalize heights cho slides
    function equalizeSlickHeights($carousel) {
        if (!$carousel.hasClass('slick-initialized')) {
            return;
        }
        
        let maxHeight = 0;
        $carousel.find('.slick-slide:not(.slick-cloned)').each(function() {
            const $slide = $(this);
            $slide.css('height', 'auto');
            const slideHeight = $slide.outerHeight();
            if (slideHeight > maxHeight) {
                maxHeight = slideHeight;
            }
        });
        
        if (maxHeight > 0) {
            $carousel.find('.slick-slide:not(.slick-cloned)').css('height', maxHeight + 'px');
        }
    }
    
    // Khởi tạo khi DOM ready
    $(document).ready(function() {
        // Đợi một chút để đảm bảo CSS đã load
        setTimeout(function() {
            initSlickFromData();
        }, 100);
    });
    
    // Khởi tạo lại khi có content mới được load (AJAX)
    $(document).on('DOMNodeInserted', function(e) {
        if ($(e.target).find('.slickjs[data-slick]').length > 0 || $(e.target).hasClass('slickjs')) {
            setTimeout(initSlickFromData, 200);
        }
    });
    
    // Khởi tạo lại khi window load xong (đảm bảo tất cả resources đã load)
    $(window).on('load', function() {
        setTimeout(initSlickFromData, 100);
    });
    
})(jQuery);

