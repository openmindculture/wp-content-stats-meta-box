(function($) {
    'use strict';

    $(document).ready(function() {
        const $metaBox = $('#content-stats-meta-box');

        if ($metaBox.length === 0) {
            return;
        }

        function minmax(min, value, max) {
            if (!value || isNaN(min) || isNaN(max)) { return false; }
            return (min <= value && value <= max)
        }

        function updateStats(editor) {
            if (!editor) { return; }
            const content = editor.getContent();
            if (!content) { return; }
            const stats = window.ContentStatsCalculator.calculate(editor);
            const format = window.ContentStatsCalculator.formatNumber;

            $metaBox.find('[data-stat="word-count"]').text(format(stats.wordCount));
            $metaBox.find('[data-stat="word-count"]').toggleClass('is-ok', minmax(1000, stats.wordCount, 2500));
            $metaBox.find('[data-stat="links-int-count"]').text(format(stats.linksIntCount));
            $metaBox.find('[data-stat="links-int-count"]').toggleClass('is-ok', (minmax(2, stats.linksIntCount, 9) && stats.linksExtCount));
            $metaBox.find('[data-stat="links-ext-count"]').text(format(stats.linksExtCount));
            $metaBox.find('[data-stat="links-ext-count"]').toggleClass('is-ok', minmax(2, stats.linksExtCount, 5));
            $metaBox.find('[data-stat="authority-domains-count"]').text(format(stats.authorityDomainsCount));
            $metaBox.find('[data-stat="authority-domains-count"]').toggleClass('is-ok', (stats.authorityDomainsCount >= 1));
            $metaBox.find('[data-stat="paragraph-count"]').text(format(stats.paragraphCount));
            $metaBox.find('[data-stat="paragraph-count"]').toggleClass('is-ok', (stats.paragraphCount >= 20));
            $metaBox.find('[data-stat="reading-time"]').text(stats.readingTime + ' min');
            $metaBox.find('[data-stat="reading-time"]').toggleClass('is-ok', minmax(5, stats.readingTime, 10));
        }

        function getEditor() {
            if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                const editor = tinymce.get('content');
                if (editor && !editor.isHidden()) {
                    return editor
                    return editor
                }
            }
            return null;
        }

        const debouncedUpdate = window.ContentStatsCalculator.debounce(function() {
            updateStats(getEditor());
        }, 500);

        $(document).on('tinymce-editor-init', function(event, editor) {
            if (editor.id === 'content') {
                editor.on('input change keyup paste NodeChange', debouncedUpdate);
                updateStats(editor);
            }
        });

        $('#content').on('input keyup paste', debouncedUpdate);

        $('#content-tmce, #content-html').on('click', function() {
            setTimeout(function() {
                updateStats(getEditor());
            }, 200);
        });

        setTimeout(function() {
            updateStats(getEditor());
        }, 500);
    });

})(jQuery);