<?php
namespace keesiemeijer\DevHub\Related_Posts;

add_action( 'wp_parser_import_item', __NAMESPACE__ . '\\add_related_terms', 10, 3 );

function add_related_terms( $post_id, $data, $post_data ) {
	set_related_object_terms( $post_id, $post_data );
}

/**
 * Sets the related terms from a post title.
 *
 * @param int   $post_id   Optional; post ID of the inserted or updated item.
 * @param array $post_data WordPress post data of the inserted or updated item.
 */
function set_related_object_terms( $post_id = 0, $post_data = array() ) {
	$post_id = absint( $post_id );

	if ( ! $post_id || ! isset( $post_data['post_title'] ) ) {
		return;
	}

	// Get related words for class and method separately.
	if ( 'wp-parser-method' === $post_data['post_type'] ) {

		$method_titles = explode( '::', $post_data['post_title'] );
		$method_words  = array();

		foreach ( $method_titles as $method_title ) {
			$method_tiltle_words = get_title_keywords( $method_title );
			$method_words        = array_merge( $method_words, $method_tiltle_words );
		}
		$words = array_filter( array_unique( $method_words ) );
	} else {
		// Get all related words and their stems.
		$words = get_title_keywords( $post_data['post_title'] );
	}

	$source_file = wp_get_post_terms( $post_id, 'wp-parser-source-file', array( 'fields' => 'names' ) );
	$source_file = isset( $source_file[0] ) ? esc_html( $source_file[0] ) : '';

	if ( $source_file ) {
		// Add the filename to the related words.
		$file_parts = pathinfo( $source_file );
		$file_name  = sanitize_title( $file_parts['basename'] );
		$words[]    = $file_name;
	}

	if ( ! empty( $words ) ) {
		// insert title word terms.
		wp_set_object_terms( $post_id, $words, 'wp-parser-related-words', false );
	}
}
