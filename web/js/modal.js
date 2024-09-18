$(function () {
    "use strict";

    $("button[id^=modal]").click(function () {
        let idModal = '#' + $(this).attr('data-show');
        $(idModal).modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });

    $("div[id^=modal-][class~='modal']").on('hidden.bs.modal', function (event) {
        $(this).find('#modalContent').html('');
    });
});
