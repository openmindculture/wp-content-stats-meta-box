/**
 * Classic Editor Integration for Content Stats
 */
(function($) {
    'use strict';

    // Wait for DOM to be ready
    $(document).ready(function() {
        const $metaBox = $('#content-stats-meta-box');

        // Exit if meta box doesn't exist
        if ($metaBox.length === 0) {
            return;
        }

        /**
         * Update the stats display
         */
        function updateStats(content) {
            const stats = window.ContentStatsCalculator.calculate(content);
            const format = window.ContentStatsCalculator.formatNumber;

            // Update each stat element
            $metaBox.find('[data-stat="word-count"]').text(format(stats.wordCount));
            $metaBox.find('[data-stat="char-count"]').text(format(stats.charCount));
            $metaBox.find('[data-stat="char-count-no-spaces"]').text(format(stats.charCountNoSpaces));
            $metaBox.find('[data-stat="paragraph-count"]').text(format(stats.paragraphCount));
            $metaBox.find('[data-stat="reading-time"]').text(stats.readingTime + ' min');
        }

        /**
         * Get content from TinyMCE or textarea
         */
        function getEditorContent() {
            // Try to get content from TinyMCE (visual editor)
            if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                return tinymce.get('content').getContent();
            }

            // Fallback to textarea (text editor)
            const $textarea = $('#content');
            if ($textarea.length) {
                return $textarea.val();
            }

            return '';
        }

        // Create debounced update function (500ms delay)
        const debouncedUpdate = window.ContentStatsCalculator.debounce(function() {
            const content = getEditorContent();
            updateStats(content);
        }, 500);

        /**
         * Initialize stats tracking
         */
        function initializeStatsTracking() {
            // Initial calculation
            updateStats(getEditorContent());

            // Listen for TinyMCE changes
            if (typeof tinymce !== 'undefined') {
                // Wait for TinyMCE to initialize
                $(document).on('tinymce-editor-init', function(event, editor) {
                    if (editor.id === 'content') {
                        // Update on keyup, change, and paste
                        editor.on('keyup change paste', debouncedUpdate);

                        // Update on switching between visual/text modes
                        editor.on('SetContent', debouncedUpdate);
                    }
                });

                // If TinyMCE is already initialized
                if (tinymce.get('content')) {
                    const editor = tinymce.get('content');
                    editor.on('keyup change paste SetContent', debouncedUpdate);
                }
            }

            // Listen for textarea changes (text mode)
            $('#content').on('input keyup paste', debouncedUpdate);

            // Update when switching between Visual/Text tabs
            $('#content-tmce, #content-html').on('click', function() {
                setTimeout(function() {
                    updateStats(getEditorContent());
                }, 100);
            });
        }

        // Initialize
        initializeStatsTracking();
    });

})(jQuery);