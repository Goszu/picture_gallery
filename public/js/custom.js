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

PORTFOLIO.bgSlide = function(config) {

    $(config.container).append('<div id="bg1"></div><div id="bg2"></div>').css({
        "position" : "relative",
        "margin" : "20px auto",
        "width" : config.width,
        "height" : config.height
    });
    $("#bg1").css({
        //"background" : "url(images/" + config.items[0].image + ")",
        "width" : config.width,
        "height" : config.height,
        "z-index" : 1
    });
    $("#bg2").css({
        //"background" : "url(images/" + config.items[1].image + ")",
        "width" : config.width,
        "height" : config.height,
        "z-index" : 0,
        "display" : "none"
    });

    var itemNo = 0,
        nextItem,
        itemCount = config.items.length,
        enabled = true,
        play = function () {
            console.log('play');
        },
        stop = function () {
            console.log('stop');
        },
        next = function () {
            if (enabled === true) {
                enabled = false;
                itemNo += 1;
                nextItem = itemNo + 1;
                if (itemNo === itemCount) {
                    itemNo = 0;
                    nextItem = 1;
                }
                if (nextItem === itemCount) {
                    nextItem = 0;
                }
                $("#bg1").fadeOut(1000);
                $("#bg2").fadeIn(1000, function () {
                    $("#bg1").css({
                        "background" : "url(images/" + config.items[+itemNo].image + ")",
                        "z-index" : 1
                    }).show(0);
                    $("#bg2").css({
                        "background" : "url(images/" + config.items[+nextItem].image + ")",
                        "z-index" : 0
                    }).hide(0, function() {enabled = true;});
                });
                $("#text").fadeOut(500, function () {
                    $(this).text(config.items[+itemNo].decsription).slideDown(400);
                });
                $("#link").fadeOut(500, function () {
                    var that = $(this);
                    setTimeout(function() {
                        that.slideDown(600).find("a").text(config.items[+itemNo].anchor).attr("href", config.items[+itemNo].url);
                    }, 600);
                });
            }
        },
        prev = function () {
            console.log('prev');
        };
        $(window).load(function () {
            next();
        });

    return {
        play : play,
        stop : stop,
        next : next,
        prev : prev
    };
};


$(function() {
    // fire functions when DOM is ready

    $(document).click(function () {
        PORTFOLIO.slideInstance.next();
    });

});

