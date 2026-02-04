/**
 * Block Editor Integration for Content Stats
 */
(function (wp) {
    'use strict';

    const { registerPlugin } = wp.plugins;
    const { PluginDocumentSettingPanel } = wp.editPost;
    const { useSelect } = wp.data;
    const { createElement: el, useState, useEffect, useRef } = wp.element;

    const ContentStatsPanel = () => {
        const [stats, setStats] = useState({
            wordCount: 0,
            charCount: 0,
            charCountNoSpaces: 0,
            paragraphCount: 0,
            readingTime: 0
        });

        // Track if this is the first render
        const isFirstRender = useRef(true);

        // Get the current post content
        const content = useSelect((select) => {
            const editor = select('core/editor');
            return editor.getEditedPostContent();
        }, []);

        // Update stats with debouncing
        useEffect(() => {
            // Calculate immediately on first render
            if (isFirstRender.current) {
                isFirstRender.current = false;
                const initialStats = window.ContentStatsCalculator.calculate(content);
                setStats(initialStats);
                return;
            }

            // Use debouncing for subsequent updates
            const debouncedUpdate = window.ContentStatsCalculator.debounce(() => {
                const newStats = window.ContentStatsCalculator.calculate(content);
                setStats(newStats);
            }, 500);

            debouncedUpdate();

            // Cleanup function
            return () => {
                // The debounce cleanup is handled internally
            };
        }, [content]);

        const format = window.ContentStatsCalculator.formatNumber;

        return el(
            'div',
            { className: 'content-stats-panel' },
            el(
                'div',
                { style: { padding: '16px 0' } },
                el('div', {
                        className: 'stat-row',
                        style: { display: 'flex', justifyContent: 'space-between', marginBottom: '12px' }
                    },
                    el('strong', {}, 'Words:'),
                    el('span', {}, format(stats.wordCount))
                ),
                el('div', {
                        className: 'stat-row',
                        style: { display: 'flex', justifyContent: 'space-between', marginBottom: '12px' }
                    },
                    el('strong', {}, 'Characters:'),
                    el('span', {}, format(stats.charCount))
                ),
                el('div', {
                        className: 'stat-row',
                        style: { display: 'flex', justifyContent: 'space-between', marginBottom: '12px' }
                    },
                    el('strong', {}, 'Characters (no spaces):'),
                    el('span', {}, format(stats.charCountNoSpaces))
                ),
                el('div', {
                        className: 'stat-row',
                        style: { display: 'flex', justifyContent: 'space-between', marginBottom: '12px' }
                    },
                    el('strong', {}, 'Paragraphs:'),
                    el('span', {}, format(stats.paragraphCount))
                ),
                el('div', {
                        className: 'stat-row',
                        style: { display: 'flex', justifyContent: 'space-between' }
                    },
                    el('strong', {}, 'Est. Reading Time:'),
                    el('span', {}, `${stats.readingTime} min`)
                )
            )
        );
    };

    registerPlugin('content-stats-plugin', {
        render: () => {
            return el(
                PluginDocumentSettingPanel,
                {
                    name: 'content-stats-panel',
                    title: 'Content Statistics',
                    className: 'content-stats-panel',
                },
                el(ContentStatsPanel)
            );
        },
    });
})(window.wp);