function boxmania(selector) {

    var i,
        colsNo,
        boxNo;

    function getStateBack() {

        $('.moved').each(function () {
            $(this).removeClass('moved').removeAttr('style');
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
        colsNo = Math.floor(($(window).width() - 16) / 260);
        //setInterval(function () {
        //    colsNo = Math.floor(($(window).width() - 16) / 260);
        //    console.log(colsNo);
        //}, 1000);
    }

    function fillGaps() {
        var leftOffset = 0,
            topOffset = 0,
            clickedCol = checkClickedCol();

        if (clickedCol === 1) {return;}

        // TODO add proper handling when item from last column clicked (for now temporary solution)
        if (clickedCol === colsNo) {
            clickedCol -=1;
            $('.block:nth-child(' + (boxNo - 1) + ')').hide().addClass('moved');
        }

        i = 1;
        do {
            $('.block:nth-child(' + (boxNo + i) + ')').css({
                position: 'absolute',
                top: (topOffset * 260) + (Math.ceil(boxNo / colsNo) * 260),
                left: leftOffset * 260
            }).addClass('moved');
            leftOffset++;

            if (i % (clickedCol - 1) === 0) {
                leftOffset = 0;
                topOffset++;
            }
            i++;

        } while (i <= (clickedCol - 1) * 3);
    }

    function getItemText(target, itemId) {
        $.ajax({
            url: "get_item_text.php",
            type: "POST",
            data: {item_id : $(target).data('id')},
            success: function(html){
                $(target).find('.item-text').append(html);
            }

        });
    }

    $(selector + ' .block').click(function () {

        checkColNo();

        boxNo = $(this).data('no');

        if ($(this).hasClass('big')) {
            getStateBack();
            $(this).removeAttr('style').removeClass('big');
        } else {

            $(selector + ' .block').each(function () {
                if ($(this).hasClass('big')) {
                    console.log('found big one')
                    getStateBack();
                    $(this).removeAttr('style').removeClass('big');
                }
            });

            $(this).css({
                width: '510px',
                height: '1030px'
            }).addClass('big').append('<div class="item-text"></div>');
            getItemText(this, boxNo);
            fillGaps();
        }

    });

};