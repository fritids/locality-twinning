/**
 * Init
 */
window.wheels = new Object();
window.wheels.ease = 'easeInOutQuad';
window.wheels.duration = 750;
window.wheels.easeAlt = 'easeInOutQuad';
window.wheels.durationAlt = 300;

jQuery(function ($) {
    $.Body = $('body');
    $.Window = $(window);
    $.isMobile = ($.Body.hasClass('webkit-mobile') || (navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)));
    $.Scroll = ($.browser.mozilla || $.browser.msie) ? $('html') : $.Body;

    if( getCookie('isMobile') == 'no' ){
        $.isMobile = false;
    }

    // this aliases the jquery slider to uislider
    // to avoid collisions with jquery mobile
    jQuery.fn.uislider = jQuery.fn.slider;

    if ($.isMobile) {
        $.Body.addClass('mobile ' + ' w' + screen.width + ' h' + screen.height);
        var deviceAgent = navigator.userAgent.toLowerCase();
        if (deviceAgent.match(/(iphone)/)) $.Body.addClass('iphone');
        if (deviceAgent.match(/(ipod)/)) $.Body.addClass('ipod');
        if (deviceAgent.match(/(ipad)/)) $.Body.addClass('ipad');

        $(document).bind("mobileinit", function(){
            $.extend($.mobile, {
                ajaxEnabled: false, //we are not currently using the ajax capabilities
                ajaxLinksEnabled: false,
                ajaxFormsEnabled: false,
                metaViewportContent: false, //we set this ourselves above
                autoInitialize: false //let's call this ourselves in script.js at our own discretion
            });

        });
        $(document.body).append('<script src="/wp-content/themes/wheels/js/libs/jquery.mobile-1.0.min.js"><\\/script>');

    }
    $('[data-controller]').Instantiate();
});

/**
 * Auto init
 */
(function($) {
    $.fn.Instantiate = function(settings) {
        var config = {};
        if (settings) $.extend(config, settings);

        this.each(function() {
            var $self = $(this),
                $controller = $self.attr('data-controller');
            if ($self[$controller]) {
                $self[$controller]();
            }
        });
    };
})(jQuery);

/**
 * Events
 */
(function($) {
    $.Events = {
        SCROLL: 'scroll',
        RESIZE: 'resize',
        OPEN: 'open',
        CLOSE: 'close'
    };
})(jQuery);

/**
 * Controllers
 */
