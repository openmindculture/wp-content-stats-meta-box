<?php
/**
 * Plugin Name: Content Stats Meta Box
 * Text Domain: content-stats-meta-box
 * Description: Displays content statistics in a meta box for posts
 * Version: 1.0.0
 * Author: Your Name
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Content_Stats_Meta_Box {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_classic_editor_assets'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
    }

    /**
     * Register meta box for Classic Editor
     */
    public function add_meta_box() {
        add_meta_box(
            'content-stats-meta-box',
            __('Content Statistics', 'text-domain'),
            array($this, 'render_meta_box'),
            'post',
            'side',
            'high'
        );
    }

    /**
     * Render the meta box content
     */
    public function render_meta_box($post) {
        ?>
        <div class="content-stats-wrapper">
            <style>
                .content-stats-wrapper {
                    font-size: 13px;
                }
                .stat-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 8px 0;
                    border-bottom: 1px solid #ddd;
                }
                .stat-row:last-child {
                    border-bottom: none;
                }
                .stat-label {
                    font-weight: 600;
                    color: #1d2327;
                }
                .stat-value {
                    color: #50575e;
                }
            </style>

            <div class="stat-row">
                <span class="stat-label"><?php _e('Words:', 'text-domain'); ?></span>
                <span class="stat-value" data-stat="word-count">0</span>
            </div>

            <div class="stat-row">
                <span class="stat-label"><?php _e('Characters:', 'text-domain'); ?></span>
                <span class="stat-value" data-stat="char-count">0</span>
            </div>

            <div class="stat-row">
                <span class="stat-label"><?php _e('Characters (no spaces):', 'text-domain'); ?></span>
                <span class="stat-value" data-stat="char-count-no-spaces">0</span>
            </div>

            <div class="stat-row">
                <span class="stat-label"><?php _e('Paragraphs:', 'text-domain'); ?></span>
                <span class="stat-value" data-stat="paragraph-count">0</span>
            </div>

            <div class="stat-row">
                <span class="stat-label"><?php _e('Est. Reading Time:', 'text-domain'); ?></span>
                <span class="stat-value" data-stat="reading-time">0 min</span>
            </div>
        </div>
        <?php
    }

    /**
     * Enqueue assets for Classic Editor
     */
    public function enqueue_classic_editor_assets($hook) {
        // Only load on post edit screens
        if (!in_array($hook, array('post.php', 'post-new.php'))) {
            return;
        }

        // Enqueue the shared stats calculator
        wp_enqueue_script(
            'content-stats-calculator',
            plugins_url('js/stats-calculator.js', __FILE__),
            array(),
            '1.0.0',
            true
        );

        // Enqueue the classic editor integration
        wp_enqueue_script(
            'content-stats-classic-editor',
            plugins_url('js/classic-editor.js', __FILE__),
            array('jquery', 'content-stats-calculator'),
            '1.0.0',
            true
        );
    }

    /**
     * Enqueue assets for Block Editor (Gutenberg)
     */
    public function enqueue_block_editor_assets() {
        // Enqueue the shared stats calculator
        wp_enqueue_script(
            'content-stats-calculator',
            plugins_url('js/stats-calculator.js', __FILE__),
            array(),
            '1.0.0',
            true
        );

        // Enqueue the block editor integration
        wp_enqueue_script(
            'content-stats-block-editor',
            plugins_url('js/block-editor.js', __FILE__),
            array('wp-plugins', 'wp-edit-post', 'wp-element', 'wp-data', 'wp-components', 'content-stats-calculator'),
            '1.0.0',
            true
        );
    }
}

// Initialize the plugin
new Content_Stats_Meta_Box();