<?php
namespace keesiemeijer\DevHub\Related_Posts;

add_action( 'init', __NAMESPACE__ . '\\wporg_related_register_taxonomy' );

function wporg_related_register_taxonomy() {
	register_taxonomy( 'wp-parser-related-words', array( 'wp-parser-class', 'wp-parser-function', 'wp-parser-hook', 'wp-parser-method' ), array(
			'hierarchical'          => false,
			'label'                 => __( 'Related title words', 'wporg' ),
			'public'                => true,
			'rewrite'               => false,
			'sort'                  => false,
		) );
}
