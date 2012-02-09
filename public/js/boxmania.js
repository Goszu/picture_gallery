function boxmania(selector) {

    var i,
        colsNo,
        boxNo,
        boxWidth = 230,
        boxHeight = 205,
        expandedSize = 3;

    function getStateBack() {

        $('.moved').each(function () {
            $(this).removeClass('moved').removeAttr('style');
        });
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
            $('.block:nth-child(' + (boxNo - 1) + ')').hide().addClass('moved');
            $('.block:nth-child(' + (boxNo - 2) + ')').hide().addClass('moved');
        }
        if (clickedCol === (colsNo - 1)) {
            clickedCol -=1;
            $('.block:nth-child(' + (boxNo - 1) + ')').hide().addClass('moved');
        }

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

    function getItemText(target, itemId, callback) {
        $.ajax({
            url: "get_item_text.php",
            type: "POST",
            data: {item_id : $(target).data('id')},
            success: function(html){
                $(target).find('.item-text').append(html);
                callback(target);
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

            $(this).css({
                width: (boxWidth * expandedSize) - 10,
                'min-height': '810px'
            }).removeClass('block').addClass('big-block').append('<div class="item-text"></div>');
            getItemText(this, boxNo, fillGaps);
            //fillGaps(this);
        }

    });

};