(function($) {

    /**
     * Scrollable
     *
     * This can be attached to things that need to be notified of scroll events
     */
    $.fn.Scrollable = function(settings) {
        var config = {};
        if (settings) $.extend(config, settings);

        this.each(function(){
            var $self = $(this);
            $.Window.bind('scroll', section_Scroll);
            function section_Scroll() {
                $self.triggerHandler($.Events.SCROLL);
            }
        });
        return this; // to allow for chaining
    };

    /**
     * Main Navigation Controller
     */
    $.fn.MainNavigationController = function() {
        this.each(function(){
            var $self = $(this),
                $items = $('.navbar li', this),
                threshhold = $('.navbar', $self).offset().top,
                menuHeights = ($('.navbar', $self).outerHeight(true) + $('.toolbar', $self).outerHeight(true)) + $('.subnav', $self).outerHeight(true);

            if ($.isMobile && $.Body.hasClass("mobile-page")) {
                return;
            }

            // add our fixed buffer shim for the table switching to position fixed
            $('<div class="header-fixed-buffer"></div>').insertBefore($self);
            $('.header-fixed-buffer').css({height:menuHeights}).hide();

            $('#bg-navprimary-menu-2 .experts ul li').matchItemHeights();

            $self
                .Scrollable()
                .bind($.Events.SCROLL, nav_scroll);

            // trigger scroll
            $self.trigger($.Events.SCROLL);

            $items
                .bind('mouseenter', items_Over)
                .bind('mouseleave', items_Out);

            $('.main-menu-window')
                .bind('mouseenter', menu_Over)
                .bind('mouseleave', menu_Out);

            /**
             * Nav scroll
             */
            function nav_scroll() {
                if ($.Scroll.scrollTop() > threshhold) {
                    $self.addClass('fixed');
                    $items.removeClass('hover');
                    $('.main-menu-window').hide().css({top:$.Scroll.scrollTop() + 48});
                    $('.header-fixed-buffer').show();
                }
                else {
                    $self.removeClass('fixed');
                    $('.main-menu-window').css({top:$('#mainnavbar').offset().top + 48});
                    $('.header-fixed-buffer').hide();
                }
                $('.ui-menu').hide();
            }

            function menu_Over(e) {
                // trigger scroll
                $self.trigger($.Events.SCROLL);
                $(this).show();
                var dataAttr = $('#' + $(this).attr('data-menuitem'));
                if (dataAttr != '') {
                    $(dataAttr).addClass('hover');
                }
            }

            function menu_Out(e) {
                $(this).hide();
                var dataAttr = $('#' + $(this).attr('data-menuitem'));
                if (dataAttr != '') {
                    $(dataAttr).removeClass('hover');
                }
            }

            function items_Over(e) {
                e.preventDefault();
                // trigger scroll
                $self.trigger($.Events.SCROLL);
                $(this).addClass('hover');
                $('.main-menu-window').hide();
                if ($(this).attr('data-window') != '') {
                    $('#'+$(this).attr('data-window')).show();
                }
            }

            function items_Out(e) {
                e.preventDefault();
                $('.main-menu-window').hide();
                $(this).removeClass('hover');
            }


        });
    };

    /**
     * Homepage Sidebar Controller
     */
    $.fn.HomepageSidebarController = function() {
        this.each(function(){
            var $self = $(this);

            // slider create
            $(".slider", $self).bind( "slidecreate", function(e, ui) {
                $('.min-value', $(this).parent()).appendTo($('.ui-slider-handle:eq(0)', this));
                //$('.max-value', $(this).parent()).appendTo($('.ui-slider-handle:eq(1)', this));
            });

            $("#year-home-slider", $self).bind( "slide", function(e, ui) {
                updatePosition($('.min-value', this), (ui.value) + '+');
            });
            $("#year-home-slider", $self).uislider({
                range:'max',
                value:2004,
                min: 1999,
          max: 2013,
                step:1});
            updatePosition($("#year-home-slider .min-value", $self), $("#year-home-slider", $self).uislider("values", 0) + '+');

            function updatePosition($el, val){
                if (val) {
                    $el.text(val);
                }
            }
        });
    };

    /**
     * Main Header Controller
     */
    $.fn.MainHeaderController = function() {
        this.each(function(){
            var $self = $(this);


            if ($.isMobile && Modernizr.inputtypes.range) {
                $(".slider", $self).empty().append('<input data-role="none" id="price-slider-control" type="range" min="1000" max="100000" value="20000" step="1000" name="slider" />');

                $('#price-slider-control').change(function(e) {
                    updatePosition($(".price-container .min-value", $self), '< $' + (e.target.value/1000) + 'K');
                });

            }
            else {
                // slider create
                $(".slider", $self).bind( "slidecreate", function(e, ui) {
                    $('.min-value', $(this).parent()).appendTo($('.ui-slider-handle:eq(0)', this));
                    $('.max-value', $(this).parent()).appendTo($('.ui-slider-handle:eq(1)', this));
                });

                $("#price-slider", $self).bind( "slide", function(e, ui) {
                    updatePosition($('.min-value', this), '< $' + (ui.value/1000) + 'K');
                });
                $("#price-slider", $self).uislider({
                    range:'min',
                    value:20000,
                    min: 1000,
            max: 400000,
                    step:1000});
                updatePosition($("#price-slider .min-value", $self), '< $' + ($("#price-slider", $self).uislider("values", 0)/1000) + 'K');
            }

            function updatePosition($el, val){
                if (val) {
                    $el.text(val);
                }
            }

        });
    };

    /**
     * Review Navigation Controller
     */
    $.fn.ReviewNavController = function() {
        this.each(function(){
            var $self = $(this),
                $items = $('li', $self),
                menuHeights = ($('#mainnavbar').outerHeight(true) + $('#toolnavbar').outerHeight(true)) - $('#subnavbar').outerHeight(true),
                threshhold = $self.offset().top - 147,
                $rows = $('.section'),
                navClicked = false;

            if ($.isMobile) {

                // inject the navigation
                if ($self.find('.navigation').length == 0 && $.Body.hasClass("mobile-page")) {
                    $self.prepend('<a class="nav left ui-link">Left</a><a class="nav right ui-link">Right</a>');
                }

                // measure the container
                var reviewMenuWidth = 0,
                    index = 1,
                    visibleLength = $("#review-nav li").length - 1;

                $("#review-nav li").each(function(){
                    reviewMenuWidth += $(this).outerWidth(true);
                });

                $("#review-nav").wrap("<div class='viewport' />");
                $("#review-nav").width(reviewMenuWidth);

                // lets make this scrollable for IOS5
                $(".viewport").addClass('scrollable scroll-y');

                $self
                    .bind('swipeleft', function(e) {
                        e.preventDefault();
                        moveRight();
                    })
                    .bind('swiperight', function(e) {
                        e.preventDefault();
                        moveLeft();
                    });
                $self.find('.left')
                    .bind('click', function(e){
                        e.preventDefault();
                        moveLeft();
                    });

                $self.find('.right')
                    .bind('click', function(e){
                        e.preventDefault();
                        moveRight();
                    });

                function moveLeft() {
                    if (index > 1) {
                        index--;
                    }
                    moveSlide(index);
                }

                function moveRight() {
                    if (index < visibleLength) {
                        index++;
                    }
                    moveSlide(index);
                }

                function moveSlide(index) {
                    var $containerPos = $("#review-nav li:eq(" + (index-1) + ")").position().left * -1;
                    $("#review-nav").stop().animate({left:$containerPos}, {duration:window.wheels.duration, easing:window.wheels.ease});
                }

                return;
            }

            // add our fixed buffer shim for the table switching to position fixed
            $('<div class="fixed-buffer"></div>').insertBefore($self);
            $('.fixed-buffer').css({height:$self.outerHeight(true)}).hide();

            $self
                .Scrollable()
                .bind($.Events.SCROLL, nav_scroll);

            // trigger scroll
            $self.trigger($.Events.SCROLL);

            /**
             * Nav scroll
             */
            function nav_scroll() {
                if ($.Scroll.scrollTop() > threshhold) {
                    $self.addClass('fixed');
                    $('.fixed-buffer').show();
                }
                else {
                    $self.removeClass('fixed');
                    $('.fixed-buffer').hide();
                }

                $rows.each(function(i) {
                    if ($.inview($(this), {threshold:-250})) {
                        $rows.removeClass('inview');
                        $(this).addClass('inview');
                        if (!navClicked) {
                            var index = $rows.index($(this));
                            $items.removeClass('active');
                            $($items[index]).addClass('active');
                        }
                    }
                });
            }

            $items.bind('click', item_Click);
            function item_Click(e) {
                e.preventDefault();
                navClicked = true;
                $items.removeClass('active');

                var h = $('#mainnavbar').outerHeight(true) + $('#toolnavbar').outerHeight(true) + $('#review-nav').outerHeight();

                $.Scroll.animate({scrollTop: $($rows[$items.index($(this))]).offset().top - (h)}, {
                    duration:window.wheels.duration,
                    easing:window.wheels.ease,
                    complete:function() {
                        setTimeout(function(){
                            $rows.each(function(i) {
                                if (Math.floor($(this).offset().top - h) == $.Scroll.scrollTop()) {
                                    var index = $rows.index($(this));
                                    $items.removeClass('active');
                                    $($items[index]).addClass('active');
                                }
                            });
                            navClicked = false;
                            // check if we're at the bottom'
                            if ($.Window.scrollTop() + $.Window.height() == $(document).height()) {
                                $items.removeClass('active');
                                $($items[$items.length-1]).addClass('active');
                            }
                        }, 150);
                    }
                });
            }
        });

    };

    /**
     * Polls
     */
    $.fn.PollController = function() {
        this.each(function(){
            var $self = $(this);

            $self.bind('click', poll_Click);
            function poll_Click(e) {
                e.preventDefault();
                $('[data-controller="PollController"]').removeClass('on');
                $(this).addClass('on');
            }
        });
    };

    /**
     * ModalTriggerController
     */
    $.fn.ModalTriggerController = function() {
        this.each(function(){
            var $self = $(this);

            // click handler to trigger modal windows
            $self.bind('click', modalTrigger_Click);
            function modalTrigger_Click(e) {
                e.preventDefault();
                $modal = $($(this).attr('data-modal'));
                if ($modal.length > 0) {
                    $modal.trigger($.Events.OPEN);
                }
            }
        });
    };

    /**
     * Modals
     */
    $.fn.ModalController = function() {
        this.each(function(){
            var $self = $(this),
                $modals = $self.find('.modal');

            $('a.close', $modals).bind('click', closeModal_Click);
            function closeModal_Click(e) {
                e.preventDefault();
                $modal = $(this).parents(".modal");
                $modal.hide();
            }

            $modals.bind($.Events.OPEN, modal_Open);
            function modal_Open(e) {
                e.preventDefault();
                $modal = $(this);
                if ( $modal && $modal.is(":hidden") ) {
                    $modals.hide();
                    $modal.show();

                    $('.mask', $modal).css({
                        height: $(document).height(),
                        width:$.Window.width()
                    });

                    $('.content', $modal).css("top", (($(window).height() - $('.content', $modal).outerHeight()) / 2));
                    $('.content', $modal).css("left", (($(window).width() - $('.content', $modal).outerWidth()) / 2));
                    $('.content', $modal).css("z-index", 10001);
                }
            }

            // DEBUG
            // $modals.filter(':first').trigger('open');
        });
    };

    /**
     * Poll controller
     */
    $.fn.PollController = function() {
        this.each(function(){
            var $self = $(this),
                $form = $('form', $self),
                $view = $('.poll-results', $self),
                $viewResults = $('#view-poll-results', $self),
                $viewPoll = $('#view-poll-form', $self);

            $form.bind('submit', poll_Submit);
            $viewResults.bind('click', viewResults_Click);
            $viewPoll.bind('click', viewPoll_Click);

            function viewResults_Click(e) {
                e.preventDefault();
                viewResults();
            }

            function viewPoll_Click(e) {
                e.preventDefault();
                viewPoll();
            }

            function poll_Submit(e) {
                e.preventDefault();
                // do some ajax
                // show results
                viewResults();
            }

            function viewPoll() {
                $form.show();
                $view.hide();
            }

            function viewResults() {
                $form.hide();
                $view.show();
            }

        });
    };

    /**
     * Reader Opinion
     */
    $.fn.ReaderOpinionController = function() {
        this.each(function(){
            var $self = $(this),
                $vehicles = $('.vehicles-list li', $self),
                $opinions = $('.opinions-list li', $self),
                $nub = $('.nub', $self),
                positions = [74, 232, 404, 590, 760];

            $('.opinions').height($('.opinions-list li:eq(0)').outerHeight());

            $vehicles.bind('mouseenter', vehicle_Over);

            function vehicle_Over(e) {
                var index = $('.vehicles-list li', $self).index($(this));
                $opinions.hide();
                $('.opinions-list li:eq('+index+')').show();
                $nub.css('left', positions[index]);
                $('.opinions').height($('.opinions-list li:eq('+index+')').outerHeight() );
            }
        });
    };


    /**
     * Carousel Controller
     */
    $.fn.CarouselController = function() {
        this.each(function(){
            var $self = $(this),
                $items = $('.item', $self),
                useBullets = $self.attr('data-usebullets');

            // insert bullet navigation
            var navStr = '<ul class="carousel-bullets" data-for="' + $self.attr('id') + '">';
            $items.each(function(i){
                if (i == 0) {
                    navStr += '<li class="active">' + i + '</li>';
                }
                else {
                    navStr += '<li>' + i + '</li>';
                }
            });
            navStr += '</ul>';
            if (useBullets != 'false') {
                $self.after(navStr);
            }

            $self.carousel({
                interval: 10000
            });
            $self.carousel('pause');

            // scale images
            $('.item img', $self).imgscale({
                parent : '.feature-container'
            });

            $bullets = $('.carousel-bullets[data-for="' + $self.attr('id') + '"] li');

            $bullets.bind('click', function(e){
                e.preventDefault();
                $(this, $self).addClass('active').siblings().removeClass('active');
                $self.carousel(parseInt($(this).text()));
                setTimeout(function(){
                    $self.carousel('pause');
                }, 1500);

            });

            $self.bind('slide', function(e) {
                $items.css({'z-index':6999});
                if ($.isMobile) {
                    $('.item.next .feature-container', $self).css('display', 'block');
                }
                $('.item.next', $self).css({'z-index':7001});
                $('.item.prev', $self).css({'z-index':7001});
            });

            $self.bind('slid', function(e) {
                var index = $items.index( $('.item.active', $self) );
                $('.carousel-bullets[data-for="' + $self.attr('id') + '"] li').removeClass('active');
                $('.carousel-bullets[data-for="' + $self.attr('id') + '"] li:eq(' + index + ')').addClass('active');
            });

            $('a.carousel-control.left, a.carousel-control.right', $self).bind('click', function(e){
                setTimeout(function(){
                    $self.carousel('pause');
                }, 1500);
            });
        });
    };

    /**
     * Generic Combobox
     */
    $.fn.ComboboxController = function() {
        this.each(function(){
            var $self = $(this),
                uiClasses = $self.attr('class').split(' '),
                uiSelector = '';

            if ($.isMobile) {
                return;
            }

            $.each(uiClasses, function(index, objValue){
                uiSelector += '.' + objValue;
            });

            $self.combobox();

            $autocomplete = $('.ui-autocomplete-input'+uiSelector);

            if ($self.attr('data-readonly') == 'true') {
                $('.ui-autocomplete-input'+uiSelector)
                    .attr('readonly', 'readonly')
                    .addClass('pointer');
                $self.parent().find('.ui-button')
                    .addClass('readonly');
            }
            else {
                $('.ui-autocomplete-input'+uiSelector)
                    .bind('focus', function(){
                        $thisAutocomplete = $(this);
                        $thisAutocomplete.bind('blur', function(){

                        })
                        if ($('option:first', $self).val() == $(this).val()) {
                            $(this).val('');
                        }
                    })
                    .bind('blur', function(){
                        if ($thisAutocomplete.val() == '') {
                            $thisAutocomplete.val($('option:first', $self).val());
                        }
                    });
            }

        });
    };

    /**
     * Vehicle Finder Results
     */
    $.fn.VehicleFinderResultsController = function() {
        this.each(function(){
            var $self = $(this);

            // ajax load would go here
            $('.load-more a').live('click', loadMore_Click);
            function loadMore_Click(e) {

            }
        });
    };

    /**
     * Vehicle Finder Filters
     */
    $.fn.VehicleFinderFiltersController = function() {
        this.each(function(){
            var $self = $(this);

            // slider create
            $(".slider", $self).bind( "slidecreate", function(e, ui) {
                $('.min-value', $(this).parent()).appendTo($('.ui-slider-handle:eq(0)', this));
                $('.max-value', $(this).parent()).appendTo($('.ui-slider-handle:eq(1)', this));
            });

            // year slider
            $("#year-slider", $self).bind( "slide", function(e, ui) {
                updatePosition($('.min-value', this), ui.values[0]);
                updatePosition($('.max-value', this), ui.values[1]);
            });
            $("#year-slider", $self).uislider({
                range:true,
                values:[1999, 2012],
                min: 1911,
                max: 2012,
                step:1});
            updatePosition($("#year-slider .min-value", $self), $("#year-slider", $self).uislider("values", 0));
            updatePosition($("#year-slider .max-value", $self), $("#year-slider", $self).uislider("values", 1));

            // price slider
            $("#price-slider", $self).bind( "slide", function(e, ui) {
                updatePosition($('.min-value', this), '$' + (ui.values[0]/1000) + 'K');
                updatePosition($('.max-value', this), '$' + (ui.values[1]/1000) + 'K');
            });
            $("#price-slider", $self).uislider({
                range:true,
                values:[20000, 600000],
                min: 1000,
                max: 400000,
                step:1000});
            updatePosition($("#price-slider .min-value", $self), '$' + ($("#price-slider", $self).uislider("values", 0)/1000) + 'K');
            updatePosition($("#price-slider .max-value", $self), '$' + ($("#price-slider", $self).uislider("values", 1)/1000) + 'K');

            // km slider
            $("#km-slider", $self).bind( "slide", function(e, ui) {
                updatePosition($('.min-value', this), 'Less than ' + (ui.value/1000) + 'K');
            });
            $("#km-slider", $self).uislider({
                range:'max',
                value:300000,
                min: 0,
                max: 500000,
                step:1000});
            updatePosition($("#km-slider .min-value", $self), 'Less than ' + ($("#km-slider", $self).uislider("values", 0)/1000) + 'K');

            // efficiency slider
            $("#efficiency-slider", $self).bind( "slide", function(e, ui) {
                updatePosition($('.min-value', this), ui.value + 'L /100KM');
            });
            $("#efficiency-slider", $self).uislider({
                range:'max',
                value:4.5,
                min: 0,
                max: 50,
                step:0.5});
            updatePosition($("#efficiency-slider .min-value", $self), $("#efficiency-slider", $self).uislider("values", 0) + 'L /100KM');

            // torque slider
            $("#torque-slider", $self).bind( "slide", function(e, ui) {
                updatePosition($('.min-value', this), ui.value + ' LBS/FT');
            });
            $("#torque-slider", $self).uislider({
                range:'max',
                value:150,
                min: 0,
                max: 1000,
                step:50});
            updatePosition($("#torque-slider .min-value", $self), $("#torque-slider", $self).uislider("values", 0) + ' LBS/FT');

            // hp slider
            $("#hp-slider", $self).bind( "slide", function(e, ui) {
                updatePosition($('.min-value', this), ui.value + ' HP');
            });
            $("#hp-slider", $self).uislider({
                range:'max',
                value:150,
                min: 0,
                max: 1500,
                step:10});
            updatePosition($("#hp-slider .min-value", $self), $("#hp-slider", $self).uislider("values", 0) + ' HP');

            function updatePosition($el, val){
                if (val) {
                    $el.text(val);
                }
            }

        });
    };


    /**
     * Compare Vehicles
     */
    $.fn.CompareController = function() {
        this.each(function(){
            var $self = $(this),
                threshhold = $self.offset().top - 0,
                threshhold2 = $self.offset().top - 130,
                $imageRow = $('.compare-table', $self).first(),
                $closeBtns = $('a.close', $self),
                $sponsored = $('a.sponsored', $self);

            // add our fixed buffer shim for the table switching to position fixed
            $('.table-holder', $self).prepend('<div class="fixed-buffer"></div>');
            $('.fixed-buffer', $self).css({height:$imageRow.outerHeight()}).hide();

            $self
                .Scrollable()
                .bind($.Events.SCROLL, nav_scroll);

            // close button
            $closeBtns.bind('click', close_Click);
            $sponsored.bind('click', sponsored_Click);

            // remove widows in titles and drop downs
            $('h3 a').removeWidows();
            $('.compare-trim option', $self).removeWidows();

            // trigger scroll
            $self.trigger($.Events.SCROLL);

            // suggestions
            if ($self.hasClass('compare2') || $self.hasClass('compare1')) {
                $('#suggestion-list li', $self).matchItemHeights();
            }

            /**
             * Nav scroll
             */
            function nav_scroll() {
                if ($.Scroll.scrollTop() > threshhold) {
                    $imageRow.addClass('fixed');
                    $('.fixed-buffer', $self).show();
                }
                else {
                    $imageRow.removeClass('fixed');
                    $('.fixed-buffer', $self).hide();
                }
                $('.ui-autocomplete-input', $self).autocomplete("close");
            }

            /**
             * Close click handler
             */
            function close_Click(e){
                e.preventDefault();
                var car_id = $(this).attr('data-id'),
                    currentCol = $('.compare-table tr.image-row td').length;
                if (currentCol < 2) {
                    return;
                }
                $self.removeClass('compare'+currentCol--);
                $('.compare-table td[data-column-id="'+car_id+'"]', $self).remove();
                $self.addClass('compare'+currentCol);
            }

            /**
             * Sponsored click handler
             */
            function sponsored_Click(e){
                e.preventDefault();
            }
        });
    };

    /**
     * Search Results Controller
     */
    $.fn.SearchResultsController = function(){
        this.each(function(){
            var $self =  $(this),
                $tabLinks = $('a.view-all', $self);

            // view all links to open tabs
            $tabLinks.bind('click', tabLink_Click);
            function tabLink_Click(e) {
                e.preventDefault();
                $('#search-tabs', $self).trigger($.Events.OPEN, $(this).attr('data-tab'));
            }

            $( 'a.load', $self ).click(function(e){
                e.preventDefault();

                //Placeholder CODE
                //TODO: Replace me with real AJAX functionality
                var copy = $('div.result-set', $self).filter(':first').clone();
                copy.hide();
                $( '> div.results', $self ).append( copy );
                copy.fadeIn();
                //END Placeholder CODE
            });


        });
    };

    /**
     * Tabs Controller
     */
    $.fn.TabsController = function(){
        this.each(function(){
            var $self =  $(this),
                $navigation = $('> .tab-nav', $self), // only first one so we can have tabs within tabs
                $tabs = $('> .tabs', $self), // only first one so we can have tabs within tabs
                $currentTab = $('a.active', $navigation);

            //first hide all tabs
            $tabs.find('> .tab').hide();

            //allow any given tab to be the default open one by starting it off as .active
            if ($currentTab.length > 0) {
                //make sure we had no duplicate actives
                $navigation.find('a').removeClass('active');
                $navigation.find('a').parent().removeClass('on');
                var tab = $currentTab.filter(':first');
                tab.addClass('active');
                tab.parent().addClass('on');
                var index = $("a", $navigation).index( tab ) + 1;
                $('> .tab',$tabs).hide();//direct children only, (tabs within tabs)
                //show the correct one
                $tabs.find('> .tab:nth-child(' + index +')').filter(':first').show();
            }
            else {
                //then activate the first
                $tabs.find('.tab:first').show();
                $navigation.find('a:first').addClass('active');
                $navigation.find('a:first').parent().addClass('on');
            }

            // listen for open events
            $self.bind($.Events.OPEN, tab_Open);
            function tab_Open(e, index) {
                e.preventDefault();
                e.stopPropagation(); // we don't want this to propogate

                //first hide all tabs
                $('> .tab', $tabs).hide(); //direct children only, (tabs within tabs)

                $navigation.find('a').removeClass('active');
                $navigation.find('a').parent().removeClass('on');
                //show the correct one
                var tab = $tabs.find('> .tab:nth-child(' + index +')').filter(':first');

                tab.show();
                $($navigation.find('a')[index-1]).addClass('active');
                $($navigation.find('a')[index-1]).parent().addClass('on');

                if (tab.attr('id') == 'settings') {
                    $('#settings .topics li').matchItemHeights();
                }
            }

            //listen for tab clicks
            $navigation.find('ul li a').bind('click', tab_Click);
            function tab_Click(e) {
                e.preventDefault();
                $self.trigger($.Events.OPEN, $("a", $navigation).index( $(this) ) + 1);
            }
        });
    }

    /**
     * Toggle Field Controller
     */
    $.fn.ToggleFieldController = function(){
        this.each(function(){
            var $self =  $(this),
                $field = $('#' + $self.attr('data-for')),
                $row = $self.closest('li');

            $field.hide();

            function _getFieldValue() {
                if ($('span', $row).length == 0) {
                    $row.append('<span></span>');
                }
                var $span = $('span', $row);

                if ($field.attr('type') == 'text') {
                    $span.html($field.val());
                }
                else {
                    var str = '';
                    for (var i=0; i < $field.val().length; i++) {
                        str += '*';
                    }
                    $span.html(str);
                }
            }

            _getFieldValue();

            $self.bind('click', edit_Click);
            function edit_Click(e) {
                e.preventDefault();
                $field.toggle();
                $('span', $row).toggle();
                _getFieldValue();
            }

        });
    }

    /**
     * Delete Field Controller
     */
    $.fn.DeleteFieldController = function(){
        this.each(function(){
            var $self =  $(this),
                $field = $('#' + $self.attr('data-for')),
                $row = $self.closest('li');

            $self.bind('click', delete_Click);
            function delete_Click(e) {
                e.preventDefault();
                $row.remove();
            }

        });
    }

    /**
     * Expander Controller
     */
    $.fn.ExpanderController = function(settings){
        this.each(function(){
            var $self =  $(this),
                customSlicePoint = $(this).attr('data-slicepoint');
            $self.expander({
                slicePoint: ((customSlicePoint != '') ? parseInt(customSlicePoint) : 150),
                expandText: 'More',
                userCollapseText: 'Less'
            });
        });
    };

    /**
     * Closed Accordion Controller
     */
    $.fn.ClosedAccordionController = function(settings){
        this.each(function(){
            var $self =  $(this);

            $self.AccordionController({expandFirst:false});
        });
    };

    /**
     * Accordion Controller
     */
    $.fn.AccordionController = function(settings){
        var config = {
            collapseAll: false,
            expandFirst: true,
            expandAll: false
        };
        if (settings) $.extend(config, settings);
        this.each(function(){
            var $self =  $(this),
                $handles = $('.heading', $self),
                $rows = $('> li', $self);

            $rows.each(function(index){
                var $row = $(this);
                if (config.expandFirst) {
                    if (index != 0) {
                        $('.collapsible', this).css({display:'none'});
                    }
                    else {
                        $row.addClass('open');
                        $('.collapsible', this).css({display:'block'});
                    }
                }
                else if (config.expandAll) {
                    $row.addClass('open');
                }
                else {
                    $row.removeClass('open');
                    $('.collapsible', this).css({display:'none'});
                }

            });

            $handles.bind('click', handle_Click);

            function handle_Click(e) {
                e.preventDefault();
                var $thisHandle = $(this),
                    $thisRow = $thisHandle.closest('li');

                $rows.each(function(index){
                    var $row = $(this);
                    if ($row[0] == $thisRow[0]) {
                        if ($row.hasClass('open')) {
                            $('.collapsible', this).slideUp(function(){
                                $row.removeClass('open');
                            });
                        }
                        else {
                            $row.addClass('open');
                            $('.collapsible', this).slideDown('slow');
                        }
                    }
                    else {
                        if (!config.collapseAll) {
                            return;
                        }
                        if ($row.hasClass('open')) {
                            $('.collapsible', this).slideUp(function(){
                                $row.removeClass('open');
                            });
                        }
                    }
                });
            }
        });
    }

    /**
     * Gallery Controller
     */
    $.fn.GalleryController = function(){
        this.each(function(){
            var $self =  $(this),
                $content = $self.find('.galleryContent'),
                $captionContainer = $(".overlay-container", $self),
                $captionCopy = $(".copy .pos", $captionContainer);

            $captionCopy.empty();
            $content.find('a').filter(':first').addClass('active');
            $content.find('a').bind('click', content_Click);

            // scale image
            $('.img img.large', $self).imgscale({ parent : '.img' });

            showCaption($content.find('a').filter(':first').parent().find('.caption').text());

            function content_Click(e) {
                e.preventDefault();
                var imgLoc = $(this).attr('href'),
                    $largeImage = $('.img img.large', $self);

                $largeImage.load(function(e){
                    $(this).css({'margin-top':'auto'}).imgscale({ parent : '.img' });
                }).attr("src", imgLoc);

                $content.find('a').removeClass('active');
                $(this).addClass('active');

                showCaption($(this).parent().find('.caption').text());
            }

            function showCaption(theCaption) {
                $captionCopy.empty();

                if (theCaption != "") {
                    $captionCopy.text(theCaption);
                    $captionContainer.show();
                }
                else {
                    $captionContainer.hide();
                }
            }
        });
    }

    /**
     * Slides Controller
     */
    $.fn.SlidesController = function() {
        this.each(function(){

            var $self = $(this),
                $viewport = $('.viewport', $self),
                $container = $('.container', $self),
                index = 1,
                $firstSlide = $self.find('.slide').filter(':first'),
                slideWidth = $firstSlide.outerWidth(true),
                size = $('.slide', $self).size(),
                visibleItems = Math.round($viewport.actualWidth() / slideWidth),
                visibleLength = size / visibleItems,
                nChild = $self.attr('data-nthchild');

            if (nChild != "" && nChild != undefined) {
                $(".slide:nth-child(" + nChild + "n)").addClass("last");
            }

            //Do nothing for now, we don't need this to be a slider for web
            if ( $self.attr("data-mobileonly") == "true" && !$.isMobile ) {
            }
            // This is a mobile only slider
            else if($self.attr("data-mobileonly") == "true" && $.isMobile) {

                // lets make this scrollable for IOS5
                $viewport.addClass('scrollable scroll-y');
                //$viewport.css('overflow', 'hidden');

                // set the width
                $container.css('width', (slideWidth * size) + 'px');

                size = $('.slide', $self).size();
                visibleItems = 1;
                visibleLength = size / visibleItems;

                // inject the navigation
                if ($self.find('.navigation').length == 0 && $.Body.hasClass("mobile-page")) {
                    $self.prepend('<div class="navigation"><a class="nav left ui-link">Left</a><a class="nav right ui-link">Right</a></div>');
                }
            }
            // Regular slider that needs to be mobile
            else if($self.attr("data-mobileonly") == undefined && $.isMobile) {

                // lets make this scrollable for IOS5
                $viewport.addClass('scrollable scroll-y');
                //$viewport.css('overflow', 'hidden');

                // set the width
                $container.css('width', (slideWidth * size) + 'px');

                size = $('.slide', $self).size();
                visibleItems = 1;
                visibleLength = size / visibleItems;
            }
            // regular slider
            else {
                $viewport.css('overflow','hidden');
                $container.css('width', (slideWidth * size) + 'px');
            }

            $self.find('.right')
                .unbind('tap')
                .unbind('mouseenter')
                .unbind('mouseleave')
                .unbind('mouseclick');

            $self.find('.left')
                .unbind('tap')
                .unbind('mouseenter')
                .unbind('mouseleave')
                .unbind('mouseclick');

            $self
                .unbind('swipeleft')
                .unbind('swiperight');

            $self.find('.right')
                .bind('mouseenter', function(e){
                    $(this).addClass("hover");
                })
                .bind('mouseleave', function(e){
                    $(this).removeClass("hover");
                })
                .bind('click', function(e){
                    e.preventDefault();
                    moveRight();
                });

            $self.find('.left')
                .bind('mouseenter', function(e){
                    $(this).addClass("hover");
                })
                .bind('mouseleave', function(e){
                    $(this).removeClass("hover");
                })
                .bind('click', function(e){
                    e.preventDefault();
                    moveLeft();
                });

            if($.isMobile) {
                $self
                    .bind('swipeleft', function(e) {
                        e.preventDefault();
                        moveRight();
                    })
                    .bind('swiperight', function(e) {
                        e.preventDefault();
                        moveLeft();
                    });
                if ($self.attr("data-mobileonly") == "true") {
                    $self.find('.left')
                        .bind('tap', function(e){
                            e.preventDefault();
                            moveLeft();
                        });

                    $self.find('.right')
                        .bind('tap', function(e){
                            e.preventDefault();
                            moveRight();
                        });
                }

            }

            function moveLeft() {
                if (index > 1) {
                    index--;
                }
                moveSlide(index);
            }

            function moveRight() {
                if (index < visibleLength) {
                    index++;
                }
                moveSlide(index);
            }

            function moveSlide(index) {
                if (!$.isMobile) {
                    $containerPos = (((index-1) * $viewport.outerWidth(true)) * -1);
                }
                else {
                    $containerPos = (index-1) * ((slideWidth) * -1);
                }
                $container.stop().animate({left:$containerPos}, {duration:window.wheels.duration, easing:window.wheels.ease});
            }

        });
    };

    /**
     * Mobile Tab Controller
     */
    $.fn.MobileTabController = function(){
        $(this).each(function(index){
            var $self =  $(this),
                $tabs = "";

            $("option", $self).each(function() {
                $tabs += $(this).val() + ',';
            });

            $tabs = $tabs.substring(0, $tabs.length-1);

            $self.bind('change', function(e){
                e.preventDefault();
                $($tabs).hide();
                $($(this).val()).show();
            });
        });
    };

    /**
     * Answer Center Post Question Controller
     */
    $.fn.AnswerCentrePostQuestionController = function(){
        $(this).each(function(index){
            var $self =  $(this),
                $message = $('#ask-question-message');

            $message.hide();
            $self.bind('click', close_Click);
            function close_Click(e){
                e.preventDefault();
                $message
                    .show()
                    .css({opacity:1});
            }
        });
    };

    /**
     * This controls the ask a question dialog on the guides page
     * The form submit has been disabled, due to uncertainty
     * regarding posting this form by ajax or not
     */
    $.fn.AskQuestionMessageController1 = function(){
        this.each(function(){
            var $self =  $(this),
                $form = $('form', $self);

            // set this up as a Message Controller
            $self.MessageController({});

            $form.bind('submit', form_Submit);
            function form_Submit(e){
                e.preventDefault();
                $self.trigger($.Events.CLOSE);
            }


        });
    };


    /**
     * This controls removing messages form the view
     * It listens for the $.Events.CLOSE event to fire
     * then calls the closeMessage method.
     * Inclide data-destroy=false to avoid the message
     * being destroyed
     */
    $.fn.MessageController = function(settings){
        var config = {
        };
        if (settings) $.extend(config, settings);
        $(this).each(function(index){
            var $self =  $(this),
                destroyBool = $self.attr('data-destroy') ? $self.attr('data-destroy') : true;

            $self
                .bind($.Events.CLOSE, closeMessage)
                .find('a.close')
                .bind('click', message_Close);

            function message_Close(e) {
                e.preventDefault();
                $self.trigger($.Events.CLOSE);
            };

            function closeMessage(e) {
                $self.animate({opacity:0, queue:false}, 500, 'easeOutQuad', function(){
                    $(this).slideUp(function(){
                        if (destroyBool === true) {
                            $(this).remove();
                        }
                    });
                });
            }
        });
    };

    /**
     * This removes widows from plain text by adding &nbsp between the last
     * two words
     */
    $.fn.removeWidows = function(){
        $(this).each(function(index){
            var $html = $(this).text().replace(/ (\S+)$/,'&nbsp;$1');
            $(this).html($html);
        });
    };

    /**
     * make all the elements as tall as the tallest of the elements
     * useful for floated grids with items of different height
     */
    $.fn.matchItemHeights = function() {
        var h = 0;
        $(this).each(function(index) {
            var outerHeight = $(this).actualHeight();
            if (h < outerHeight) {
                h = outerHeight;
            }
        }).height(h);
    };

    /**
     * Find the actual height of an element even if it is hidden
     */
    $.fn.actualHeight = function(){
        // find the closest visible parent and get it's hidden children
        if ($(this).is(':visible')) {
            return $(this).outerHeight();
        }

        var visibleParent = this.closest(':visible').children(),
            thisHeight;

        // set a temporary class on the hidden parent of the element
        visibleParent.addClass('temp-show');

        // get the height
        thisHeight = this.height();

        // remove the temporary class
        visibleParent.removeClass('temp-show');

        return thisHeight;
    };

    /**
     * Find the actual width of an element even if it is hidden
     */
    $.fn.actualWidth = function(){
        // find the closest visible parent and get it's hidden children
        if ($(this).is(':visible')) {
            return $(this).outerWidth();
        }

        var visibleParent = this.closest(':visible').children(),
            thisWidth;

        // set a temporary class on the hidden parent of the element
        visibleParent.addClass('temp-show');

        // get the height
        thisWidth = this.width();

        // remove the temporary class
        visibleParent.removeClass('temp-show');

        return thisWidth;
    };

    /**
     * Check if element is below the page fold
     */
    $.belowthefold = function($element, settings) {
        var fold = $(window).height() + $(window).scrollTop();
        return fold <= $element.offset().top - settings.threshold;
    };

    /**
     * Check if an element is above the top of the page
     */
    $.abovethetop = function($element, settings) {
        var fold = $(window).scrollTop();
        return fold >= $element.offset().top + settings.threshold  + $element.height();
    };

    /**
     * Check if an element is in view
     */
    $.inview = function($element, settings) {
        return ($.abovethetop($element,settings)!=true && $.belowthefold($element,settings)!=true)
    };

    /**
     * Pretty checkboxes
     * @author Stephane Caron (http://www.no-margin-for-errors.com)
     */
    $.fn.prettyCheckboxes = function(settings) {
        settings = jQuery.extend({
            checkboxWidth: 17,
            checkboxHeight: 17,
            className : 'prettyCheckbox',
            display: 'list'
        }, settings);

        $(this).each(function(){
            // Find the label
            $label = $('label[for="'+$(this).attr('id')+'"]');

            // Add the checkbox holder to the label
            $label.prepend("<span class='holderWrap'><span class='holder'></span></span>");

            // If the checkbox is checked, display it as checked
            if($(this).is(':checked')) {
                $label.addClass('checked');
            };

            // Assign the class on the label
            $label.addClass(settings.className).addClass($(this).attr('type')).addClass(settings.display);

            // Assign the dimensions to the checkbox display
            $label.find('span.holderWrap').width(settings.checkboxWidth).height(settings.checkboxHeight);
            $label.find('span.holder').width(settings.checkboxWidth);

            // Hide the checkbox
            $(this).addClass('hiddenCheckbox');

            // Associate the click event
            $label.bind('click',function(){
                $('input#' + $(this).attr('for')).triggerHandler('click');

                if($('input#' + $(this).attr('for')).is(':checkbox')){
                    $(this).toggleClass('checked');
                    $('input#' + $(this).attr('for')).checked = true;

                    $(this).find('span.holder').css('top',0);
                }else{
                    $toCheck = $('input#' + $(this).attr('for'));

                    // Uncheck all radio
                    $('input[name="'+$toCheck.attr('name')+'"]').each(function(){
                        $('label[for="' + $(this).attr('id')+'"]').removeClass('checked');
                    });

                    $(this).addClass('checked');
                    $toCheck.checked = true;
                };
            });

            $('input#' + $label.attr('for')).bind('keypress',function(e){
                if(e.keyCode == 32){
                    if($.browser.msie){
                        $('label[for="'+$(this).attr('id')+'"]').toggleClass("checked");
                    }else{
                        $(this).trigger('click');
                    }
                    return false;
                };
            });
        });
    };

})(jQuery);


