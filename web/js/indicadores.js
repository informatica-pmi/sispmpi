$("document").ready(function() {
    $("#form").on("pjax:end", function() {
        $.pjax.reload({container:"#gridview"});  //Reload GridView
    });

    $('.float-number span').each(function () {
        $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
        }, {
            duration: 1000,
            easing: 'swing',
            step: function (now) {
                $(this).text(this.Counter.toFixed(2));
            }
        });
    });

    $('.integer-number span').each(function () {
        $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
        }, {
            duration: 1000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });

    $('.progress-bar').animate({
        width: '0%',
    }, 100, function () {
        $(this).animate({width: $(this).attr('aria-valuenow') + '%'})
    });
});


