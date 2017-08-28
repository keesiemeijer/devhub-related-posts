<?php
namespace keesiemeijer\DevHub\Related_Posts;

/**
 * Check if a post is marked private or deprecated.
 *
 * @param int $post_id Post ID.
 * @return boolean True if a post is marked private or deprecated.
 */
function is_excluded_type( $post_id ) {
	$private = function_exists( '\DevHub\get_private_access_message' );
	if ( $private && \DevHub\get_private_access_message( $post_id ) ) {
		return true;
	}

	$deprecated = function_exists( '\DevHub\is_deprecated' );
	if ( $deprecated && \DevHub\is_deprecated( $post_id ) ) {
		return true;
	}

	return false;
}

/**
 * Display related posts.
 *
 * @param null|WP_Post $post Optional. The post. Default null.
 */
function display_related_posts( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	if ( ! function_exists( '\DevHub\\get_parsed_post_types' ) ) {
		return;
	}

	$html            = '';
	$count           = 0;
	$post_type       = $post->post_type;
	$post_type_names = \DevHub\get_parsed_post_types( 'labels' );
	$post_type_name  = isset( $post_type_names[ $post_type ] ) ? strtolower( $post_type_names[ $post_type ] ) : '';

	// Get related posts.
	$related_posts = Related_Posts::get_posts( $post );

	if ( $related_posts ) {
		$count = count( $related_posts );
		foreach ( (array) $related_posts as $related ) {
			$html .= '<li><a href="' . get_permalink( $related->ID ) . '">';
			$html .= $related->post_title . '</a>';
			$html .= ' (' . $related->termcount . ')</li>';
		}
	}

	if ( $html ) {
		echo "<h3>Related $post_type_name</h3>";
		echo '<p>' . $count . ' related ' . $post_type_name . ' found for: <code>' . $post->post_title . '</code></p>';
		echo '<ul>' . $html . '</ul>';
	} else {
		echo 'No related posts found';
	}
}

/**
 * Display a table with taxonomies and terms used to find the related posts.
 */
function display_table_related_terms_used() {
	global $post;

	$post_type     = $post->post_type;
	$taxonomies    = array( 'wp-parser-related-words', 'wp-parser-package', 'wp-parser-source-file' );
	$related_terms = wp_get_object_terms( $post->ID, array_map( 'trim', (array) $taxonomies ) );
	$types         = array( 'words' => '', 'package' => '', 'file' => '' );

	foreach ( $related_terms as $term ) {
		$tax = explode( '-', $term->taxonomy );
		$tax = end( $tax );
		$types[ $tax ] .= ', ' . $term->name;
		$types[ $tax ] = trim( $types[ $tax ], ', ' );
	}
	echo "<p>Related terms found for this post</p>";
	echo '<table><thead><th>Taxonomy</th><th>Terms</th></thead><tbody>';
	echo "<tbody><tr><td>wp-parser-related-words</td><td>{$types['words']}</td></tr>";
	echo "<tr><td>wp-parser-package</td><td>{$types['package']}</td></tr>";
	echo "<tr><td>wp-parser-source-file</td><td>{$types['file']}</td></tr></tbody></table>";

	$terms_used = Related_Posts::get_terms( $post->ID, $taxonomies );
	if ( ! $terms_used ) {
		return;
	}

	echo "<h3>Terms Used</h3>";
	echo "<p>Terms used in the related posts query</p>";
	echo "<ul>";
	foreach ( $terms_used as $used ) {
		echo '<li>' . $used->name . '</li>';
	}
	echo "</ul>";

}