// Dom ready
$(function () {

    // home
    $('#home-reviews-pill-menu li:eq(0)').bind('click', function(e){
        e.preventDefault();
        $(this).siblings().removeClass('on');
        $(this).addClass('on');
        $('.home-reviews-container-latest').show();
        $('.home-reviews-container-popular').hide();
    });

    $('#home-reviews-pill-menu li:eq(1)').bind('click', function(e){
        e.preventDefault();
        $(this).siblings().removeClass('on');
        $(this).addClass('on');
        $('.home-reviews-container-latest').hide();
        $('.home-reviews-container-popular').show();
    });

    $('.home-reviews-container-popular').hide();

    // answer centre
    $('#answer-centre-pill-menu li:eq(0)').bind('click', function(e){
        e.preventDefault();
        $(this).siblings().removeClass('on');
        $(this).addClass('on');
        $('.answer-centre-container-latest').show();
        $('.answer-centre-container-popular').hide();
    });

    $('#answer-centre-pill-menu li:eq(1)').bind('click', function(e){
        e.preventDefault();
        $(this).siblings().removeClass('on');
        $(this).addClass('on');
        $('.answer-centre-container-latest').hide();
        $('.answer-centre-container-popular').show();
    });

    $('.answer-centre-container-popular').hide();


    $( "#review-rating .slider" ).uislider({
        min: 0,
        max: 5,
        step: 0.5,
        value: 0,
        slide: function(event, ui) {
            var value = ui.value;
            //update the UI
            $(this).parent().find(".rating").text(value);
            //update the hidden form input
            $('#user-rating').val(value);
        }
    });

    $("div.reader-reviews a.post-review").click(function(e){
        e.preventDefault();
        $("div.reader-reviews").slideUp();
        $("div.post-review.popup").slideDown();
        return false;
    });

    $("div.post-review a.close").click(function(e){
        e.preventDefault();
        $("div.reader-reviews").slideDown();
        $("div.post-review.popup").slideUp();
        return false;
    });

    // ask friend model
    $("#compare-share-uri").mouseup(function(){
        this.select();
    });

    if ( $.isMobile ) {
        //additional Mobile JS scripts here

        // review mobile
        if ($.Body.hasClass("review")) {
            $(".row.navigation").after("<div id='mobile-section-wrap' />");
            $("#mobile-section-wrap").append("<div class='row intro-wrap' />");
            $(".row.intro").appendTo(".row.intro-wrap");
            $(".row.intro-copy").appendTo(".row.intro-wrap");
            $(".row.whatsnew").appendTo("#mobile-section-wrap");
            $(".row.gallery-row").appendTo("#mobile-section-wrap");
            $(".row.performance").appendTo("#mobile-section-wrap");
            $(".row.tech").appendTo("#mobile-section-wrap");
            $(".row.verdict").appendTo("#mobile-section-wrap");

            $(".row.intro .wrap div").matchItemHeights();

            var $mobileSections = $("#mobile-section-wrap > .row")
            $reviewMenuItems = $(".row.navigation li");

            $reviewMenuItems.bind('click', mobileItem_Click);
            function mobileItem_Click(e) {
                e.preventDefault();
                $containerPos = $($reviewMenuItems[$reviewMenuItems.index($(this))]).position().left * -1;
                $("#review-nav").stop().animate({left:$containerPos}, {duration:window.wheels.duration, easing:window.wheels.ease});
                $reviewMenuItems.removeClass('active');
                $(this).addClass('active');
                $mobileSections.hide();
                $($mobileSections[$reviewMenuItems.index($(this))]).show();

            }
        }

        // home mobile
        if ($.Body.hasClass("home")) {

            // top carousels
            $('.home-carousel div[data-controller="CarouselController"]').each(function(){
                var mobileFeatures = $(".mobile-feature", this);
                $(".item", this).remove();
                $(".carousel-inner", this).append(mobileFeatures);

                $(".carousel-inner .mobile-feature", this).each(function(){
                    $(this).wrap("<div class='item' />");
                });
                $(".carousel-inner .item:eq(0)", this).addClass("active");
            });

            // review content carousel
            var slides = $('.reviews.home-module .slide .pos');
            $('.reviews.home-module .slide').remove();

            slides.each(function(index){
                if ((index % 4) == 0) {
                    // new column
                    $('.reviews.home-module .container ul').append("<li class='slide' />");
                }
                $(".reviews.home-module .container ul li:last-child").append($(this));
            });

            $('.reviews.home-module .slide .pos').wrap("<div class='slide-wrap' />");
            $('.reviews.home-module').SlidesController();

            // vehicle profile content carousel
            var slides = $('.vehicle-profile.home-module .slide .pos');
            $('.vehicle-profile.home-module .slide').remove();

            slides.each(function(index){
                if ((index % 4) == 0) {
                    // new column
                    $('.vehicle-profile.home-module .container ul').append("<li class='slide' />");
                }
                $(".vehicle-profile.home-module .container ul li:last-child").append($(this));
            });

            $('.vehicle-profile.home-module .slide .pos').wrap("<div class='slide-wrap' />");
            $('.vehicle-profile.home-module').SlidesController();


        }

    }
    else {

        var mqtriggered = false
            , originalLatestCarousel
            , originalPopularCarousel;

        $(window).resize(function () {
            var width = $(window).innerWidth();

        if ($.Body.hasClass("home") && width <= 480 && getCookie('isMobile') != 'no') {

                if (!mqtriggered) {
                    mqtriggered = true;

                    // add mobile class
                    if ( !$.Body.hasClass('webkit-mobile') ) {
                        $.Body.addClass('webkit-mobile');
                    }

                    originalLatestCarousel = $('.home-reviews-container-latest').html();
                    originalPopularCarousel = $('.home-reviews-container-popular').html();

                    // top carousels
                    $('.home-carousel div[data-controller="CarouselController"]').each(function(){
                        var mobileFeatures = $(".mobile-feature", this);
                        $(".item", this).remove();
                        $(".carousel-inner", this).append(mobileFeatures);
                        $(".carousel-inner .mobile-feature", this).each(function(){
                            $(this).wrap("<div class='item' />");
                        });
                        $(".carousel-inner .item:eq(0)", this).addClass("active");
                    });

                    // scale images
                    $('.home-reviews-container-latest .item img').css('margin-top', '0px');
                    $('.home-reviews-container-latest .item img').imgscale({
                        parent : '.feature-container'
                    });
                    $('.home-reviews-container-popular .item img').css('margin-top', '0px');
                    $('.home-reviews-container-popular .item img').imgscale({
                        parent : '.feature-container'
                    });

                }
            }
            else {
                if (mqtriggered) {
                    mqtriggered = false;

                    // add mobile class
                    if ( $.Body.hasClass('webkit-mobile') ) {
                        $.Body.removeClass('webkit-mobile');
                    }

                    $('.home-reviews-container-latest').html(originalLatestCarousel);
                    $('.home-reviews-container-popular').html(originalPopularCarousel);

                    // scale images
                    $('.home-reviews-container-latest .item img').css('margin-top', '0px');
                    $('.home-reviews-container-latest .item img').imgscale({
                        parent : '.feature-container'
                    });
                    $('.home-reviews-container-popular .item img').css('margin-top', '0px');
                    $('.home-reviews-container-popular .item img').imgscale({
                        parent : '.feature-container'
                    });

                }
            }
        });
        $(window).trigger('resize');


        // pretty checkboxes
        $('input[type=checkbox]').prettyCheckboxes();

        // debate radio buttons
        $('#debate input[type=radio]').prettyCheckboxes({checkboxWidth:23, checkboxHeight:23});

    }

    /**
     * Remove widows
     */
        // My Wheels
    $('#my-comparisons .car h4').removeWidows();

    /**
     * Match list heights
     */
        // Vehicles and Reviews
    $('#vehicles-reviews .used-vehicles ul.listing li').matchItemHeights();
    $('#features .in-this-section ul li').matchItemHeights();
    $('#news .in-this-section ul li').matchItemHeights();

    /**
     * Placeholder fix
     */
    if(!Modernizr.input.placeholder){
        $("input").each(
            function(){
                if($(this).val()=="" && $(this).attr("placeholder")!=""){
                    $(this).val($(this).attr("placeholder"));
                    $(this).focus(function(){
                        if($(this).val()==$(this).attr("placeholder")) $(this).val("");
                    });
                    $(this).blur(function(){
                        if($(this).val()=="") $(this).val($(this).attr("placeholder"));
                    });
                }
            });
    }

});


