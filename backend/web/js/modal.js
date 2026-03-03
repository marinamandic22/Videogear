
var modal = {}; // namespace

$ = jQuery.noConflict();

modal = (function ($) {
    return {
        instance: null,
        id: null,
        loadingOverlayId: '#main-loading-overlay',
        init: function () {
            modal.id = 'main-modal';
            modal.instance = $('#' + modal.id);
            modal.instance.on('hidden.bs.modal', function () {
                if (window.tinyMCE) {
                    tinyMCE.EditorManager.remove('.modal-content *');
                }
                modal.instance.find('.modal-content').empty();
            });

            $(document)
                .on('click', '.btn-modal-control-close', this.modalClose)
                .on('click', '.btn-modal-control', this.modalControl)
                .on('click', '.btn-modal-control-expand', this.modalControlSize)
                .on('click', '.btn-modal-control-minimise', this.modalControlSize)
                .on('click', '.btn-modal-control-submit', this.modalControlSubmit)
                .on('click', '.btn-modal-control-confirm-submit', this.modalControlConfirmSubmit);
        },
        /**
         * displays a modal with url
         */
        modalControl: function (e) {
            e.preventDefault();
            var self = $(this);

            if (self.hasClass('disabled')) {
                return false;
            }
            var url = self.attr('data-href') || self.attr('href');
            var data = self.data('params') || '';
            var options = {backdrop: 'static'};
            if (self.attr('data-height')) {
                options.height = self.attr('data-height');
                if (modal.instance.data('modal')) {
                    modal.instance.data('modal').options.height = self.attr('data-height');
                }
            }
            if (self.attr('data-width')) {
                options.width = self.attr('data-width');
                if (modal.instance.data('modal')) {
                    modal.instance.data('modal').options.width = self.attr('data-width');
                }
            }
            if (self.attr('data-size')) {
                modal.instance.find('.modal-dialog').removeClass('modal-xl modal-lg modal-md modal-sm modal-xs').addClass(self.attr('data-size'));
            }

            modal.instance.find('.modal-content').attr('class', 'modal-content');
            if (self.attr('data-content-class')) {
                modal.instance.find('.modal-content').addClass(self.attr('data-content-class'));
            }

            main.ui.buttonLoading(self, true);

            setTimeout(function () {
                var modalContent = modal.instance.find('.modal-content');
                modalContent.load(url, data, function () {
                    modal.instance.modal(options);
                    main.ui.buttonLoading(self, false);
                    $(document).trigger('modal-opened', [modal]);
                    main.ui.initTooltips();
                });
            }, 200);
            return false;
        },
        modalControlSize: function () {
            var modalClass = $(this).data('size');
            modal.instance.find('.modal-dialog').attr('class', 'modal-dialog');
            modal.instance.find('.modal-dialog').addClass(modalClass);

            return true;
        },
        openModal: function(url) {
            this.modalControl.call($('<a href="' + url + '">'), {preventDefault: $.noop});
        },
        /**
         * closes a modal
         */
        modalClose: function () {
            modal.instance.modal('hide');
            $(document).trigger('modal-hidden', [modal]);
        },
        /**
         * submits a form within a modal
         * @return {Boolean}
         */
        modalControlSubmit: function (e) {
            e.preventDefault();
            var self = $(this);

            if (self.hasClass('disabled')) {
                return false;
            }
            var frm = this.form ? $(this.form) : self.data('form-id') ? $('#' + self.data('form-id')) : self.closest('.modal-content').find('form');

            var hide = self.data('hide') !== undefined? self.data('hide') : true;

            main.ui.buttonLoading(self, true);

            $.ajax({
                url: frm.attr('action'),
                type: 'post',
                data: new FormData(frm[0]),
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (data) {
                    if (!data.success) {
                        main.yiiactiveform.updateInputs(frm, data.errors || []);
                        hide = false;
                    }

                    if (hide) {
                        modal.instance.modal('hide');
                    }

                    modal.notify(data.message, data.success ? 'success' : 'error');
                    main.ui.buttonLoading(self, false);

                    $(document).trigger('modal-submitted', [data, self, frm, {
                        'grid_id': frm.data('grid-id'),
                        'pjax_id': frm.data('pjax-id')
                    }]);
                },
                error: function (XHR) {
                    modal.instance.find('.modal-content').html(XHR.responseText);
                    main.ui.buttonLoading(self, false);
                }
            });
            return false;
        },
        /**
         * submits a form within a modal
         * @return {Boolean}
         */
        modalControlConfirmSubmit: function (e) {
            e.preventDefault();
            var self = $(this), data = {},
                confirmMsg = self.data('confirm-msg') || 'Are you sure?',
                confirmType = self.data('confirm-type') || '';

            if (self.hasClass('disabled')) {
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

                var frm = (this && this.form) ? $(this.form) : self.data('form-id') ? $('#' + self.data('form-id')) : self.closest('.modal-content').find('form');

                if (frm.length < 1) {
                    frm = self.closest('form');
                }

                var hide = self.data('hide') !== undefined? self.data('hide') : true;

                main.ui.buttonLoading(self, true);

                $.ajax({
                    url: frm.attr('action'),
                    type: 'post',
                    data: new FormData(frm[0]),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (data) {
                        if (!data.success) {
                            main.yiiactiveform.updateInputs(frm, data.errors || []);
                            hide = false;
                        }

                        if (hide) {
                            modal.instance.modal('hide');
                        }

                        modal.notify(data.message, data.success ? 'success' : 'error');
                        main.ui.buttonLoading(self, false);

                        $(document).trigger('modal-submitted', [data, self, frm, {
                            'grid_id': frm.data('grid-id'),
                            'pjax_id': frm.data('pjax-id')
                        }]);
                    },
                    error: function (XHR) {
                        modal.instance.find('.modal-content').html(XHR.responseText);
                        main.ui.buttonLoading(self, false);
                    }
                });
                return false;
            };

            if (confirmMsg) {
                main.ui.confirm(confirmMsg, confirmType).then(sendRequest);
                return false;
            }

            sendRequest({value: true});

            return false;
        },
        notify: function (message, type, position) {

            main.ui.notify(message, type, position);

            return this;
        }
    };
})($);