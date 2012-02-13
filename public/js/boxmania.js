function boxmania(selector) {

    var i,
        colsNo,
        boxNo,
        boxWidth = 230,
        boxHeight = 205,
        expandedSize = 3;

    function getStateBack() {

        $('.moved').each(function () {
            console.log($(this).data("no"));
            $(this).removeClass('moved').insertAfter('.block:nth-child(' + ($(this).data("no") - 1) + ')').removeAttr('style');
        });
        $('.item-text').remove();
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
        colsNo = Math.floor(($(window).width() - 16) / boxWidth);
        //setInterval(function () {
        //    colsNo = Math.floor(($(window).width() - 16) / boxWidth);
        //    console.log(colsNo);
        //}, 1000);
    }

    function fillGaps(expandedBox) {

        var leftOffset = 0,
            topOffset = 0,
            clickedCol = checkClickedCol(),
            expandedHeightInBoxesNo = Math.ceil($(expandedBox).height() / boxHeight);

        $(expandedBox).height((expandedHeightInBoxesNo * boxHeight) - 10);

        if (clickedCol === 1) {return;}

        // TODO add proper handling when item from last column clicked (for now temporary solution)
        if (clickedCol === colsNo) {
            clickedCol -=2;
            $('.block:nth-child(' + (boxNo - 1) + ')').insertAfter('.block:last-child').addClass('moved');
            $('.block:nth-child(' + (boxNo - 2) + ')').insertAfter('.block:last-child').addClass('moved');
        }
        if (clickedCol === (colsNo - 1)) {
            clickedCol -=1;
            $('.block:nth-child(' + (boxNo - 1) + ')').insertAfter('.block:last-child').addClass('moved');
        }
        if (colsNo === 3) {return;}

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
            'min-height': '810px'
        }).removeClass('block').addClass('big-block').append('<div class="item-text"></div>');
    }

    function goToId(id){
        $('html,body').animate({scrollTop: $('#' + id).offset().top - 20},'slow');
    }

    function getItemText(target, itemId, callback1, callback2, callback3) {
        $.ajax({
            url: "get_item_text.php",
            type: "POST",
            data: {item_id : $(target).data('id')},
            success: function(html) {
                callback1(target);
                $(target).find('.item-text').append(html);
                callback2(target);
                callback3($(target).attr('id'));
            }

        });
    }

    $(selector + ' .block').click(function () {

        checkColNo();

        boxNo = $(this).data('no');

        if ($(this).hasClass('big-block')) {
            // clicked on expanded block
            getStateBack();
            $(this).removeAttr('style').removeClass('big-block').addClass('block');
        } else {

            $(selector + ' div').each(function () {
                if ($(this).hasClass('big-block')) {
                    // found expanded block somwere on the page - need to collapse it
                    getStateBack();
                    $(this).removeAttr('style').removeClass('big-block').addClass('block');
                }
            });


            getItemText(this, boxNo, expand, fillGaps, goToId);
            //fillGaps(this);
        }

    });

};