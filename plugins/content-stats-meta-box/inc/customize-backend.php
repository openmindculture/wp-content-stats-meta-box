<?php

/**
 * Adds a "Drafts" link under the "Posts" admin menu.
 */
add_action('admin_menu', function () {
    add_submenu_page(
        'edit.php',                             // Parent slug (Posts)
        'Draft Posts',                          // Page title
        'Drafts',                               // Menu title
        'edit_posts',                           // Capability required
        'edit.php?post_status=draft&post_type=post' // Menu slug (the destination URL)
    );
});