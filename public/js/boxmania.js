function boxmania(selector) {

    var i,
        colsNo,
        boxNo,
        boxWidth = 230,
        boxHeight = 205,
        expandedSize = 3,
        boxBorder = 70,
        debug = false,
        expanded = false,
        api = {},
        windowWidth = $(window).width();

    function checkScrollbar() {
        var docHeight = $(document).height();
        var scroll    = $(window).height();
        return docHeight > scroll;
    }

    function getStateBack() {
        if (api.slideshow) {api.slideshow.removeAll()}
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
        //$('.item-text').remove();
        $('#inner').remove();

        $('.big-block').removeClass('big-block').addClass('block').removeAttr('style');
    }

    function checkClickedCol() {
        var colIterator = 0;
        do {
            colIterator++;
            if ((boxNo - colIterator) % colsNo === 0) {
                return colIterator;
            }
        } while (colIterator < 8);
    }

    function checkColNo() {
        colsNo = Math.floor($('body').width() / boxWidth);
    }

    function alignRight(selector, adjust) {
        var adj = adjust || 0;

        checkColNo();
        $(selector).css('float', 'left');
        if (colsNo > 3) {
            $(selector).css('padding-left', (((colsNo * boxWidth) - $(selector).width() - 10 ) + adj));
        } else if (colsNo === 3) {
            $(selector).css('padding-left', (((3 * boxWidth) - $(selector).width() - 10 ) + adj));
        } else {
            $(selector).css({'float': 'right', 'padding-left': 0});
        }
    }

    function fillGaps(expandedBox) {

        var leftOffset = 0,
            topOffset = 0,
            clickedCol = checkClickedCol(),
            expandedHeightInBoxesNo = Math.ceil(($(expandedBox).height() + (boxBorder * 2)) / boxHeight);

        //$(expandedBox).height(((expandedHeightInBoxesNo * boxHeight) - 10) - (boxBorder * 2));
        $(expandedBox).find('#inner').height(((expandedHeightInBoxesNo * boxHeight) - 10) - (boxBorder * 2));

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
            width: ((boxWidth * expandedSize) - 10) - (boxBorder * 2),
            'min-height': '260px'
        }).removeClass('block').addClass('big-block');
    }

    function goToId(target){
        $('html,body').animate({scrollTop: $('#' + $(target).attr('id')).offset().top -8},'slow');
    }

    function addExpandedMarkup(target) {
        $(target).prepend('<div id="inner"><div id="header"><img src="images/close.png" id="close"/></div></div>');
        $(target).find('.name').appendTo('#header');
        $(target).find('.item-text').appendTo('#inner');
        if ($(target).hasClass('slide')) {api.slideshow = slideshow($(target).attr('id'));}
    }

    function getItemText(target, itemNo) {
        var args = Array.prototype.slice.apply(arguments, [2]);
        $.ajax({
            url: "get_item_text.php",
            type: "POST",
            data: {item_id : $(target).data('id')},
            success: function(html) {
                function goOn(html_content, formPresent) {
                    var targetObj = $(target);
                    $('#loader').remove();

                    if (targetObj.data('bg-col')) {
                        targetObj.css('background-color', targetObj.data('bg-col'));
                    } else {
                        targetObj.css('background-color', '#fff');
                    }



                    targetObj.find('div.image-container').fadeTo(0, 1).hide();
                    targetObj.append('<div class="item-text">' + html_content + '</div>');
                    //if (targetObj.hasClass('slide')) {api.slideshow = slideshow(itemNo)}
                    for( var i = 0; i < args.length; i++ ) {
                        args[i](target);
                    }
                    $(selector + ' .image-container').bind('click', function () {
                        clickBoxHandler(this);
                    });
                    expanded = true;

                    if (formPresent) { PORTFOLIO.mailFunctionality(); }
                }

                if (/__FORM__/.test(html)) {
                    $.ajax({
                        url: "mail_form.php",
                        type: "GET",
                        success: function (form_html) {
                            html = html.replace(/__FORM__/, form_html);
                            goOn(html, true);
                        }
                    });
                } else {
                    goOn(html, false);
                }
            }
        });
    }

    function clickBoxHandler(target) {

        $(selector + ' .image-container').unbind('click');

        var box = $(target).parent();

        $(box).css('background-color', '#bbb').append('<img id="loader"  src="images/loader.gif" alt="loading" />');
        $(target).fadeTo(0, 0.4);

        checkColNo();

        boxNo = box.data('no');

        $(selector + ' div').each(function () {
            if ($(this).hasClass('big-block')) {
                // found expanded block somewere on the page - need to collapse it
                getStateBack();
            }
        });

        // arguments from third - callback functions when ajax is successful
        getItemText(box, boxNo, expand, addExpandedMarkup, fillGaps, goToId);
    }

    function slideshow(itemId) {
        var autoplayInterval,
            i = 1,
            length = $('#' + itemId + ' .item-text img').length,
            nextImg,
            current,
            frozen = false,
            slideshowApi = {
                next: function (imgNumber) {
                    if (frozen === true) return;
                    current = $('.slideshow img.current');
                    if (imgNumber !== undefined) {
                        clearInterval(autoplayInterval);
                        nextImg = $('.slideshow img:nth-child(' + imgNumber + ')');
                        if (nextImg.hasClass('current')) return;
                        frozen = true;
                    } else {
                        nextImg =  current.next('img').length ? current.next('img') : $('.slideshow img:first');
                    }
                    current.addClass('last-current');
                    nextImg.css({opacity: 0.0}).addClass('current').animate({opacity: 1.0}, 500, function() {
                        current.removeClass('current last-current');
                        frozen = false;
                    });
                },
                autoplay: function () {
                    autoplayInterval = setInterval(function () {
                        slideshowApi.next();
                    }, 3000 );
                },
                removeAll: function () {
                    $('.slideshow, #numbers').remove();
                    clearInterval(autoplayInterval);
                }
            };

        // get images into container
        $('#' + itemId + ' #inner .item-text').before('<div class="slideshow"></div>');
        $('#' + itemId + ' .item-text img').each(function () {
            $(this).appendTo('#' + itemId + ' .slideshow');
        });

        $('#' + itemId + ' .item-text img:last').addClass('current');

        //create navigation
        $('.slideshow').after('<div id="numbers"></div>');
        for(i; i <= length; i +=1) {
            $('#numbers').append('<span>' + i + '</span>');
        }

        //attach click handlers
        $('#numbers').delegate('span', 'click', function () {
            slideshowApi.next($(this).text());
        });

        slideshowApi.autoplay();

        return slideshowApi;
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

    alignRight('#footer');
    setTimeout(function () {
        alignRight('#social', -$('#company').width());
    }, 300);

    $(window).resize(function() {
        if(expanded && windowWidth !== $(window).width()) {
            expanded = false;
            getStateBack();
            windowWidth = $(window).width();
        }
        alignRight('#footer');
        alignRight('#social', -$('#company').width());
    });

    $(selector + ' .image-container').bind('click', function () {
        clickBoxHandler(this);
    });

    $(selector + ' #close').live('click', function () {
        // clicked on expanded block
        expanded = false;
        getStateBack();
    });

    return api;
}