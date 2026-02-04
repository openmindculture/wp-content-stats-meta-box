/**
 * Content Stats Calculator
 * Shared utility for calculating content statistics
 */
(function(window) {
    'use strict';

    const ContentStatsCalculator = {
        /**
         * Strip HTML tags from content
         */
        stripHTML: function(html) {
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || '';
        },

        /**
         * Calculate all content statistics
         */
        calculate: function(content) {
            const plainText = this.stripHTML(content);
            const words = plainText.split(/\s+/).filter(word => word.length > 0);

            return {
                wordCount: words.length,
                charCount: plainText.length,
                charCountNoSpaces: plainText.replace(/\s/g, '').length,
                paragraphCount: (content.match(/<\/p>/g) || []).length,
                readingTime: Math.ceil(words.length / 200) // 200 words per minute
            };
        },

        /**
         * Format number with locale
         */
        formatNumber: function(num) {
            return num.toLocaleString();
        },

        /**
         * Create a debounced function
         */
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    };

    // Export to global scope
    window.ContentStatsCalculator = ContentStatsCalculator;

})(window);