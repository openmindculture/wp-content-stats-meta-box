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
            plugins_url('js/stats-calculator.js', __FILE__),
            array(),
            '1.0.0',
            true
        );

        wp_enqueue_script(
            'content-stats-classic-editor',
            plugins_url('js/classic-editor.js', __FILE__),
            array('jquery', 'content-stats-calculator'),
            '1.0.0',
            true
        );
    }

    public function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'content-stats-calculator',
            plugins_url('js/stats-calculator.js', __FILE__),
            array(),
            '1.0.0',
            true
        );
    }
}

new Content_Stats_Meta_Box();