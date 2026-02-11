<?php
/**
 * @package content-stats-meta-box
 * @wordpress-plugin
 * Plugin Name: Content Stats Meta Box
 * Text Domain: content-stats-meta-box
 * Domain Path: /languages
 * Description: Displays content statistics in a meta box for posts
 * Version: 1.2.0
 * Author: openmindculture
 * Author URI: https://wordpress.org/support/users/openmindculture/
 */

if (!defined('ABSPATH') && !is_admin()) {
    exit;
}

class Content_Stats_Meta_Box
{

    const TEXT_DOMAIN = 'content-stats-meta-box';

    public function __construct()
    {
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_classic_editor_assets'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
    }

    public function load_textdomain()
    {
        load_plugin_textdomain(
            self::TEXT_DOMAIN,
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }

    public function add_meta_box()
    {
        add_meta_box(
            'content-stats-meta-box',
            __('Content Statistics', self::TEXT_DOMAIN),
            array($this, 'render_meta_box'),
            'post',
            'side',
            'high'
        );
    }

    public function render_meta_box($post)
    {
        ?>
        <div class="content-stats-meta-box-wrapper">
            <div class="content-stats-headline-preview"><?php
                echo get_the_title($post->ID);
                ?></div>

            <div class="content-stats-excerpt-preview"><?php
                echo get_the_excerpt($post->ID);
                ?></div>

            <div class="content-stats-focus-keyword">
                <span class="content-stats-meta-box-label"><?php _e('Focus:', self::TEXT_DOMAIN); ?></span>
                <span data-stat="focus-keyword"></span>
            </div>

            <div class="content-stats-meta-box-row" title="goal: between five and ten">
                <span class="content-stats-meta-box-label"><?php _e('Est. Reading Time:', self::TEXT_DOMAIN); ?></span>
                <span class="content-stats-meta-box-value" data-stat="reading-time">-</span>
            </div>

            <div class="content-stats-meta-box-row" title="goal: 1000 - 2500">
                <span class="content-stats-meta-box-label"><?php _e('Words:', self::TEXT_DOMAIN); ?></span>
                <span class="content-stats-meta-box-value" data-stat="word-count">-</span>
            </div>

            <div class="content-stats-meta-box-row" title="goal: at least twenty">
                <span class="content-stats-meta-box-label"><?php _e('Paragraphs:', self::TEXT_DOMAIN); ?></span>
                <span class="content-stats-meta-box-value" data-stat="paragraph-count">-</span>
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

        </div>
        <?php
    }

    public function enqueue_classic_editor_assets($hook)
    {
        if (!in_array($hook, array('post.php', 'post-new.php'))) {
            return;
        }

        wp_enqueue_script(
            'content-stats-calculator',
            plugins_url('js/statsCalculator.js', __FILE__),
            array(),
            wp_get_theme()->get('Version'),
            true
        );

        wp_enqueue_script(
            'content-stats-classic-editor',
            plugins_url('js/classicEditor.js', __FILE__),
            array('jquery', 'content-stats-calculator'),
            wp_get_theme()->get('Version'),
            true
        );

        wp_enqueue_style(
            'content-stats-admin-style',
            plugins_url('css/adminStyle.css', __FILE__),
            array(),
            wp_get_theme()->get('Version'),
            'all'
        );

    }
}

new Content_Stats_Meta_Box();