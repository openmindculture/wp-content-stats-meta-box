(function($) {
    'use strict';

    $(document).ready(function() {
        const $metaBox = $('#content-stats-meta-box');

        if ($metaBox.length === 0) {
            return;
        }

        function updateStats(content) {
            const stats = window.ContentStatsCalculator.calculate(content);
            const format = window.ContentStatsCalculator.formatNumber;

            $metaBox.find('[data-stat="word-count"]').text(format(stats.wordCount));
            $metaBox.find('[data-stat="links-int-count"]').text(format(stats.linksIntCount));
            $metaBox.find('[data-stat="links-ext-count"]').text(format(stats.linksExtCount));
            $metaBox.find('[data-stat="paragraph-count"]').text(format(stats.paragraphCount));
            $metaBox.find('[data-stat="reading-time"]').text(stats.readingTime + ' min');
        }

        function getEditorContent() {
            if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                const editor = tinymce.get('content');
                if (editor && !editor.isHidden()) {
                    return editor.getContent();
                }
            }

            const $textarea = $('#content');
            if ($textarea.length) {
                return $textarea.val();
            }

            return '';
        }

        const debouncedUpdate = window.ContentStatsCalculator.debounce(function() {
            updateStats(getEditorContent());
        }, 500);

        $(document).on('tinymce-editor-init', function(event, editor) {
            if (editor.id === 'content') {
                editor.on('input change keyup paste NodeChange', debouncedUpdate);
                updateStats(editor.getContent());
            }
        });

        $('#content').on('input keyup paste', debouncedUpdate);

        $('#content-tmce, #content-html').on('click', function() {
            setTimeout(function() {
                updateStats(getEditorContent());
            }, 200);
        });

        setTimeout(function() {
            updateStats(getEditorContent());
        }, 500);
    });

})(jQuery);