/**
 * Window load event
 */
$(window).load(function() {
    // load gmaps if there is a map on this page
    //if ($('#map_canvas').length > 0) {
    //  loadGoogleMapScript();
    //}

});


/**
 * jquery-mobile document ready event
 */
$( document ).bind( "pageinit", function( event, data ){

});


/**
 * Initialize maps
 */
//function initializeMaps() {
//  var myOptions = {
//    zoom: 8,
//    center: new google.maps.LatLng(43.652, -79.381),
//    mapTypeId: google.maps.MapTypeId.ROADMAP
//  }
//  var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
//}

/**
 * Asynch load google maps script
 */
function loadGoogleMapScript() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "http://maps.googleapis.com/maps/api/js?key=AIzaSyBYEMJFeb2W4hD61HklrQLThnXLmFD_W-Y&sensor=false&callback=initializeMaps";
    document.body.appendChild(script);
}


(function($) {
    $.widget( "ui.combobox", {
        _create: function() {
            var self = this,
                select = this.element.hide(),
                selected = select.children( ":selected" ),
                value = $.trim(selected.val()) ? $.trim(selected.text()) : "";

            var input = this.input = $( "<input>" ).insertAfter( select ).val( value ).autocomplete({
                delay: 0,
                minLength: 0,
                source: function( request, response ) {
                    var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                    response( select.children( "option" ).map(function() {
                        var text = $( this ).text();
                        if ( this.value && ( !request.term || matcher.test(text) ) )
                            return {
                                label: text.replace(
                                    new RegExp(
                                        "(?![^&;]+;)(?!<[^<>]*)(" +
                                            $.ui.autocomplete.escapeRegex(request.term) +
                                            ")(?![^<>]*>)(?![^&;]+;)", "gi"
                                    ), "$1" ),
                                value: $.trim(text),
                                option: this
                            };
                    }) );
                },
                select: function( event, ui ) {
                    ui.item.option.selected = true;
                    self._trigger( "selected", event, {
                        item: ui.item.option
                    });
                },
                change: function( event, ui ) {
                    if ( !ui.item ) {
                        var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
                            valid = false;
                        select.children( "option" ).each(function() {
                            if ( $( this ).text().match( matcher ) ) {
                                this.selected = valid = true;
                                return false;
                            }
                        });
                        if ( !valid ) {
                            // remove invalid value, as it didn't match anything
                            $( this ).val( "" );
                            select.val( "" );
                            input.data( "autocomplete" ).term = "";
                            return false;
                        }
                    }
                }
            })
                .addClass( "ui-widget ui-widget-content ui-corner-left" );

            input.addClass(select.attr('class'));

            input.data( "autocomplete" )._renderItem = function( ul, item ) {
                ul.addClass(select.attr('class'));
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.label + "</a>" )
                    .appendTo( ul );
            };

            this.button = $( "<button type='button'>&nbsp;</button>" )
                .attr( "tabIndex", -1 )
                .attr( "title", "Show All Items" )
                .addClass(select.attr('class'))
                .insertAfter( input )
                .button({
                    icons: {
                        primary: "ui-icon-triangle-1-s"
                    },
                    text: false
                })
                .removeClass( "ui-corner-all" )
                .addClass( "ui-corner-right ui-button-icon" )
                .click(function() {
                    // close if already visible
                    if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
                        input.autocomplete( "close" );
                        return;
                    }

                    // work around a bug (likely same cause as #5265)
                    $( this ).blur();

                    // pass empty string as value to search for, displaying all results
                    input.autocomplete( "search", "" );
                    input.focus();
                });
        },
        destroy: function() {
            this.input.remove();
            this.button.remove();
            this.element.show();
            $.Widget.prototype.destroy.call( this );
        }
    });
})(jQuery);


