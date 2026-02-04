(function(window) {
    'use strict';

    const ContentStatsCalculator = {
        stripHTML: function(html) {
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || '';
        },

        calculate: function(content) {
            if (!content) {
                return {
                    wordCount: 0,
                    linksIntCount: 0,
                    linksExtCount: 0,
                    paragraphCount: 0,
                    readingTime: 0
                };
            }

            const plainText = this.stripHTML(content);
            const words = plainText.trim().split(/\s+/).filter(word => word.length > 0);

            return {
                wordCount: words.length,
                linksIntCount: 0, /* TODO */
                linksExtCount: 0, /* TODO */
                paragraphCount: (content.match(/<\/p>/g) || []).length,
                readingTime: Math.max(1, Math.ceil(words.length / 200))
            };
        },

        formatNumber: function(num) {
            return num.toLocaleString();
        },

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

    window.ContentStatsCalculator = ContentStatsCalculator;

})(window);