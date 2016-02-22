/*jslint browser: true, devel: true, nomen: true, unparam: true*/
/*global jQuery */

var Modal = (function ($) {
    'use strict';

    var selector = '#modal',
        $modalContainer = $(selector),
        $modalBg = $('#modalBg', selector),
        $modalTitle = $('#modalHeader .title', selector),
        $modalBody = $('#modalBody', selector),
        $modalClose = $('#modalClose', selector),
        callback = false;

    function open(onClose) {
        callback = onClose || false;
        if (!$modalContainer.hasClass('open')) {
            $modalContainer.addClass('open');
            $modalContainer.fadeIn();
        }
    }

    function close() {
        $modalContainer.removeClass('open');
        $modalContainer.fadeOut(400, function () {
            if (callback) {
                callback();
            }
        });
    }

    function setTitle(title) {
        $modalTitle.text(title);
    }

    function setBody(body) {
        $modalBody.empty().append(body);
    }

    function getBody() {
        return $modalBody.html();
    }

    $modalClose.on('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        close();
    });

    $modalBg.on('dblclick', function (event) {
        event.preventDefault();
        event.stopPropagation();
        close();
    });

    return {
        open: function (onClose) {
            open(onClose);
        },
        close: function () {
            close();
        },
        setTitle: function (title) {
            setTitle(title);
            return this;
        },
        setBody: function (body) {
            setBody(body);
            return this;
        },
        getBody: function () {
            getBody();
        },
        content: function (title, body) {
            setTitle(title);
            setBody(body);
            return this;
        }
    };
}(jQuery));
