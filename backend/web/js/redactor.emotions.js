if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.emotions = function () {
    return {
        init: function () {
            var items = [
                [':grinning:', ':grinning:'],
                [':slight_smile:', ':slight_smile:'],
                [':yum:', ':yum:'],
                [':heart_eyes:', ':heart_eyes:'],
                [':hearts:', ':hearts:'],
                [':blue_heart:', ':blue_heart:'],
                [':yellow_heart:', ':yellow_heart:'],
                [':green_heart:', ':green_heart:'],
                [':purple_heart:', ':purple_heart:']
            ];

            this.emotions.template = $('<ul id="redactor-modal-list" class="redactor-emojione-list">');

            for (var i = 0; i < items.length; i++) {
                var li = $('<li>');
                var a = $('<a href="#" class="redactor-emojione-link">').text(items[i][0]);
                var div = $('<div class="redactor-emojione">').hide().html(items[i][1]);

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

            $('#redactor-modal-list').find('.redactor-emojione-link').each($.proxy(this.emotions.load, this));
            
            this.selection.save();
            this.modal.show();
        },
        load: function (i, s) {
            $(".redactor-emojione-link").each(function() {
                var original = $(this).html();
                // use .shortnameToImage if only converting shortnames (for slightly better performance)
                var converted = emojione.toImage(original);
                $(this).html(converted);
            });
            
            $(s).on('click', $.proxy(function (e) {
                e.preventDefault();
                this.emotions.insert($(s).next().html());

            }, this));
        },
        insert: function (html) {
            this.selection.restore();
            
            emojione.unicodeAlt = false;
            var html = emojione.toImage(html);

            this.insert.htmlWithoutClean(html);
            
            this.modal.close();
            this.observe.load();
        }
    };
};

