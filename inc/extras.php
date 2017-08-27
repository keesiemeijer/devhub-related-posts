<?php
namespace keesiemeijer\DevHub\Related_Posts;

// Add the related posts template part.
add_action( 'get_template_part_reference/template', __NAMESPACE__ . '\\add_template_part', 10, 2 );

function add_template_part( $slug, $name ) {
	// Display before parameters.
	if ( 'description' === $name ) {
		require DEVHUB_RELATED_POSTS_PLUGIN_DIR . 'reference/template-related-posts.php';
	}
}
