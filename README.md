# wp-content-stats-meta-box

A simple WordPress plugin showing stats while using classic or block editor.
Currently, only the classic version is tested.
If you are interested in the block editor version I am looking forward to your pull requests!

## usage

Install the plugin to see stats while writing a post. Stats will update automatically debounced from time to time.

## disclaimer and known limitations

This plugin is provided as is without any warranty.

It currently only works in the classic editor and stats may slightly differ from those of similar plugins as you can see in the screenshot below. Goals and heuristic signals for reputable outgoing links (like linking to [wikipedia.org](https://en.wikipedia.org/)) are hard-coded based on common recommendations for writing general-purpose blog posts. The plugin version 1.0.1 has been tested successfully with WordPress 6.9 on [Open-Mind-Culture.org](https://www.open-mind-culture.org/) in 2026.

Feel free to review the open [issues on GitHub](https://github.com/openmindculture/wp-content-stats-meta-box/issues) and contribute to the project. 

## development

- `docker compose up --build`

- open http://localhost:8666/wp-admin

- default admin credentials: `admin`:`secret`

- activate the plugin

## deployment

zip `plugins/content-stats-meta-box`

## screenshot

![screenshot](./screenshot.png)

This screenshot shows an early plugin version on the right and slightly different statistics for the same post content provided by Yoast's WordPress SEO insights: estimated reading time of 8 vs. 7 minutes, word count of 1245 vs. 1219 words.