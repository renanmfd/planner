/*jslint browser: true, devel: true, nomen: true, unparam: true*/
/*global jQuery, Modal, addThrobber, removeThrobber, format_money, format_date, leading_zero */

(function (window, document, $) {
    'use strict';

    var requests = [
            {
                event: 'click',
                selector: '#addIncomeSubmit',
                ajax: {
                    data: {
                        services: true,
                        action: 'add_income'
                    },
                    success: function (data) {
                        console.log('ADD INCOME', 'ajax success', data);
                        setTimeout(function () {
                            $('form', '#modal')
                                .find('input[type=text], input[type=number], textfield').val('');
                            $('form', '#modal')
                                .find('#addIncomeIsToday, #addOutcomeIsToday').attr('checked', true);
                            Modal.close();
                        }, 3000);
                        if (data.debug) {
                            $('#footer').append(data.debug);
                        }
                    },
                    beforeSend: function (ajax, plain) {
                        addThrobber('body', true);
                    },
                    complete: function (ajax, status) {
                        setTimeout(function () {
                            removeThrobber('body');
                        }, 3000);
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
                        console.log('ADD OUTCOME', 'ajax success', data);
                        setTimeout(function () {
                            $('form', '#modal')
                                .find('input[type=text], input[type=number], textfield').val('');
                            Modal.close();
                        }, 3000);
                        if (data.debug) {
                            $('#footer').append(data.debug);
                        }
                    },
                    beforeSend: function (ajax, plain) {
                        addThrobber('body', true);
                    },
                    complete: function (ajax, status) {
                        setTimeout(function () {
                            removeThrobber('body');
                        }, 3000);
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
        ],
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
                    $('[data-type=data_total]', that).find('.value').text(format_money(data.total));
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
                    data.result.forEach(function (entry, index) {
                        var $clone = $('#template', that).clone().removeAttr('id');
                        $clone.addClass(entry.type);
                        $clone.find('.date').text(format_date(entry.date));
                        $clone.find('.title').text(entry.title);
                        $clone.find('.desc').text(entry.description);
                        $clone.find('.value').text(format_money(entry.value));
                        $clone.find('.actions').data('id', entry.id);

                        $('.panel-body', that).append($clone);
                    });
                    $('#template', that).hide();
                },
                error: function (ajax) {
                    console.log('ERROR');
                }
            }
        };

    /**
     * Update BOX content with an ajax call.
     * @param {String} name Name of the container to update. If not set, all will be updated.
     */
    function ajaxUpdateBox(name) {
        var selector = name ? '[data-ajax-box="' + name + '"]' : '[data-ajax-box]';

        /** Test **/
        $(selector).each(function () {
            var method = $(this).data('ajax-box'),
                params = {},
                that = this;
            console.log('method', method);

            $(this).each(function () {
                $.each(this.attributes, function () {
                    if (this.specified) {
                        params[this.name] = this.value;
                    }
                });
            });
            params['date'] = {
                'year': $('#dateYear').val(),
                'month': $('#dateMonth').val()
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

    /**
     * Update TABS content with an ajax call.
     * @param {String} name Name of the container to update. If not set, all will be updated.
     */
    function ajaxUpdateTab(name) {
        var selector = name ? '[data-ajax-tab="' + name + '"]' : '[data-ajax-tab]';

        /** Test **/
        $(selector).each(function () {
            var method = $(this).data('ajax-tab'),
                params = {},
                that = this;
            console.log('method', method);

            $(this).each(function () {
                $.each(this.attributes, function () {
                    if (this.specified) {
                        params[this.name] = this.value;
                    }
                });
            });
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
                console.log('AJAX sent data', element.ajax);

                $.ajax('', element.ajax);
            });
        });

        // Update BOX information on page load.
        ajaxUpdateBox();

        // Update TAB information on click.
        $('#tabsHead li a', '#tabs').on('click', function () {
            var name = $(this).data('ajax-tab');
            ajaxUpdateTab(name);
        });
    });

    /** HELPER FUNCTION (global) **/

    window.format_money = function (value, currency) {
        currency = currency || 'R$';
        return currency + ' ' + value;
        /*var intpart = 0,
            intpart_array = [],
            decpart = 0,
            value_int = Math.trunc(Math.round(value * 100));

        currency = currency || 'R$';
        // Decimal
        decpart = Math.trunc(value_int % 100).toString();
        decpart = leading_zero(decpart, 2);

        // Integer
        intpart = Math.trunc(value_int / 100).toString();
        while (intpart.length > 0) {
            intpart_array.push(intpart.substr(-3, 3));
            intpart = intpart.substr(0, intpart.length - 3);
        }
        intpart_array.reverse();

        return currency + ' ' + intpart_array.join('.') + ',' + decpart;*/
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
