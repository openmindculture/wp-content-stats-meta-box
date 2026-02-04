<?php
/**
 * Plugin Name: Content Stats Meta Box
 * Description: Displays content statistics in a meta box for posts
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: content-stats-meta-box
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

class Content_Stats_Meta_Box {

    const TEXT_DOMAIN = 'content-stats-meta-box';

    public function __construct() {
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_classic_editor_assets'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
    }

    public function load_textdomain() {
        load_plugin_textdomain(
            self::TEXT_DOMAIN,
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }

    public function add_meta_box() {
        add_meta_box(
            'content-stats-meta-box',
            __('Content Statistics', self::TEXT_DOMAIN),
            array($this, 'render_meta_box'),
            'post',
            'side',
            'high'
        );
    }

    public function render_meta_box($post) {
        ?>
        <div class="content-stats-wrapper">
            <style>
                .content-stats-wrapper {
                    font-size: 13px;
                }
                .wp-content-stats-meta-box-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 8px 0;
                    border-bottom: 1px solid #ddd;
                }
                .wp-content-stats-meta-box-row:last-child {
                    border-bottom: none;
                }
                .wp-content-stats-meta-box-label {
                    font-weight: 600;
                    color: #1d2327;
                }
                .wp-content-stats-meta-box-value {
                    color: #50575e;
                }
                .wp-content-stats-meta-box-value::after {
                    display: inline-block;
                    width: 12px;
                    height: 12px;
                    margin-left: 8px;
                }
                .wp-content-stats-meta-box-value--ok::after {
                    content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 64 64' enable-background='new 0 0 64 64'%3E%3Cpath d='M32,2C15.431,2,2,15.432,2,32c0,16.568,13.432,30,30,30c16.568,0,30-13.432,30-30C62,15.432,48.568,2,32,2z M25.025,50 l-0.02-0.02L24.988,50L11,35.6l7.029-7.164l6.977,7.184l21-21.619L53,21.199L25.025,50z' fill='%2343a047'/%3E%3C/svg%3E");
                }
                .wp-content-stats-meta-box-value--warning::after {
                    content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 20 20'%3E%3Ctitle%3E alert %3C/title%3E%3Cstyle type='text/css'%3E* %7B fill: %23fc3 %7D%3C/style%3E%3Cpath d='M19.64 16.36L11.53 2.3A1.85 1.85 0 0 0 10 1.21 1.85 1.85 0 0 0 8.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67 0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z'/%3E%3C/svg%3E");
                }
                .wp-content-stats-meta-box-value--red-flag::after {
                    content: url("data:image/svg+xml,%0A%3Csvg xmlns='http://www.w3.org/2000/svg' xml:space='preserve' width='20' height='15'%3E%3Cpath d='m161.352 371.653-1.148.483-1.118-6.818-19.593-130.15-18.476-123.331.96-1.794.96-1.793 304.574-.75 305.072-.25-9.793 4.768L437.5 243.73z' style='fill:%23ec1b23'/%3E%3Cpath d='m170.25 586.895-13.692-2.025-12.26-2.238-29.236-239.603-28.919-239.286-.821-.79-12.654-5.51-11.833-4.717.626-6.113 1.184-11.257.559-5.143 11.148-5.142 11.148-5.14 11.187 4.127 11.187 4.128 1.176 5.157 1.68 12.72.505 7.561-3.034 2.553-3.034 2.553 33.416 243.878L172 586.743z' style='fill:%23000'/%3E%3C/svg%3E");
                }
            </style>

            <div class="stat-row">
                <span class="stat-label"><?php _e('Words:', self::TEXT_DOMAIN); ?></span>
                <span class="stat-value" data-stat="word-count">-</span>
            </div>

            <div class="stat-row">
                <span class="stat-label"><?php _e('Links (int.):', self::TEXT_DOMAIN); ?></span>
                <span class="stat-value" data-stat="links-int-count">-</span>
            </div>

            <div class="stat-row">
                <span class="stat-label"><?php _e('Links (ext.):', self::TEXT_DOMAIN); ?></span>
                <span class="stat-value" data-stat="links-ext-count">-</span>
            </div>

            <div class="stat-row">
                <span class="stat-label"><?php _e('Paragraphs:', self::TEXT_DOMAIN); ?></span>
                <span class="stat-value" data-stat="paragraph-count">-</span>
            </div>

            <div class="stat-row">
                <span class="stat-label"><?php _e('Est. Reading Time:', self::TEXT_DOMAIN); ?></span>
                <span class="stat-value" data-stat="reading-time">-</span>
            </div>
        </div>
        <?php
    }

    public function enqueue_classic_editor_assets($hook) {
        if (!in_array($hook, array('post.php', 'post-new.php'))) {
            return;
        }

        wp_enqueue_script(
            'content-stats-calculator',
            plugins_url('js/statsCalculator.js', __FILE__),
            array(),
            '1.0.0',
            true
        );

        wp_enqueue_script(
            'content-stats-classic-editor',
            plugins_url('js/classicEditor.js', __FILE__),
            array('jquery', 'content-stats-calculator'),
            '1.0.0',
            true
        );
    }
}

new Content_Stats_Meta_Box();