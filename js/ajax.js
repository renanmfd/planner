/*jslint browser: true, devel: true, nomen: true, unparam: true*/
/*global jQuery, Modal, addThrobber, removeThrobber, format_money, format_date, leading_zero */

(function (window, document, $) {
    'use strict';

    var requests,
        handlers;

    /**
     * Reset the entry forms to default state.
     * @param {jQuery} $form Form object.
     */
    function reset_form($form) {
        //$('.checkbox-use-preset input', $form).prop('checked', false);
        //$('.checkbox-is-monthly input', $form).prop('checked', false);
        //$('.checkbox-is-today input', $form).prop('checked', true);
        //$('.input-date', $form).hide();
        //$('.input-monthly', $form).hide();
        //$('.input-preset', $form).hide();
        $('.reset', $form).val('');
    }

    /**
     * Update BOX content with an ajax call.
     * @param {String} name Name of the container to update. If not set, all will be updated.
     */
    function ajaxUpdate(type, name) {
        var selector = name ? '[data-ajax-' + type + '="' + name + '"]' : '[data-ajax-' + type + ']';

        /** Test **/
        $(selector).each(function () {
            var method = $(this).data('ajax-' + type),
                params = {},
                that = this;

            params.date = {
                year: $('#dateYear').val(),
                month: $('#dateMonth').val()
            };
            //console.log('data-ajax', this, method, params);

            $.ajax('', {
                method: 'POST',
                data: {
                    services: true,
                    action: method,
                    params: params
                },
                beforeSend: function () {
                    addThrobber(that);
                },
                success: function (data) {
                    console.log('AJAX success:', method, data);
                    handlers[method].success.call(that, data.data);
                    removeThrobber(that);
                },
                error: function (ajax) {
                    console.log('AJAX error:', method, ajax);
                    handlers[method].error.call(that, ajax);
                    removeThrobber(that);
                }
            });
        });
    }

    /** CLICK REQUEST HANDLERS **/
    requests = [
        {
            event: 'click',
            selector: '#addIncomeSubmit',
            ajax: {
                data: {
                    services: true,
                    action: 'add_income'
                },
                success: function (data) {
                    var active_tab = $('.panel.active', '#tabs').data('ajax-tab');
                    reset_form($('form', '#modal'));
                    Modal.close();
                    if (data.debug) {
                        $('#footer').append(data.debug);
                    }
                    ajaxUpdate('box', 'sumary_current_in');
                    ajaxUpdate('tab', active_tab);
                },
                beforeSend: function (ajax, plain) {
                    addThrobber('body', true);
                },
                complete: function (ajax, status) {
                    removeThrobber('body');
                }
            },
            getParams: function ($context) {
                return {
                    isMonthly: $context.find('#addIncomeIsMonthly').is(':checked'),
                    isToday: $context.find('#addIncomeIsToday').is(':checked'),
                    monthly: $context.find('#addIncomeMonthly').val(),
                    date: $context.find('#addIncomeDate').val(),
                    name: $context.find('#addIncomeName').val(),
                    description: $context.find('#addIncomeDescription').val(),
                    value: $context.find('#addIncomeValue').val(),
                    save: $context.find('#addIncomeSavePreset').is(':checked')
                };
            }
        },
        {
            event: 'click',
            selector: '#addOutcomeSubmit',
            ajax: {
                data: {
                    services: true,
                    action: 'add_outcome'
                },
                success: function (data) {
                    var active_tab = $('.panel.active', '#tabs').data('ajax-tab');
                    reset_form($('form', '#modal'));
                    Modal.close();
                    if (data.debug) {
                        $('#footer').append(data.debug);
                    }
                    ajaxUpdate('box', 'sumary_current_out');
                    ajaxUpdate('tab', active_tab);
                },
                beforeSend: function (ajax, plain) {
                    addThrobber('body', true);
                },
                complete: function (ajax, status) {
                    removeThrobber('body');
                }
            },
            getParams: function ($context) {
                return {
                    isMonthly: $context.find('#addOutcomeIsMonthly').is(':checked'),
                    isToday: $context.find('#addOutcomeIsToday').is(':checked'),
                    monthly: $context.find('#addOutcomeMonthly').val(),
                    date: $context.find('#addOutcomeDate').val(),
                    name: $context.find('#addOutcomeName').val(),
                    description: $context.find('#addOutcomeDescription').val(),
                    value: $context.find('#addOutcomeValue').val(),
                    save: $context.find('#addOutcomeSavePreset').is(':checked')
                };
            }
        },
        {
            event: 'click',
            selector: '#menuLogout',
            ajax: {
                data: {
                    services: true,
                    action: 'logout'
                },
                success: function (data) {
                    location.reload();
                },
                beforeSend: function (ajax, plain) {
                    addThrobber('body', true);
                },
                complete: function (ajax, status) {
                    removeThrobber('body');
                }
            },
            getParams: function ($context) {
                return {};
            }
        }
    ];

    /** DATA-AJAX HANDLERS **/
    handlers = {
        /** HEADER box 1 **/
        sumary_lastmonth: {
            success: function (data) {
                var that = this;
                data.sumary.forEach(function (data, index) {
                    var $element = $('[data-type=data_' + index + ']', that);
                    $element.find('.value').text(format_money(data.value));
                    $element.find('.label').text(data.title);
                });
                $('[data-type=data_total]', that).find('.value').text(format_money(data.total.total));
            },
            error: function (ajax) {
                console.log('ERROR');
            }
        },
        /** HEADER box 2 **/
        sumary_current_in: {
            success: function (data) {
                var that = this;
                data.sumary.forEach(function (data, index) {
                    var $element = $('[data-type=data_' + index + ']', that);
                    $element.find('.value').text(format_money(data.value));
                    $element.find('.label').text(data.title);
                });
                $('[data-type=data_total]', that).find('.value').text(format_money(data.total));
            },
            error: function (ajax) {
                console.log('ERROR');
            }
        },
        /** HEADER box 3 **/
        sumary_current_out: {
            success: function (data) {
                var that = this;
                data.sumary.forEach(function (data, index) {
                    var $element = $('[data-type=data_' + index + ']', that);
                    $element.find('.value').text(format_money(data.value));
                    $element.find('.label').text(data.title);
                });
                $('[data-type=data_total]', that).find('.value').text(format_money(data.total));
                $(that).find('.box-item .glyphicon')
                    .removeClass('glyphicon-plus-sign')
                    .addClass('glyphicon-minus-sign');
            },
            error: function (ajax) {
                console.log('ERROR');
            }
        },
        /** CONTENT tab 1 **/
        month_list: {
            success: function (data) {
                var that = this;
                $('.entry', that).not('#template').remove();
                data.result.forEach(function (entry, index) {
                    var $clone = $('#template', that).clone().removeAttr('id');
                    $clone.addClass(entry.type).removeAttr('style');
                    $clone.find('.date').text(format_date(entry.date));
                    $clone.find('.title').text(entry.title);
                    $clone.find('.desc').text(entry.description);
                    $clone.find('.value').text(format_money(entry.value));
                    $clone.find('.actions').data('id', entry.id);

                    $('.entry-wrapper', that).append($clone);
                });
                //console.log('data month_list', data);
                $('.total-income .value', that).text(format_money(data.total.in));
                $('.total-outcome .value', that).text(format_money(data.total.out));
                $('.total-total .value', that).text(format_money(data.total.total));
                $('#template', that).hide();
            },
            error: function (ajax) {
                console.log('ERROR');
            }
        }
    };

    $(document).ready(function () {
        // Ajax requests.
        requests.forEach(function (element, index) {
            $(element.selector).on(element.event, function (event) {
                var $form = $(this).closest('form'),
                    valid = [];

                event.preventDefault();

                // Validation.
                $form.find('.form-input.required input').each(function () {
                    if ($(this).val() === '') {
                        valid.push(this);
                        $(this).addClass('invalid').on('focus', function () {
                            $(this).removeClass('invalid');
                        });
                    }
                });
                if (valid.length > 0) {
                    alert('Required field not filled.');
                    return true;
                }
                // End validation.

                element.ajax.method = 'POST';
                element.ajax.data.params = element.getParams($form);
                element.ajax.error = element.ajax.error || function (ajax) {
                    console.error('AJAX ERROR', ajax);
                };
                //console.log('AJAX sent data', element.ajax);

                $.ajax('', element.ajax);
            });
        });

        // Update BOX information on page load.
        ajaxUpdate('box');

        // Update TAB information on click.
        $('#tabsHead li a', '#tabs').on('click', function () {
            var name = $(this).data('ajax-tab');
            ajaxUpdate('tab', name);
        });
    });

    /** HELPER FUNCTION (global) **/

    window.format_money = function (value, currency) {
        currency = currency || 'R$';
        return currency + ' ' + value;
    };

    window.format_date = function (timestamp) {
        var date = new Date(parseInt(timestamp, 10) * 1000);
        return leading_zero(date.getDate());
    };

    window.leading_zero = function (value, size) {
        var aux = value.toString();
        size = size || 2;
        while (aux.length < size) {
            aux = '0' + aux;
        }
        return aux;
    };

    window.addThrobber = function (element, fixed) {
        var template = '<div class="throbber"><span class="glyphicon glyphicon-hourglass"></span></div>',
            $template = $(template).hide();

        if (fixed) {
            $template.css('position', 'fixed');
        }
        $(element).css('position', 'relative');
        $($template).appendTo(element).fadeIn();
    };

    window.removeThrobber = function (element) {
        $(element).find('.throbber').fadeOut(function () {
            $(this).remove();
        });
    };

}(window, document, jQuery));
