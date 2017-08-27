<?php
/**
 * Plugin Name: DevHub Related Posts
 * Description: Generate terms when parsing and display of related posts.
 * Author: keesiemeijer
 */

// Plugin Folder Path.
if ( ! defined( 'DEVHUB_RELATED_POSTS_PLUGIN_DIR' ) ) {
	define( 'DEVHUB_RELATED_POSTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * Registers the taxonomy wp-parser-related-words.
 */
require __DIR__ . '/inc/registrations.php';

/**
 * Template tags.
 */
require __DIR__ . '/inc/template-tags.php';

/**
 * PHP5 Implementation of the Porter Stemmer algorithm
 */
require __DIR__ . '/inc/porterstemmer.php';

/**
 * Title keywords algorithm.
 */
require __DIR__ . '/inc/title-keywords.php';

/**
 * Imports title keyword terms when parsing.
 */
require __DIR__ . '/inc/import-keywords.php';

/**
 * Adds the related posts template part.
 */
require __DIR__ . '/inc/extras.php';

/**
 * Query for related posts.
 */
require __DIR__ . '/inc/related-posts.php';
