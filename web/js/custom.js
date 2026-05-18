$(function () {
    "use strict";

    $('[data-toggle="tooltip"]').tooltip();

    $("div[class*='index'] input[type='text'], div[class*='not-placeholder'] table input[type='text']").attr('placeholder', "\uf002");

    var path = window.location.pathname;
    var search = window.location.search;
    var pathSplit = path.split("/");

    var url = pathSplit[3] === 'admin' ? pathSplit[4] + '/' : pathSplit[3] + '/';

    if (url != '/') {
        var navItem = $('nav a[href*="' + url + '"]');
        navItem.addClass('active');
        var treeview = navItem.parents('.has-treeview');
        treeview.addClass('menu-open');
        treeview.children().addClass('active');
    } else {
        var navItem = $('.nav-item.home a');
        navItem.addClass('active');
    }

    $('.btn-dark-mode').click(function (event) {
        event.preventDefault();
        let darkIsActive = document.cookie.includes('dark=true');
        $('body').toggleClass('dark-mode');
        document.cookie = "dark=" + !darkIsActive + ";path=/"
    });

    function checkDarkMode() {
        let darkIsActive = document.cookie.includes('dark=true');

        if (darkIsActive) {
            setTimeout(function () {
                $('.loading-dark-mode').fadeOut(600);
            }, 1000);
            $('body').addClass('dark-mode');
        } else {
            $('body').removeClass('dark-mode');
        }
    }

    checkDarkMode();
});