/* ==========================================================
 * bootstrap-carousel.js v2.0.0
 * http://twitter.github.com/bootstrap/javascript.html#carousel
 * ==========================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */


!function( $ ) {

    $(function () {

        "use strict"

        /* CSS TRANSITION SUPPORT (https://gist.github.com/373874)
         * ======================================================= */

        $.support.transition = (function () {
            var thisBody = document.body || document.documentElement
                , thisStyle = thisBody.style
                , support = thisStyle.transition !== undefined || thisStyle.WebkitTransition !== undefined || thisStyle.MozTransition !== undefined || thisStyle.MsTransition !== undefined || thisStyle.OTransition !== undefined

            return support && {
                end: (function () {
                    var transitionEnd = "TransitionEnd"
                    if ( $.browser.webkit ) {
                        transitionEnd = "webkitTransitionEnd"
                    } else if ( $.browser.mozilla ) {
                        transitionEnd = "transitionend"
                    } else if ( $.browser.opera ) {
                        transitionEnd = "oTransitionEnd"
                    }
                    return transitionEnd
                }())
            }
        })()

    })

}( window.jQuery )


!function( $ ){

    "use strict"

    /* CAROUSEL CLASS DEFINITION
     * ========================= */

    var Carousel = function (element, options) {
        this.$element = $(element)
        this.options = $.extend({}, $.fn.carousel.defaults, options)
        this.options.slide && this.slide(this.options.slide)
    }

    Carousel.prototype = {

        cycle: function () {
            this.interval = setInterval($.proxy(this.next, this), this.options.interval)
            return this
        }

        , to: function (pos) {
            var $active = this.$element.find('.active')
                , children = $active.parent().children()
                , activePos = children.index($active)
                , that = this

            if (pos > (children.length - 1) || pos < 0) return

            if (this.sliding) {
                return this.$element.one('slid', function () {
                    that.to(pos)
                })
            }

            if (activePos == pos) {
                return this.pause().cycle()
            }

            return this.slide(pos > activePos ? 'next' : 'prev', $(children[pos]))
        }

        , pause: function () {
            clearInterval(this.interval)
            return this
        }

        , next: function () {
            if (this.sliding) return
            return this.slide('next')
        }

        , prev: function () {
            if (this.sliding) return
            return this.slide('prev')
        }

        , slide: function (type, next) {

            // Modified by Samiul 13 Aug 2012
            // Prevent slide if slider items are less then 3
            var xChildElement = this.$element.find('.feature-container');
            var xGuidePage = $('#guides').length; // Exclude prevent slide for guide lading page
            if( !xGuidePage && xChildElement.length && xChildElement.length < 4 ) return false;

            var $active = this.$element.find('.active')
                , $next = next || $active[type]()
                , isCycling = this.interval
                , direction = type == 'next' ? 'left' : 'right'
                , fallback  = type == 'next' ? 'first' : 'last'
                , that = this

            this.sliding = true

            isCycling && this.pause()

            $next = $next.length ? $next : this.$element.find('.item')[fallback]()

            if (!$.support.transition && this.$element.hasClass('slide')) {
                this.$element.trigger('slide')
                $active.removeClass('active')
                $next.addClass('active')
                this.sliding = false
                this.$element.trigger('slid')
            } else {
                $next.addClass(type)
                $next[0].offsetWidth // force reflow
                $active.addClass(direction)
                $next.addClass(direction)
                this.$element.trigger('slide')
                this.$element.one($.support.transition.end, function () {
                    $next.removeClass([type, direction].join(' ')).addClass('active')
                    $active.removeClass(['active', direction].join(' '))
                    that.sliding = false
                    setTimeout(function () {that.$element.trigger('slid')}, 0)
                })
            }

            isCycling && this.cycle()

            return this
        }

    }


    /* CAROUSEL PLUGIN DEFINITION
     * ========================== */

    $.fn.carousel = function ( option ) {
        return this.each(function () {
            var $this = $(this)
                , data = $this.data('carousel')
                , options = typeof option == 'object' && option
            if (!data) $this.data('carousel', (data = new Carousel(this, options)))
            if (typeof option == 'number') data.to(option)
            else if (typeof option == 'string' || (option = options.slide)) data[option]()
            else data.cycle()
        })
    }

    $.fn.carousel.defaults = {
        interval: 5000
    }

    $.fn.carousel.Constructor = Carousel


    /* CAROUSEL DATA-API
     * ================= */

    $(function () {
        $('body').on('click.carousel.data-api', '[data-slide]', function ( e ) {
            var $this = $(this), href
                , $target = $($this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) //strip for ie7
                , options = !$target.data('modal') && $.extend({}, $target.data(), $this.data())
            $target.carousel(options)
            e.preventDefault()
        })
    })

}( window.jQuery );



