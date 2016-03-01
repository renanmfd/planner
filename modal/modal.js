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
        callbackClose = false;

    function onClose(callback) {
        callbackClose = callback || false;
    }

    function open(callback) {
        onClose(callback);
        if (!$modalContainer.hasClass('open')) {
            $modalContainer.addClass('open');
            $modalContainer.fadeIn();
        }
    }

    function close() {
        $modalContainer.removeClass('open');
        $modalContainer.fadeOut(400, function () {
            if (callbackClose) {
                callbackClose();
            }
        });
    }

    function clean() {
        //$modalContainer.removeClass('open');
        if (callbackClose) {
            callbackClose();
        }
        setTitle('');
        setBody('');
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
        open: function (callback) {
            open(callback);
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
        },
        clean: function () {
            clean();
            return this;
        },
        // Events
        onClose: function (callback) {
            onClose(callback);
            return this;
        }
    };
}(jQuery));
