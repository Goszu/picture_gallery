function boxmania(selector) {

    var i,
        colsNo,
        boxNo,
        boxWidth = 230,
        boxHeight = 205,
        expandedSize = 3,
        debug = false,
        loaderImg,
        expanded = false;

    function checkScrollbar() {
        var docHeight = $(document).height();
        var scroll    = $(window).height();
        if (docHeight > scroll) return true;
        else return false;
    }

    function getStateBack() {
        $('.slideshow').remove();
        $('.big-block .name').insertAfter('.big-block .image-container');
        $('.big-block div.image-container').show();
        $('.moved').each(function () {
            $(this).removeAttr('style').removeClass('moved');
            if ($('#bl-' + ($(this).data("no") + 1)).html() !== null) {
                $(this).insertBefore('#bl-' + ($(this).data("no") + 1));
            } else {
                $(this).insertAfter('#bl-' + ($(this).data("no") - 1));
            }
        });
        $('.item-text').remove();
        $('#header').remove();
        $('.big-block').removeAttr('style').removeClass('big-block').addClass('block');
    }

    function checkClickedCol() {
        var colIterator = 0;
        do {
            colIterator++;
            if ((boxNo - colIterator) % colsNo === 0) {
                return colIterator;
            }
        } while (colIterator <= 7);
    }

    function checkColNo() {
        colsNo = Math.floor($('body').width() / boxWidth);
    }

    function fillGaps(expandedBox) {

        var leftOffset = 0,
            topOffset = 0,
            clickedCol = checkClickedCol(),
            expandedHeightInBoxesNo = Math.ceil($(expandedBox).height() / boxHeight);

        $(expandedBox).height((expandedHeightInBoxesNo * boxHeight) - 10);

        if (clickedCol === 1) {return;}

        if (clickedCol === colsNo) {
            clickedCol -=2;
            $(expandedBox).insertBefore('.block:nth-child(' + (boxNo - 2) + ')').addClass('moved');
            boxNo -= 2;
        }
        if (clickedCol === (colsNo - 1)) {
            clickedCol -=1;
            $(expandedBox).insertBefore('.block:nth-child(' + (boxNo - 1) + ')').addClass('moved');
            boxNo -= 1;
        }
        if (colsNo === expandedSize) {return;}

        i = 1;
        do {
            $('.block:nth-child(' + (boxNo + i) + ')').css({
                position: 'absolute',
                top: (topOffset * boxHeight) + (Math.ceil(boxNo / colsNo) * boxHeight),
                left: leftOffset * boxWidth
            }).addClass('moved');
            leftOffset++;

            if (i % (clickedCol - 1) === 0) {
                leftOffset = 0;
                topOffset++;
            }
            i++;

        } while (i <= (clickedCol - 1) * (expandedHeightInBoxesNo -1));
    }

    function expand(target) {
        $(target).css({
            width: (boxWidth * expandedSize) - 10,
            'min-height': '400px'
        }).removeClass('block').addClass('big-block');
    }

    function goToId(target){
        $('html,body').animate({scrollTop: $('#' + $(target).attr('id')).offset().top - 20},'slow');
    }

    function addExpandedMarkup(target) {
        $(target).prepend('<div id="header"><img src="images/close.gif" id="close"/></div>');
        $(target).find('.name').appendTo('#header');
    }

    function getItemText(target, itemId) {
        var args = Array.prototype.slice.apply(arguments, [2]);
        $.ajax({
            url: "get_item_text.php",
            type: "POST",
            data: {item_id : $(target).data('id')},
            success: function(html) {
                $('#loader').remove();
                $(target).css('background', '#ffffff').find('div.image-container').fadeTo(0, 1);
                $(target).find('div.image-container').hide();
                $(target).append('<div class="item-text">' + html + '</div>');
                if ($(target).hasClass('slide')) PORTFOLIO.slideshow = slideshow();
                for( var i = 0; i < args.length; i++ ) {
                    args[i](target);
                }
                $(selector + ' .image-container').bind('click', function () {
                    clickBoxHandler(this);
                });
                expanded = true;
            }
        });
    }

    function clickBoxHandler(target) {

        $(selector + ' .image-container').unbind('click');

        var box = $(target).parent();

        $(box).css('background', '#bbb').append('<img id="loader"  src="images/' + loaderImg + '" alt="loading" />');
        $(target).fadeTo(0, 0.7);

        checkColNo();

        boxNo = box.data('no');

        $(selector + ' div').each(function () {
            if ($(this).hasClass('big-block')) {
                // found expanded block somewere on the page - need to collapse it
                getStateBack();
                $(this).removeAttr('style').removeClass('big-block').addClass('block');
            }
        });

        // arguments from third - callback functions when ajax is successful
        getItemText(box, boxNo, expand, addExpandedMarkup, fillGaps, goToId);
    }

    function slideshow() {
        //TODO if item has slideshow option - get images in sliseshow container and start it
        var controlsfadeTimeout,
            autoplayInterval,
            api = {
                next: function () {
                    var current = $('.slideshow img.current');
                    if ( current.length == 0 ) current = $('.slideshow img:last');
                    var $next =  current.next('img').length ? current.next('img') : $('.slideshow img:first');
                    current.addClass('last-current');
                    $next.css({opacity: 0.0}).addClass('current').animate({opacity: 1.0}, 500, function() {
                        current.removeClass('current last-current');
                    });
                },
                autoplay: function (button) {
                    button = $(button);
                    if (button.hasClass('on')) {
                        button.removeAttr('class').text('AUTOPLAY');
                        clearInterval(autoplayInterval);
                    } else {
                        button.addClass('on').text('STOP');
                        autoplayInterval = setInterval(function () {
                            api.next();
                        }, 3000 );
                    }
                },
                showControls: function () {
                    clearTimeout(controlsfadeTimeout);
                    $('#controls').fadeIn(500);
                },
                hideControls: function () {
                    controlsfadeTimeout = setTimeout(function () {
                        $('#controls').fadeOut(500);
                    }, 500);
                }
            };

        // get images into container
        $('#bl-10 .item-text').before('<div class="slideshow"></div>');
        $('#bl-10 .item-text img').each(function () {
            $(this).appendTo('#bl-10 .slideshow');
        });

        //create controls
        $('.slideshow').append('<div id="controls"><span id="next">NEXT</span><span id="autoplay">AUTOPLAY</span></div>');

        //attach handlers
        $('.slideshow').live('mouseenter', function () {
            api.showControls();
        });
        $('.slideshow').live('mouseleave', function () {
            api.hideControls();
        });
        $('#next').live('click', function() {
            api.next();
        });
        $('#autoplay').live('click', function() {
            api.autoplay(this);
        });

        return api;
    }

    if (debug === true) {
        $('body').append('<div id="debug"></div>');
        setInterval(function () {
            colsNo = Math.floor($('body').width() / boxWidth);
            $('#debug').html('<p>Number of columns: ' + colsNo + '</p>' +
                             '<p>Scrollbar presence: ' + checkScrollbar() + '</p>');
            
        }, 500);
    }

    setInterval(function () {
        if(!checkScrollbar()) {
            $('body').css('padding-right', '20px');
        } else {
            $('body').css('padding-right', (20 - $.getScrollbarWidth()) + 'px');
        }
    }, 100);

    $(window).resize(function() {
        if(expanded) { getStateBack() }
    });

    if ($.browser.mozilla || $.browser.opera) {
        loaderImg = 'loader.png';
    } else {
        loaderImg = 'loader.gif';
    }

    $(selector + ' .image-container').bind('click', function () {
        clickBoxHandler(this);
    });

    $(selector + ' #close').live('click', function () {
        // clicked on expanded block
        expanded = false;
        getStateBack();
    });

};