/*!
 * jQuery Expander Plugin v1.4
 *
 * Date: Sun Dec 11 15:08:42 2011 EST
 * Requires: jQuery v1.3+
 *
 * Copyright 2011, Karl Swedberg
 * Dual licensed under the MIT and GPL licenses (just like jQuery):
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */

(function($) {
    $.expander = {
        version: '1.4',
        defaults: {
            // the number of characters at which the contents will be sliced into two parts.
            slicePoint: 100,

            // whether to keep the last word of the summary whole (true) or let it slice in the middle of a word (false)
            preserveWords: true,

            // a threshold of sorts for whether to initially hide/collapse part of the element's contents.
            // If after slicing the contents in two there are fewer words in the second part than
            // the value set by widow, we won't bother hiding/collapsing anything.
            widow: 4,

            // text displayed in a link instead of the hidden part of the element.
            // clicking this will expand/show the hidden/collapsed text
            expandText: 'read more',
            expandPrefix: '&hellip; ',

            expandAfterSummary: false,

            // class names for summary element and detail element
            summaryClass: 'summary',
            detailClass: 'details',

            // class names for <span> around "read-more" link and "read-less" link
            moreClass: 'read-more',
            lessClass: 'read-less',

            // number of milliseconds after text has been expanded at which to collapse the text again.
            // when 0, no auto-collapsing
            collapseTimer: 0,

            // effects for expanding and collapsing
            expandEffect: 'fadeIn',
            expandSpeed: 250,
            collapseEffect: 'fadeOut',
            collapseSpeed: 200,

            // allow the user to re-collapse the expanded text.
            userCollapse: true,

            // text to use for the link to re-collapse the text
            userCollapseText: 'read less',
            userCollapsePrefix: ' ',


            // all callback functions have the this keyword mapped to the element in the jQuery set when .expander() is called

            onSlice: null, // function() {}
            beforeExpand: null, // function() {},
            afterExpand: null, // function() {},
            onCollapse: null // function(byUser) {}
        }
    };

    $.fn.expander = function(options) {
        var meth = 'init';

        if (typeof options == 'string') {
            meth = options;
            options = {};
        }

        var opts = $.extend({}, $.expander.defaults, options),
            rSelfClose = /^<(?:area|br|col|embed|hr|img|input|link|meta|param).*>$/i,
            rAmpWordEnd = /(&(?:[^;]+;)?|\w+)$/,
            rOpenCloseTag = /<\/?(\w+)[^>]*>/g,
            rOpenTag = /<(\w+)[^>]*>/g,
            rCloseTag = /<\/(\w+)>/g,
            rLastCloseTag = /(<\/[^>]+>)\s*$/,
            rTagPlus = /^<[^>]+>.?/,
            delayedCollapse;

        var methods = {
            init: function() {
                this.each(function() {
                    var i, l, tmp, summTagLess, summOpens, summCloses, lastCloseTag, detailText,
                        $thisDetails, $readMore,
                        openTagsForDetails = [],
                        closeTagsForsummaryText = [],
                        defined = {},
                        thisEl = this,
                        $this = $(this),
                        $summEl = $([]),
                        o = $.meta ? $.extend({}, opts, $this.data()) : opts,
                        hasDetails = !!$this.find('.' + o.detailClass).length,
                        hasBlocks = !!$this.find('*').filter(function() {
                            var display = $(this).css('display');
                            return (/^block|table|list/).test(display);
                        }).length,
                        el = hasBlocks ? 'div' : 'span',
                        detailSelector = el + '.' + o.detailClass,
                        moreSelector = 'span.' + o.moreClass,
                        expandSpeed = o.expandSpeed || 0,
                        allHtml = $.trim( $this.html() ),
                        allText = $.trim( $this.text() ),
                        summaryText = allHtml.slice(0, o.slicePoint);

                    // bail out if we've already set up the expander on this element
                    if ( $.data(this, 'expander') ) {
                        return;
                    }
                    $.data(this, 'expander', true);

                    // determine which callback functions are defined
                    $.each(['onSlice','beforeExpand', 'afterExpand', 'onCollapse'], function(index, val) {
                        defined[val] = $.isFunction(o[val]);
                    });

                    // back up if we're in the middle of a tag or word
                    summaryText = backup(summaryText);

                    // summary text sans tags length
                    summTagless = summaryText.replace(rOpenCloseTag, '').length;

                    // add more characters to the summary, one for each character in the tags
                    while (summTagless < o.slicePoint) {
                        newChar = allHtml.charAt(summaryText.length);
                        if (newChar == '<') {
                            newChar = allHtml.slice(summaryText.length).match(rTagPlus)[0];
                        }
                        summaryText += newChar;
                        summTagless++;
                    }

                    summaryText = backup(summaryText, o.preserveWords);

                    // separate open tags from close tags and clean up the lists
                    summOpens = summaryText.match(rOpenTag) || [];
                    summCloses = summaryText.match(rCloseTag) || [];

                    // filter out self-closing tags
                    tmp = [];
                    $.each(summOpens, function(index, val) {
                        if ( !rSelfClose.test(val) ) {
                            tmp.push(val);
                        }
                    });
                    summOpens = tmp;

                    // strip close tags to just the tag name
                    l = summCloses.length;
                    for (i = 0; i < l; i++) {
                        summCloses[i] = summCloses[i].replace(rCloseTag, '$1');
                    }

                    // tags that start in summary and end in detail need:
                    // a). close tag at end of summary
                    // b). open tag at beginning of detail
                    $.each(summOpens, function(index, val) {
                        var thisTagName = val.replace(rOpenTag, '$1');
                        var closePosition = $.inArray(thisTagName, summCloses);
                        if (closePosition === -1) {
                            openTagsForDetails.push(val);
                            closeTagsForsummaryText.push('</' + thisTagName + '>');

                        } else {
                            summCloses.splice(closePosition, 1);
                        }
                    });

                    // reverse the order of the close tags for the summary so they line up right
                    closeTagsForsummaryText.reverse();

                    // create necessary summary and detail elements if they don't already exist
                    if ( !hasDetails ) {

                        // end script if there is no detail text or if detail has fewer words than widow option
                        detailText = allHtml.slice(summaryText.length);

                        if ( detailText === '' || detailText.split(/\s+/).length < o.widow ) {
                            return;
                        }

                        // otherwise, continue...
                        lastCloseTag = closeTagsForsummaryText.pop() || '';
                        summaryText += closeTagsForsummaryText.join('');
                        detailText = openTagsForDetails.join('') + detailText;

                    } else {
                        // assume that even if there are details, we still need readMore/readLess/summary elements
                        // (we already bailed out earlier when readMore el was found)
                        // but we need to create els differently

                        // remove the detail from the rest of the content
                        detailText = $this.find(detailSelector).remove().html();

                        // The summary is what's left
                        summaryText = $this.html();

                        // allHtml is the summary and detail combined (this is needed when content has block-level elements)
                        allHtml = summaryText + detailText;

                        lastCloseTag = '';
                    }
                    o.moreLabel = $this.find(moreSelector).length ? '' : buildMoreLabel(o);

                    if (hasBlocks) {
                        detailText = allHtml;
                    }
                    summaryText += lastCloseTag;

                    // onSlice callback
                    o.summary = summaryText;
                    o.details = detailText;
                    o.lastCloseTag = lastCloseTag;

                    if (defined.onSlice) {
                        // user can choose to return a modified options object
                        // one last chance for user to change the options. sneaky, huh?
                        // but could be tricky so use at your own risk.
                        tmp = o.onSlice.call(thisEl, o);

                        // so, if the returned value from the onSlice function is an object with a details property, we'll use that!
                        o = tmp && tmp.details ? tmp : o;
                    }

                    // build the html with summary and detail and use it to replace old contents
                    var html = buildHTML(o, hasBlocks);

                    $this.html( html );

                    // set up details and summary for expanding/collapsing
                    $thisDetails = $this.find(detailSelector);
                    $readMore = $this.find(moreSelector);
                    $thisDetails.hide();
                    $readMore.find('a').unbind('click.expander').bind('click.expander', expand);

                    $summEl = $this.find('div.' + o.summaryClass);

                    if ( o.userCollapse && !$this.find('span.' + o.lessClass).length ) {
                        $this
                            .find(detailSelector)
                            .append('<span class="' + o.lessClass + '">' + o.userCollapsePrefix + '<a href="#">' + o.userCollapseText + '</a></span>');
                    }

                    $this
                        .find('span.' + o.lessClass + ' a')
                        .unbind('click.expander')
                        .bind('click.expander', function(event) {
                            event.preventDefault();
                            clearTimeout(delayedCollapse);
                            var $detailsCollapsed = $(this).closest(detailSelector);
                            reCollapse(o, $detailsCollapsed);
                            if (defined.onCollapse) {
                                o.onCollapse.call(thisEl, true);
                            }
                        });

                    function expand(event) {
                        event.preventDefault();
                        $readMore.hide();
                        $summEl.hide();
                        if (defined.beforeExpand) {
                            o.beforeExpand.call(thisEl);
                        }

                        $thisDetails.stop(false, true)[o.expandEffect](expandSpeed, function() {
                            $thisDetails.css({zoom: ''});
                            if (defined.afterExpand) {o.afterExpand.call(thisEl);}
                            delayCollapse(o, $thisDetails, thisEl);
                        });
                    }

                }); // this.each
            },
            destroy: function() {
                if ( !this.data('expander') ) {
                    return;
                }
                this.removeData('expander');
                this.each(function() {
                    var $this = $(this),
                        o = $.meta ? $.extend({}, opts, $this.data()) : opts,
                        details = $this.find('.' + o.detailClass).contents();

                    $this.find('.' + o.moreClass).remove();
                    $this.find('.' + o.summaryClass).remove();
                    $this.find('.' + o.detailClass).after(details).remove();
                    $this.find('.' + o.lessClass).remove();

                });
            }
        };

        // run the methods (almost always "init")
        if ( methods[meth] ) {
            methods[ meth ].call(this);
        }

        // utility functions
        function buildHTML(o, blocks) {
            var el = 'span',
                summary = o.summary;
            if ( blocks ) {
                el = 'div';
                // if summary ends with a close tag, tuck the moreLabel inside it
                if ( rLastCloseTag.test(summary) && !o.expandAfterSummary) {
                    summary = summary.replace(rLastCloseTag, o.moreLabel + '$1');
                } else {
                    // otherwise (e.g. if ends with self-closing tag) just add moreLabel after summary
                    // fixes #19
                    summary += o.moreLabel;
                }

                // and wrap it in a div
                summary = '<div class="' + o.summaryClass + '">' + summary + '</div>';
            } else {
                summary += o.moreLabel;
            }

            return [
                summary,
                '<',
                el + ' class="' + o.detailClass + '"',
                '>',
                o.details,
                '</' + el + '>'
            ].join('');
        }

        function buildMoreLabel(o) {
            var ret = '<span class="' + o.moreClass + '">' + o.expandPrefix;
            ret += '<a href="#">' + o.expandText + '</a></span>';
            return ret;
        }

        function backup(txt, preserveWords) {
            if ( txt.lastIndexOf('<') > txt.lastIndexOf('>') ) {
                txt = txt.slice( 0, txt.lastIndexOf('<') );
            }
            if (preserveWords) {
                txt = txt.replace(rAmpWordEnd,'');
            }
            return txt;
        }

        function reCollapse(o, el) {
            el.stop(true, true)[o.collapseEffect](o.collapseSpeed, function() {
                var prevMore = el.prev('span.' + o.moreClass).show();
                if (!prevMore.length) {
                    el.parent().children('div.' + o.summaryClass).show()
                        .find('span.' + o.moreClass).show();
                }
            });
        }

        function delayCollapse(option, $collapseEl, thisEl) {
            if (option.collapseTimer) {
                delayedCollapse = setTimeout(function() {
                    reCollapse(option, $collapseEl);
                    if ( $.isFunction(option.onCollapse) ) {
                        option.onCollapse.call(thisEl, false);
                    }
                }, option.collapseTimer);
            }
        }

        return this;
    };

    // plugin defaults
    $.fn.expander.defaults = $.expander.defaults;
})(jQuery);

