(function (window) {
    'use strict';

    const ContentStatsCalculator = {
        stripHTML: function (html) {
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || '';
        },

        calculate: function (content) { console.log('calculate')
            if (!content) { console.log('empty conetnt')
                return {
                    wordCount: 0,
                    linksIntCount: 0,
                    linksExtCount: 0,
                    paragraphCount: 0,
                    readingTime: 0
                };
            }

            const siteHostname = window.location.hostname;
            const hostnameRegex = new RegExp('//'+siteHostname.replace(/\./g, '\\.'), 'g'); console.log('hostnameRegex', hostnameRegex);

            const plainText = this.stripHTML(content);
            const words = plainText.trim().split(/\s+/).filter(word => word.length > 0);
console.log('words',words); console.log('wordCount: words.length,', words.length);
            return {
                wordCount: words.length,
                linksIntCount: 0,
                linksExtCount: 0,
                paragraphCount: (content.match(/<\/p>/g) || []).length,
                readingTime: Math.max(1, Math.ceil(words.length / 200))
            };
        },

        formatNumber: function (num) {
            if (isNaN(num)) {
                return 0;
            }
            return num.toLocaleString();
        },

        debounce: function (func, wait) {
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