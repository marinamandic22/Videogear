var main = {}; // namespace

$ = jQuery.noConflict();

$(function () {
    main.ui.init();
});

/**
 * main namespace for helper declarative methods
 */

main.ui = (function ($) {

    return {
        loadingIcon: '<i class="fa fa-spinner fa-spin"></i> ',
        defaultConfirmMessage: 'Do you wish to delete this item?',
        pjaxLoader: '<div class="pjax-loader-container"><i class="fa fa-spinner fa-spin fa-2x"></i></div>',
        messageDuration: 3000,
        color: {
            primary: '#2a3f54',
            secondary: '#ededed',
            danger: '#ff2851',
            success: '#26b99a',
        },


        confirm: function (message, type, title) {
            type = type || 'question';

            return Swal.fire({
                title: title,
                html: message,
                icon: type,
                showCancelButton: true,
                confirmButtonColor: this.color.primary,
                cancelButtonColor: this.color.secondary,
                confirmButtonText: 'Yes',
                cancelButtonText: '<span style="color: '+ this.color.primary +'">Cancel</span>'
            });
        },

        notify: function (message, type) {
            if (!message) {
                return;
            }

            Swal.fire({
                timer: main.ui.messageDuration,
                html: message,
                showConfirmButton: false,
                backdrop: false,
                customClass: {
                    container: 'flash-message flash-' + type
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            return this;
        },

        controlAjaxSubmit: function (e) {
            e.preventDefault();
            var self = $(this);

            if (self.hasClass('disabled')) {
                return false;
            }
            var frm = this.form ? $(this.form) : self.data('form-id') ? $('#' + self.data('form-id')) : self.closest('form')[0];
            var url = self.attr('data-href') || frm.attr('action');

            main.ui.buttonLoading(self, true);

            $.ajax({
                url: url,
                type: 'post',
                data: frm.serialize(),
                dataType: 'json',
                success: function (data) {
                    main.ui.notify(data.message, data.success ? 'success' : 'error');
                    main.ui.buttonLoading(self, false);

                    self.trigger('ajax-form-submitted', [data, self, frm]);
                },
                error: function (XHR) {
                    frm.parent().html(XHR.responseText);
                    main.ui.buttonLoading(self, false);
                }
            });
            return false;
        },

        controlPjaxAction: function () {
            var self = $(this), data = {},
                pjaxId = self.data('pjax-id'),
                confirmMsg = self.data('confirm-msg') || false,
                confirmType = self.data('confirm-type') || '',
                type = self.data('type') || 'get',
                url = self.prop('href') || self.attr('data-href');

            if (self.hasClass('disabled') || !url) {
                return false;
            }

            main.ui.buttonLoading(self, true);

            $.each(self.data(), function (key, value) {
                if (key.indexOf('param') === 0) {
                    key = key.slice(5);
                    key = key.charAt(0).toLowerCase() + key.slice(1);
                    data[key] = value;
                }
            });

            var sendRequest = function (confirmResponse) {
                if (confirmResponse.value !== true) {
                    main.ui.buttonLoading(self, false);
                    return false;
                }

                $.ajax({
                    url: url,
                    type: type,
                    data: data,
                    dataType: 'json',
                    success: function (data) {
                        if (data.success && $('#' + pjaxId).length) {
                            $.pjax.reload({container: '#' + pjaxId, timeout: 5000});
                        }

                        main.ui.notify(data.message, data.success ? 'success' : 'error');
                        main.ui.buttonLoading(self, false);

                        $(document).trigger('pjax-action-submitted', [data, self, pjaxId]);
                    },
                    error: function (XHR) {
                        main.ui.notify(XHR.responseText, 'error');
                        main.ui.buttonLoading(self, false);
                    }
                });
            };

            if (confirmMsg) {
                main.ui.confirm(confirmMsg, confirmType).then(sendRequest);
                return false;
            }

            sendRequest({value: true});

            return false;
        },

        controlConfirm: function (e) {
            var self = $(this);

            if (self.hasClass('disabled')) {
                return false;
            }

            var msg = self.attr('data-msg') || main.ui.defaultConfirmMessage;
            var type = self.attr('data-type') || 'question';
            var url = self.prop('href') || self.attr('data-url');
            var pjaxId = self.data('pjax-id');
            var method = self.data('method') || 'POST';
            var hasLoader = self.data('loader') !== undefined ? self.data('loader') : true;
            var isJsonResponse = (self.data('json-response') == 1);

            main.ui.confirm(msg, type).then(result => {
                if (!result.value) {
                    return false;
                }

                if (!isJsonResponse) {
                    return document.location.href = url;
                }

                if (hasLoader) {
                    main.ui.buttonLoading(self, true);
                }

                $.ajax({
                    url: url,
                    type: method,
                    success: function (data) {
                        if (data.success && $('#' + pjaxId).length) {
                            $.pjax.reload({container: '#' + pjaxId, timeout: 5000});
                        }

                        main.ui.notify(data.message, data.success ? 'success' : 'error');
                        main.ui.buttonLoading(self, false);
                        $(document).trigger('confirm-action-submitted', [data, self, pjaxId]);
                    },
                    error: function (error) {
                        if (error.responseText) {
                            main.ui.notify(error.responseText, 'error');
                            main.ui.buttonLoading(self, false);
                        }
                    }
                });

                return true;
            });

            return false;
        },

        buttonLoading: function (btn, loading) {
            if (loading) {
                btn.attr('disabled', true).addClass('disabled');
                if (btn.hasClass('btn-loading')) {
                    btn.find('[class*="fa-"]').hide();
                    if (btn.hasClass('btn-loading-right')) {
                        btn.append(main.ui.loadingIcon);
                    } else {
                        btn.prepend(main.ui.loadingIcon);
                    }
                }
            } else {
                btn.removeAttr('disabled').removeClass('disabled');
                btn.find('.fa-spinner').remove();
                btn.find('[class*="fa-"]').show();
            }
        },

        initButtonSpinners: function () {
            $('.btn-loading').closest('form').on('submit', function () {
                var form = $(this), btn = form.find(':submit');
                main.ui.buttonLoading(btn, true);

                setTimeout(function () {
                    if (form.find('.is-invalid').length > 0) {
                        main.ui.buttonLoading(btn, false);
                    }
                }, 300);
            });
        },

        initTooltips: function () {
            $('[data-toggle="tooltip"]').tooltip();
        },

        yiiConfirm: function (message, ok, cancel) {
            main.ui.confirm(message, function (result) {
                if (result) {
                    !ok || ok();
                    return;
                }

                !cancel || cancel();
            });
        },

        removeDuplicateUrlParams: function (url) {
            var params = new URLSearchParams(url);
            var result = {},
                isArrayKey,
                arrayKey,
                prevArrayKey = '';

            for (var p of params.entries()) {
                isArrayKey = p[0].indexOf('[]', p[0].length - 2) !== -1;
                if (isArrayKey) {
                    arrayKey = p[0].substr(0, p[0].length - 2);
                    if (arrayKey === prevArrayKey) {
                        result[arrayKey].push(p[1]);
                    } else {
                        result[arrayKey] = [p[1]];
                    }
                    prevArrayKey = arrayKey;
                } else {
                    result[p[0]] = p[1];
                    prevArrayKey = '';
                }
            }

            return $.param(result);
        },

        reloadPjaxContainers: function (containers, options) {
            for (var i = 0; i < containers.length; i++) {
                var container = $(containers[i]);
                if (container.length > 0) {
                    $.pjax.reload({
                        container: containers[i],
                        ...options
                    });
                    $.pjax.xhr = null;
                } else {
                    console.warn('Unable to reload pjax. Container not found: ', containers[i]);
                }
            }
        },

        /**
         * module init
         */
        init: function () {
            modal.init();
            main.ui.initButtonSpinners();
            main.ui.initTooltips();

            $(document)
                .on('click', '.btn-control-confirm', this.controlConfirm)
                .on('click', '.btn-control-ajax-submit', this.controlAjaxSubmit)
                .on('click', '.btn-control-pjax-action', this.controlPjaxAction)
                .on('pjax:beforeSend', function (event, xhr, options) {
                    var url = new URL(options.url, window.location.origin) || null;
                    if (url && url.search) {
                        options.url = url.origin + url.pathname + '?' + main.ui.removeDuplicateUrlParams(url.search);
                    }

                    $(event.target).each(function () {
                        let self = $(event.target);
                        let target = $(self.data('pjax-loader-target'));

                        if(!target|| target.length < 1){
                            target = self;
                        }

                        target.append(main.ui.pjaxLoader);
                    });

                    return true;
                })
                .on('pjax:complete', function (event) {
                    $('[data-pjax-container]:not([data-pjax-loader="0"]), [data-pjax-loader]:not([data-pjax-loader="0"])').each(function () {
                        var self = $(this);
                        var pjaxContainer = self.data('pjax-container-id') ? $('#' + self.data('pjax-container-id')) : self;
                        if (event.target === pjaxContainer[0]) {
                            self.find('> .pjax-loader-container').remove();
                            main.ui.initTooltips();
                        }
                    });
                });

            yii.confirm = main.ui.yiiConfirm;

            $.fn.modal.Constructor.prototype.enforceFocus = $.noop;
        }
    };
})
(jQuery);


/**
 * Lot ot yiiactiveform.js code, so active form javascript can be used internaly
 *
 * @type {{submit, validate, updateSummary, addAttribute, createAttribute, updateInputs, updateInput, findInput, getValue, updateAriaInvalid}}
 */
main.yiiactiveform = (function ($) {
    return {
        submit: function ($form, $trigger) {
            var url = $form.attr('action');
            $.ajax({
                url: url,
                data: $form.serialize(),
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    var reset = $trigger.attr('data-reset');
                    if (reset) {
                        $form[0].reset();
                    }
                    $trigger.button('reset');
                },
                error: function (xhr) {
                    $trigger.button('reset');
                    var reset = $trigger.attr('data-reset');
                    if (reset) {
                        $form[0].reset();
                    }
                }
            });
        },
        validate: function ($form, $trigger) {
            var settings = $.fn.yiiactiveform.getSettings($form);
            if ($trigger.length === 0) {
                $trigger = $("div[class$='-submit'],div[class*='-submit ']");
            }
            if (!settings.validateOnSubmit) {
                return main.yiiactiveform.submit($form, $trigger);
            }
            settings.submitting = true;
            $.fn.yiiactiveform.validate($form, function (messages) {
                if ($.isEmptyObject(messages)) {
                    $.each(settings.attributes, function () {
                        $.fn.yiiactiveform.updateInput(this, messages, $form);
                    });
                    main.yiiactiveform.submit($form, $trigger);
                    return true;
                } else {
                    settings = $.fn.yiiactiveform.getSettings($form);
                    $.each(settings.attributes, function () {
                        $.fn.yiiactiveform.updateInput(this, messages, $form);
                    });
                    settings.submitting = false;
                    main.yiiactiveform.updateSummary($form, messages);
                    $trigger.button('reset');
                    return false;
                }
            });
        },
        updateSummary: function ($form, messages) {
            var settings = $.fn.yiiactiveform.getSettings($form),
                heading = '<p>Please fix the following input errors:</p>',
                list = '';

            $.each(settings.attributes, function () {
                if (messages && $.isArray(messages[this.id])) {
                    $.each(messages[this.id], function (j, message) {
                        list = list + '<li>' + message + '</li>';
                    });
                }
            });
        },
        addAttribute: function ($form, attribute) {
            var settings = $.fn.yiiactiveform.getSettings($form);
            settings.attributes.push(attribute);
            $form.data('settings', settings);
            /*
             * returns the value of the CActiveForm input field
             * performs additional checks to get proper values for checkbox / radiobutton / checkBoxList / radioButtonList
             * @param o object the jQuery object of the input element
             */
            var getAFValue = function (o) {
                var type;
                if (!o.length)
                    return undefined;
                if (o[0].tagName.toLowerCase() == 'span') {
                    var c = [];
                    o.find(':checked').each(function () {
                        c.push(this.value);
                    });
                    return c.join(',');
                }
                type = o.attr('type');
                if (type === 'checkbox' || type === 'radio') {
                    return o.filter(':checked').val();
                } else {
                    return o.val();
                }
            };
            var validate = function (attribute, forceValidate) {
                if (forceValidate) {
                    attribute.status = 2;
                }
                $.each(settings.attributes, function () {
                    if (this.value !== getAFValue($form.find('#' + this.inputID))) {
                        this.status = 2;
                        forceValidate = true;
                    }
                });
                if (!forceValidate) {
                    return;
                }

                if (settings.timer !== undefined) {
                    clearTimeout(settings.timer);
                }
                settings.timer = setTimeout(function () {
                    if (settings.submitting || $form.is(':hidden')) {
                        return;
                    }
                    if (attribute.beforeValidateAttribute === undefined || attribute.beforeValidateAttribute($form, attribute)) {
                        $.each(settings.attributes, function () {
                            if (this.status === 2) {
                                this.status = 3;
                                $.fn.yiiactiveform.getInputContainer(this, $form).addClass(this.validatingCssClass);
                            }
                        });
                        $.fn.yiiactiveform.validate($form, function (data) {
                            var hasError = false;
                            $.each(settings.attributes, function () {
                                if (this.status === 2 || this.status === 3) {
                                    hasError = $.fn.yiiactiveform.updateInput(this, data, $form) || hasError;
                                }
                            });
                            if (attribute.afterValidateAttribute !== undefined) {
                                attribute.afterValidateAttribute($form, attribute, data, hasError);
                            }
                        });
                    }
                }, attribute.validationDelay);
            };
            if (attribute.validateOnChange) {
                $form.find('#' + attribute.inputID).change(function () {
                    validate(attribute, false);
                }).blur(function () {
                    if (attribute.status !== 2 && attribute.status !== 3) {
                        validate(attribute, !attribute.status);
                    }
                });
            }
            if (attribute.validateOnType) {
                $form.find('#' + attribute.inputID).keyup(function () {
                    if (attribute.value !== getAFValue($(attribute))) {
                        validate(attribute, false);
                    }
                });
            }
        },
        createAttribute: function (model, id, name, options) {
            var defaults = {
                enableAjaxValidation: true,
                errorCssClass: "has-error",
                errorID: id + '_em_',
                hideErrorMessage: false,
                id: id,
                inputContainer: "div.form-group",
                inputID: id,
                model: model,
                name: name,
                status: 1,
                successCssClass: 'has-success',
                validateOnChange: true,
                validateOnType: false,
                validatingCssClass: 'validating',
                validationDelay: 200
            };
            return $.extend({}, defaults, options);
        },
        /**
         * Updates the error messages and the input containers for all applicable attributes
         * @param $form the form jQuery object
         * @param messages array the validation error messages
         * @param submitting whether this method is called after validation triggered by form submission
         */
        updateInputs: function ($form, messages) {
            var data = $form.data('yiiActiveForm');

            if (data === undefined) {
                return false;
            }

            $.each(data.attributes, function () {
                main.yiiactiveform.updateInput($form, this, messages);
            });
        },
        /**
         * Updates the error message and the input container for a particular attribute.
         * @param $form the form jQuery object
         * @param attribute object the configuration for a particular attribute.
         * @param messages array the validation error messages
         * @return boolean whether there is a validation error for the specified attribute
         */
        updateInput: function ($form, attribute, messages) {
            var data = $form.data('yiiActiveForm'),
                $input = main.yiiactiveform.findInput($form, attribute),
                hasError = false;

            if (!$.isArray(messages[attribute.id])) {
                messages[attribute.id] = [];
            }

            data.settings.successCssClass = ''; //disable successfully validated attributes highlight

            if ($input.length) {
                hasError = messages[attribute.id].length > 0;
                var $container = $form.find(attribute.container);
                var $error = $container.find(attribute.error);
                main.yiiactiveform.updateAriaInvalid($form, attribute, hasError);
                if (hasError) {
                    if (attribute.encodeError) {
                        $error.text(messages[attribute.id][0]);
                    } else {
                        $error.html(messages[attribute.id][0]);
                    }
                    $container.removeClass(data.settings.validatingCssClass + ' ' + data.settings.successCssClass).addClass(data.settings.errorCssClass);
                } else {
                    $error.empty();
                    $container.removeClass(data.settings.validatingCssClass + ' ' + data.settings.errorCssClass + ' ').addClass(data.settings.successCssClass);
                }
            }
            return hasError;
        },
        findInput: function ($form, attribute) {
            var $input = $form.find(attribute.input);

            if ($input.length && $input[0].tagName.toLowerCase() === 'div') {
                return $input.find('input');
            } else {
                return $input;
            }
        },
        updateAriaInvalid: function ($form, attribute, hasError) {
            if (attribute.updateAriaInvalid) {
                $form.find(attribute.input).attr('aria-invalid', hasError ? 'true' : 'false');
            }
        }
    };
})(jQuery);