var PORTFOLIO = {};


/*! Copyright (c) 2008 Brandon Aaron (brandon.aaron@gmail.com || http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 */

/**
 * Gets the width of the OS scrollbar
 */
(function($) {
    var scrollbarWidth = 0;
    $.getScrollbarWidth = function() {
        if ( !scrollbarWidth ) {
            if ( $.browser.msie ) {
                var $textarea1 = $('<textarea cols="10" rows="2"></textarea>')
                    .css({ position: 'absolute', top: -1000, left: -1000 }).appendTo('body'),
                    $textarea2 = $('<textarea cols="10" rows="2" style="overflow: hidden;"></textarea>')
                        .css({ position: 'absolute', top: -1000, left: -1000 }).appendTo('body');
                scrollbarWidth = $textarea1.width() - $textarea2.width();
                $textarea1.add($textarea2).remove();
            } else {
                var $div = $('<div />')
                    .css({ width: 100, height: 100, overflow: 'auto', position: 'absolute', top: -1000, left: -1000 })
                    .prependTo('body').append('<div />').find('div')
                    .css({ width: '100%', height: 200 });
                scrollbarWidth = 100 - $div.width();
                $div.parent().remove();
            }
        }
        return scrollbarWidth;
    };
})(jQuery);

PORTFOLIO.browseServer = function () {
    // You can use the "CKFinder" class to render CKFinder in a page:
    var finder = new CKFinder();
    finder.basePath = '../../includes/ckfinder';	// The path for the installation of CKFinder (default = "/ckfinder/").
    finder.selectActionFunction = PORTFOLIO.setFileField;
    finder.popup();
};

// This is a sample function which is called when a file is selected in CKFinder.
PORTFOLIO.setFileField = function ( fileUrl ) {
    document.getElementById( 'thumbnail' ).value = fileUrl;
};

PORTFOLIO.sortItems = function () {
    $("ul.sortable").sortable({ opacity: 0.8, cursor: 'move', update: function() {
        var order = $(this).sortable("serialize") + '&update=update';
        $.post("updateorder.php", order, function (theResponse) {
            var info = $("<div id='response'></div>").insertAfter("div#main h2").html(theResponse).hide();
            info.slideDown('slow');
            setTimeout(function() {
                info.slideUp('slow', function () {
                    info.remove();
                });
            }, 2000);
        });
    }
    });
};

PORTFOLIO.mailFunctionality = function () {
    var name = $('#name'),
        tel = $('#tel'),
        mail = $('#mail'),
        message = $('#message');

    $('#form').delegate('#send-mail', 'click', function() {
        $.post("mail.php", { imie: $("#name").val(), tel: $("#tel").val(), mail: $("#mail").val(), wiad: $("#message").val() }, function(data) {
            $('#form').after('<div id="confirmation">' + data + '</div>');
            setTimeout(function () {
                $('#confirmation').fadeOut('fast', function () {
                    $(this).remove();
                });
            }, 2000);
        });
        return false;
    });
    $('#form').delegate('#reset-form', 'click', function() {
        document.getElementById('form').reset();
    });
    name.focus(function () {
        if (this.value =='Your Name:')  { this.value='' }
    });
    tel.focus(function () {
        if(this.value =='Telephone no.:') { this.value='' }
    });
    mail.focus(function () {
        if(this.value =='E-mail:' ) { this.value='' }
    });
    message.focus(function () {
        if(this.value =='Message:' ) { this.value='' }
    });
    name.blur(function () {
        if(this.value=='') { this.value='Your Name:' }
    });
    tel.blur(function () {
        if(this.value=='') { this.value='Telephone no.:' }
    });
    mail.blur(function () {
        if(this.value=='') { this.value='E-mail:' }
    });
    message.blur(function () {
        if(this.value=='') { this.value='Message:' }
    });
};