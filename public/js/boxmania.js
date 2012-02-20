function boxmania(selector) {

    var i,
        colsNo,
        boxNo,
        boxWidth = 230,
        boxHeight = 205,
        expandedSize = 3,
        debug = false,
        loaderImg;

    function checkScrollbar() {
        var docHeight = $(document).height();
        var scroll    = $(window).height();
        if(docHeight > scroll) return true;
        else return false;
    }

    function getStateBack() {
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
                for( var i = 0; i < args.length; i++ ) {
                    args[i](target);
                }
            }
        });
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

    if ($.browser.mozilla || $.browser.opera) {
        loaderImg = 'loader.png';
    } else {
        loaderImg = 'loader.gif';
    }

    $(selector + ' .image-container').click(function () {

        var box = $(this).parent();

        $(box).css('background', '#bbb').append('<img id="loader"  src="images/' + loaderImg + '" alt="loading" />');
        $(this).fadeTo(0, 0.7);

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

    });

    $(selector + ' #close').live('click', function () {
        // clicked on expanded block
        getStateBack();
        $('.big-block').removeAttr('style').removeClass('big-block').addClass('block');
    });

};