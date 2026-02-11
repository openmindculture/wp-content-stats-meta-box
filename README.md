# wp-content-stats-meta-box

A simple WordPress plugin showing stats while using classic or block editor.
Currently, as of plugin release 1.2.0, only the classic version is tested.
If you are interested in the block editor version I am looking forward to your pull requests!

## Installation and Usage 

Install the plugin to see stats while writing a post. Stats will update automatically debounced from time to time. See the [screenshots section](#screenshots) below for a visual preview of the plugin's functionality. If the plugin is not available in the official WordPress plugin directory yet, you can obtain a zip file from the [GitHub release page](https://github.com/openmindculture/wp-content-stats-meta-box/releases) and upload it manually by clicking add plugin -> upload plugin -> choose file -> install now -> activate.

### Troubleshooting

**Updating**

If an update installation fails, deactivate and delete all instances of this plugin before installing the latest version again.

## Disclaimer and Known Limitations

This plugin is provided as is without any warranty.

Initial releases have not yet been optimized for efficient performance. They only work in the classic editor and the provided stats may slightly differ from those of similar plugins like Yoast, as you can see in the [screenshots section](#screenshots. Goals and heuristic signals for reputable outgoing links (like linking to [wikipedia.org](https://en.wikipedia.org/)) are hard-coded based on common recommendations for writing general-purpose blog posts. The plugin version 1.2.0 has been tested successfully with WordPress 6.9 on [Open-Mind-Culture.org](https://www.open-mind-culture.org/) in 2026.

Feel free to review the open [issues on GitHub](https://github.com/openmindculture/wp-content-stats-meta-box/issues) and contribute to the project. 

## Development

- `docker compose up --build`

- open http://localhost:8666/wp-admin

- default admin credentials: `admin`:`secret`

- activate the plugin

## Deployment

Zip `plugins/content-stats-meta-box` to crate a plugin archive file that can be uploaded on the add a plugin page in WordPress' admin backoffice. 

<a name="screenshots" id="screenshots"></a>
## Screenshots

The screenshot below shows published blog teasers in an archive overview layout on the left and the content statistics meta box providing a similar preview of the post title and excerpt acting like a partial preview of the published teaser layout. 

![screenshot of meta box and blog archive](./screenshot-preview.png)

This plugin can coexist and complement other content creation assistance tools like Yoast WP SEO, even though their number might slightly differ. See the following screenshot for example.

![screenshot](./screenshot.png)

This screenshot shows an early plugin version on the right and slightly different statistics for the same post content provided by Yoast's WordPress SEO insights: estimated reading time of 8 vs. 7 minutes, word count of 1245 vs. 1219 words.

Screenshots have been taken on [open-mind-culture.org](https://www.open-mind-culture.org/).