/*jslint browser: true, devel: true, nomen: true, unparam: true*/
/*global jQuery, Modal */

(function (window, document, $, Modal) {
    'use strict';

    var clickEvents = [
            { // MENU - Collapse.
                selector: '#menuCollapse',
                handler: function (event) {
                    event.preventDefault();
                    $('#sidebar').toggleClass('collapsed');
                }
            },
            { // MENU - Income Add.
                selector: '#menuAddIncome',
                handler: function (event) {
                    var $body = $(this).parent().find('.menu-body'),
                        $form = $body.find('form').detach();
                    event.preventDefault();
                    Modal.setTitle('Add Income').setBody($form).open(function () {
                        $form.detach().appendTo($body);
                    });
                }
            },
            { // MENU - Outcome add.
                selector: '#menuAddOutcome',
                handler: function (event) {
                    var $body = $(this).parent().find('.menu-body'),
                        $form = $body.find('form').detach();
                    event.preventDefault();
                    Modal.setTitle('Add Outcome').setBody($form).open(function () {
                        $form.detach().appendTo($body);
                    });
                }
            },
            { // TAB - Control tabs on main container.
                selector: '#tabsHead li a',
                handler: function (event) {
                    var tab = $(this).attr('href');
                    event.preventDefault();
                    $('.tab-item', '#tabs').addClass('hidden');
                    $(tab, '#tabs').removeClass('hidden');
                    console.log('Tab default click', this);
                }
            },
            { // DATE WIDGET - Add and subtract date buttons.
                selector: '#dateWidget .date-button',
                handler: function (event) {
                    var month = parseInt($('#dateMonth', '#dateWidget').val(), 10),
                        year;

                    event.preventDefault();
                    if ($(this).val() === 'Increase') {
                        month += 1;
                        if (month > 12) {
                            month = 1;
                            year = parseInt($('#dateYear', '#dateWidget').val(), 10);
                            $('#dateYear', '#dateWidget').val(year + 1);
                        }
                    } else {
                        month -= 1;
                        if (month < 1) {
                            month = 12;
                            year = parseInt($('#dateYear', '#dateWidget').val(), 10);
                            $('#dateYear', '#dateWidget').val(year - 1);
                        }
                    }
                    $('#dateMonth', '#dateWidget').val(month);
                    console.log('dateWidget button', this);
                }
            },
            { // BOX - Header collapse.
                selector: '.box .box-header a',
                handler: function (event) {
                    event.preventDefault();
                    if ($(this).closest('.box').toggleClass('collapsed').hasClass('collapsed')) {
                        $(this).closest('.box').find('.box-body').slideUp();
                    } else {
                        $(this).closest('.box').find('.box-body').slideDown();
                    }
                }
            }
        ],
        // FORM EVENTS ================================================== //
        formEvents = [
            {
                event: 'change',
                selector: '.checkbox-use-preset input',
                handler: function (event) {
                    var $form = $(this).closest('form'),
                        type = $form.find('input[name=type]').val();
                    $form.find('.input-preset').slideToggle();

                    if (this.checked) {
                        $('#add' + type + 'Name', $form).attr('disabled', true);
                        $('#add' + type + 'Description', $form).attr('disabled', true);
                        $('#add' + type + 'IsMonthly', $form).attr('disabled', true);
                        $('#add' + type + 'SavePreset', $form).parent().slideUp();
                        $('.input-monthly', $form).slideUp();
                        $('#add' + type + 'IsMonthly', $form).removeAttr('checked');
                    } else {
                        $('#add' + type + 'Name', $form).removeAttr('disabled');
                        $('#add' + type + 'Description', $form).removeAttr('disabled');
                        $('#add' + type + 'IsMonthly', $form).removeAttr('disabled');
                        $('#add' + type + 'SavePreset', $form).parent().slideDown();
                    }
                }
            },
            {
                event: 'change',
                selector: '.checkbox-is-monthly input',
                handler: function (event) {
                    var $form = $(this).closest('form'),
                        type = $form.find('input[name=type]').val();
                    $form.find('.input-monthly').slideToggle();

                    if (this.checked) {
                        $('#add' + type + 'SavePreset', $form).parent().slideUp();
                    } else {
                        $('#add' + type + 'SavePreset', $form).parent().slideDown();
                    }
                }
            },
            {
                event: 'change',
                selector: '.checkbox-is-today input',
                handler: function (event) {
                    var $form = $(this).closest('form');
                    $form.find('.input-date').slideToggle();
                }
            },
            {
                event: 'click',
                selector: 'input[type=submit]',
                handler: function (event) {
                    event.preventDefault();
                }
            }
        ];

    $(document).ready(function () {
        console.log('app.js is running good...');

        // Datepicker.
        $('#addIncomeDate, #addOutcomeDate').datepicker({
            dateFormat: 'dd/mm/yy'
        });

        // Click events.
        clickEvents.forEach(function (element, index) {
            $(element.selector).on('click', element.handler);
        });

        // Form events.
        formEvents.forEach(function (element, index) {
            $(element.selector).on(element.event, element.handler);
        });

    });

}(window, document, jQuery, Modal));
