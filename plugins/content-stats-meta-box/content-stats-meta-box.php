<?php
/**
 * Plugin Name: Content Stats Meta Box
 * Description: Displays content statistics in a meta box for posts
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: content-stats-meta-box
 * Domain Path: /languages
 */

if (!defined('ABSPATH') && !is_admin()) {
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
        <div class="content-stats-meta-box-wrapper">
            <style>
                .content-stats-wrapper {
                    font-size: 13px;
                }
                .content-stats-meta-box-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 8px 0;
                    border-bottom: 1px solid #ddd;
                }
                .content-stats-meta-box-row:last-child {
                    border-bottom: none;
                }
                .content-stats-meta-box-label {
                    font-weight: 600;
                    color: #1d2327;
                }
                .content-stats-meta-box-value {
                    color: #50575e;
                }
                .content-stats-meta-box-value::after {
                    display: inline-block;
                    width: 12px;
                    height: 12px;
                    margin-left: 8px;
                }
                .content-stats-meta-box-value.is-ok::after {
                    content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 64 64' enable-background='new 0 0 64 64'%3E%3Cpath d='M32,2C15.431,2,2,15.432,2,32c0,16.568,13.432,30,30,30c16.568,0,30-13.432,30-30C62,15.432,48.568,2,32,2z M25.025,50 l-0.02-0.02L24.988,50L11,35.6l7.029-7.164l6.977,7.184l21-21.619L53,21.199L25.025,50z' fill='%2343a047'/%3E%3C/svg%3E");
                }
                .content-stats-meta-box-value:not(.is-ok)::after {
                    content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 20 20'%3E%3Ctitle%3E alert %3C/title%3E%3Cstyle type='text/css'%3E* %7B fill: %23fc3 %7D%3C/style%3E%3Cpath d='M19.64 16.36L11.53 2.3A1.85 1.85 0 0 0 10 1.21 1.85 1.85 0 0 0 8.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67 0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z'/%3E%3C/svg%3E");
                }
            </style>

            <div class="content-stats-meta-box-row" title="goal: 1000 - 2500">
                <span class="content-stats-meta-box-label"><?php _e('Words:', self::TEXT_DOMAIN); ?></span>
                <span class="content-stats-meta-box-value" data-stat="word-count">-</span>
            </div>

            <div class="content-stats-meta-box-row" title="goal: 2 - 9 and more than external">
                <span class="content-stats-meta-box-label"><?php _e('Internal Links:', self::TEXT_DOMAIN); ?></span>
                <span class="content-stats-meta-box-value" data-stat="links-int-count">-</span>
            </div>

            <div class="content-stats-meta-box-row" title="goal: 2 - 5, prefer reputable">
                <span class="content-stats-meta-box-label"><?php _e('External Links:', self::TEXT_DOMAIN); ?></span>
                <span class="content-stats-meta-box-value" data-stat="links-ext-count">-</span>
            </div>

            <div class="content-stats-meta-box-row" title="goal: at least one">
                <span class="content-stats-meta-box-label"><?php _e('Reputable Domains:', self::TEXT_DOMAIN); ?></span>
                <span class="content-stats-meta-box-value" data-stat="authority-domains-count">-</span>
            </div>

            <div class="content-stats-meta-box-row" title="goal: at least twenty">
                <span class="content-stats-meta-box-label"><?php _e('Paragraphs:', self::TEXT_DOMAIN); ?></span>
                <span class="content-stats-meta-box-value" data-stat="paragraph-count">-</span>
            </div>

            <div class="content-stats-meta-box-row" title="goal: between five and ten">
                <span class="content-stats-meta-box-label"><?php _e('Est. Reading Time:', self::TEXT_DOMAIN); ?></span>
                <span class="content-stats-meta-box-value" data-stat="reading-time">-</span>
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