if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.emotions = function () {
    return {
        init: function () {
            var items = [
                ['Lorem ipsum...1', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'],
                ['Red label2', '<span class="label-red">Label</span>']
            ];

            this.emotions.template = $('<ul id="redactor-modal-list">');

            for (var i = 0; i < items.length; i++) {
                var li = $('<li>');
                var a = $('<a href="#" class="redactor-clip-link">').text(items[i][0]);
                var div = $('<div class="redactor-clip">').hide().html(items[i][1]);

                li.append(a);
                li.append(div);
                this.emotions.template.append(li);
            }

            this.modal.addTemplate('emotions', '<section>' + this.utils.getOuterHtml(this.emotions.template) + '</section>');

            var button = this.button.add('emotions', 'Emotions');
            this.button.addCallback(button, this.emotions.show);

        },
        show: function () {
            this.modal.load('emotions', 'Insert Emotion', 400);

            this.modal.createCancelButton();

            $('#redactor-modal-list').find('.redactor-clip-link').each($.proxy(this.emotions.load, this));

            this.selection.save();
            this.modal.show();
        },
        load: function (i, s) {
            $(s).on('click', $.proxy(function (e) {
                e.preventDefault();
                this.emotions.insert($(s).next().html());

            }, this));
        },
        insert: function (html) {
            this.selection.restore();
            this.insert.html(html);
            this.modal.close();
            this.observe.load();
        }
    };
};

