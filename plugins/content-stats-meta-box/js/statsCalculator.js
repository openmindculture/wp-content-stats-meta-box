(function (window) {
    'use strict';
    
    const goodUrlSignals = [
        '.edu',
        '.mozilla.org/',
        '.nytimes.com/',
        '.theguardian.com/',
        '.gartner.com/',
        '.reuters.com/',
        '.sciencedirect.com/',
        'scholar.google.com/',
        '.wikipedia.org/',
        'who.int',
        'un.org'
    ]

    const ContentStatsCalculator = {
        stripHTML: function (html) {
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || '';
        },

        calculate: function (editor) {

            if (!editor || !editor.getContent()) {
                return {
                    wordCount: 0,
                    linksIntCount: 0,
                    linksExtCount: 0,
                    paragraphCount: 0,
                    readingTime: 0
                };
            }

            const content = editor.getContent();
            const siteHostnameSignal = '//' + window.location.hostname;

            const plainText = this.stripHTML(content);
            const words = plainText.trim().split(/\s+/).filter(word => word.length > 0);

            let linksInternalCount = 0;
            let linksExternalCount = 0;
            let authorityDomainsCount = 0;

            if (editor.dom) {
                const links = tinymce.activeEditor.dom.select('a');
                links.forEach(link => {
                    if (link.href.includes(siteHostnameSignal)) {
                        linksInternalCount++;
                    } else {
                        linksExternalCount++;
                        goodUrlSignals.forEach((goodUrlSignal) => {
                            if (link.href.includes(goodUrlSignal)) {
                                authorityDomainsCount++;
                            }
                        })
                    }
                });
            }

            return {
                wordCount: words.length,
                linksIntCount: linksInternalCount,
                linksExtCount: linksExternalCount,
                authorityDomainsCount: authorityDomainsCount,
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