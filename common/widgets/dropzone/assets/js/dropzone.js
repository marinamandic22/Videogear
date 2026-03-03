

let dropzone = (function ($) {
    return {
        instances: [],
        id: 1,
        initialize: function (config, options) {
            let self = {...this};
            self.id = dropzone.id++;
            self.config = config;

            let defaults = {
                autoDiscover: false,
                sending: function (file, xhr, formData) {
                    formData.append(yii.getCsrfParam(), yii.getCsrfToken());
                },
                successmultiple: function (files, xhr) {
                    if (xhr.success !== true) {
                        return;
                    }
                    $.each(files, function (key) {
                        files[key].id = xhr.data[key].image_id;
                        self.config.items.push({
                            id: xhr.data[key].image_id
                        });
                    });
                    self.updateInput();
                },
                removedfile: function (file) {
                    self.config.items = self.config.items.filter(function (item) {
                        return item.id !== file.id;
                    });

                    $(file.previewElement).remove();
                    self.updateInput();

                    if (self.dropzone.options.maxFiles > self.config.items.length) {
                        $(self.dropzone.element).removeClass('dz-max-files-reached');
                    }

                    self.refreshMaxFiles();
                },
                maxfilesexceeded: function (file) {
                    file.previewElement.remove();
                    main.ui.notify(self.dropzone.options.dictMaxFilesExceeded, 'error');
                }
            };

            self.options = $.extend({}, defaults, options);
            $(self.config.target).addClass('dropzone');
            self.dropzone = new Dropzone(self.config.target, self.options);

            self.initExistingItems();
            self.updateInput();

            if (self.config.enableSorting) {
                self.initializeSortablePlugin();
            }

            dropzone.instances.push(self);
        },
        updateInput: function () {
            let self = this;
            let input = $(self.config.input);
            let items = self.config.items;
            let inputValue = [];

            if (input.length < 1) {
                return;
            }

            $.each(items, function (key, value) {
                inputValue.push(value.id);
            });

            self.config.items.reverse();
            $.each(self.config.items, function (key, item) {
                let imageElements = $(self.dropzone.element).children();
                let index = imageElements.length - (key + 1);
                $(imageElements[index]).attr('data-id', item.id);
            });
            self.config.items.reverse();

            input.val(JSON.stringify(inputValue));
        },
        initExistingItems: function () {
            var self = this;

            let itemCount = self.config.items.length;
            self.dropzone.options.maxFiles = self.dropzone.options.maxFiles - itemCount;

            $.each(self.config.items, function (key, file) {
                self.dropzone.files.push(file);
                self.dropzone.emit('addedfile', file);
                self.dropzone.emit('complete', file);
                self.dropzone.emit('thumbnail', file, file.url);
            });
        },
        refreshMaxFiles: function () {
            let self = this;
            let initialMaxFiles = self.dropzone.options.maxFiles + self.config.initialItems.length;
            self.config.initialItems = self.config.initialItems.filter(x => self.config.items.filter(y => y.id == x.id).length > 0);
            self.dropzone.options.maxFiles = initialMaxFiles - self.config.initialItems.length;
        },
        initializeSortablePlugin: function () {
            let self = this;
            $(self.config.target).sortable({
                containment: 'parent',
                items: '> div.dz-preview',
                tolerance: 'pointer',
                cursor: 'move',
                delay: 150,
                update: function (e, ui) {
                    self.updateItemsOrder();
                }
            });
        },
        updateItemsOrder: function () {
            let self = this;
            let updatedItems = [];
            let imageElements = $(self.dropzone.element).children('div.dz-preview');

            $.each(imageElements.get(), function (key, element) {
                let imageId = $(element).data('id');
                let item = self.config.items.find(x => x.id === parseInt(imageId));
                updatedItems.push(item);
            });

            self.config.items = updatedItems;
            self.updateInput();
        }
    };
})(jQuery);


