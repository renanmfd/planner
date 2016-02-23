/*jslint browser: true, devel: true, nomen: true, unparam: true*/
/*global jQuery, Modal */

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
                            $('form', '#modal').find('input[type=text], input[type=number]').val('');
                            Modal.close();
                        }, 2000);
                        if (data.debug) {
                            $('#footer').append(data.debug);
                        }
                    },
                    beforeSend: function (ajax, plain) {
                        $('body')
                            .append('<div id="throbber" class="icon-spinner3"></div>');
                    },
                    complete: function (ajax, status) {
                        setTimeout(function () {
                            $('#throbber').remove();
                        }, 2500);
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
                            $('form', '#modal').find('input[type=text], input[type=number]').val('');
                            Modal.close();
                        }, 2000);
                        if (data.debug) {
                            $('#footer').append(data.debug);
                        }
                    },
                    beforeSend: function (ajax, plain) {
                        $('body')
                            .append('<div id="throbber" class="icon-spinner3"></div>');
                    },
                    complete: function (ajax, status) {
                        setTimeout(function () {
                            $('#throbber').remove();
                        }, 2500);
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
            }
        ],
        /** DATA-AJAX HANDLERS **/
        handlers = {
            /** HEADER box 1 **/
            sumary_lastmonth: {
                success: function (data) {
                    console.log('success!!!!', this);
                },
                error: function (ajax) {
                    console.log('ERROR');
                }
            },
            /** HEADER box 2 **/
            sumary_current_in: {
                success: function (data) {
                    var that = this;
                    console.log('success!!!!', this);
                    data.sumary.forEach(function (data, index) {
                        var $element = $('[data-type=data_' + index + ']', that);
                        $element.find('.value').text(format_money(data.value));
                        $element.find('.label').text(data.title);
                    });
                    $('[data-type=data_total]', that).find('.value').text(format_money(data.total.value));
                },
                error: function (ajax) {
                    console.log('ERROR');
                }
            },
            /** HEADER box 3 **/
            sumary_current_out: {
                success: function (data) {
                    var that = this;
                    console.log('success!!!!', this);
                    data.sumary.forEach(function (data, index) {
                        var $element = $('[data-type=data_' + index + ']', that);
                        $element.find('.value').text(format_money(data.value));
                        $element.find('.label').text(data.title);
                    });
                    $('[data-type=data_total]', that).find('.value').text(format_money(data.total.value));
                    $(that).find('.box-item .glyphicon')
                        .removeClass('glyphicon-plus-sign')
                        .addClass('glyphicon-minus-sign');
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
                console.log('AJAX sent data', element.ajax);

                $.ajax('', element.ajax);
            });
        });

        /** Test **/
        $('[data-ajax]').each(function () {
            var method = $(this).data('ajax'),
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
                    console.log('AJAX error:', ajax);
                    handlers[method].error.call(that, ajax);
                    removeThrobber(that);
                }
            });
        });
    });
    
    window.format_money = function (value, currency) {
        
        return currency + ' ' + intpart + ',' + decpart;
    }
    
    window.addThrobber = function (element) {
        var template = '<div class="throbber"><span class="glyphicon glyphicon-hourglass"></span></div>',
            $template = $(template).hide();
        $(element).css('position', 'relative');
        $($template).appendTo(element).fadeIn();
    }
    
    window.removeThrobber = function (element) {
        $(element).find('.throbber').fadeOut(function () {
            $(this).remove();
        });
    }

}(window, document, jQuery));