// http://imgscale.kjmeath.com
// modified __showImage to remove animation check
(function(a){a.fn.imgscale=function(f){f=a.extend({parent:false,scale:"fill",center:true,fade:0},f);var i,e,j,k,c,d,h,b;this.each(function(){var l=a(this);var m=(!f.parent?l.parent():l.parents(f.parent));m.css({opacity:0,overflow:'hidden'});if(m.length>0){l.removeAttr("height").removeAttr("width");if(this.complete){g(l,m,false)}else{l.load(function(){g(l,m,true)})}}});function g(l,p,r){i=p.height();e=p.width();j=l.height();k=l.width();n();function n(){if(e>i){m("w")}else{if(e<i){m("t")}else{if(e==i){m("s")}}}}function m(v){if(k>j){t(v,"w")}else{if(k<j){t(v,"t")}else{if(k==j){t(v,"s")}}}}function t(w,v){if(w=="w"&&v=="w"){q()}else{if(w=="w"&&v=="t"){s("w")}else{if(w=="w"&&v=="s"){s("w")}else{if(w=="t"&&v=="w"){s("w")}else{if(w=="t"&&v=="t"){q()}else{if(w=="t"&&v=="s"){s("t")}else{if(w=="s"&&v=="w"){s("t")}else{if(w=="s"&&v=="t"){s("w")}else{if(w=="s"&&v=="s"){s("w")}}}}}}}}}}function q(){if((k*i/k)>=e){s("t")}else{s("w")}}function s(v){switch(v){case"t":if(f.scale=="fit"){l.attr("width",e)}else{l.attr("height",i)}break;case"w":if(f.scale=="fit"){l.attr("height",i)}else{l.attr("width",e)}break}if(f.center){o()}else{u()}}function o(){c=l.width();d=l.height();if(d>i){b="-"+(Math.floor((d-i)/2))+"px";l.css("margin-top",b)}if(c>e){h="-"+(Math.floor((c-e)/2))+"px";l.css("margin-left",h)}u()}function u(){p.css("opacity",1)}}}})(jQuery);