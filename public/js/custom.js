var PORTFOLIO = {};

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
