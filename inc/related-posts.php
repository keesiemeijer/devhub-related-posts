<?php
namespace keesiemeijer\DevHub\Related_Posts;

class Related_Posts {

	/**
	 * Get related posts.
	 *
	 * @return array Array with related post objects
	 */
	public static function get_posts( $post = null, $args = array() ) {
		$defaults = array(
			'posts_per_page' => 25,
		);

		$args = wp_parse_args( $args, $defaults );

		$post = get_post( $post );
		if ( ! $post ) {
			return array();
		}

		$taxonomies = self::get_taxonomies();
		$terms      = self::get_terms( $post->ID, $taxonomies );
		if ( ! $terms ) {
			return array();
		}

		$tax_query = self::get_tax_query( $terms, $taxonomies );
		if ( empty( $tax_query ) ) {
			return array();
		}

		$tax_query['relation'] = 'OR';

		$query_args = array(
			'post_type'      => $post->post_type,
			'post__not_in'   => array( $post->ID ),
			'posts_per_page' => -1,
			'tax_query'      => $tax_query,
		);

		add_filter( 'posts_clauses', array( __CLASS__, 'add_termcount' ) );
		$related = new \WP_Query( $query_args );
		remove_filter( 'posts_clauses', array( __CLASS__, 'add_termcount' ) );

		wp_reset_postdata();

		if ( ! ( isset( $related->posts ) && is_array( $related->posts ) ) ) {
			return array();
		}

		$related_posts = $related->posts;

		return self::filter_related_posts( $related_posts, $post->post_title, $args['posts_per_page'] );
	}

	/**
	 * Filters the related posts.
	 *
	 * Filters deprecated posts and posts with less than 3 terms in common.
	 * Filters posts that are 3 term count steps below the top term count.
	 *
	 * @param array   $posts      Array with post objects.
	 * @param string  $post_title Post title.
	 * @param integer $max_posts  Maximum of posts to return. -1 returns all filtered posts.
	 * @return array Array with filtered posts.
	 */
	private static function filter_related_posts( $posts, $post_title, $max_posts = -1 ) {
		$related_posts = array();
		$step_count    = 0;
		$temp_count    = 0;

		// Loop through the related posts.
		foreach ( (array) $posts as $key => $post ) {

			if ( ! isset( $post->termcount ) || is_excluded_type( $post->ID ) ) {
				continue;
			}

			if ( $post->termcount !== $temp_count ) {
				$step_count++;
				$temp_count = $post->termcount;
			}

			$related = ( $post->termcount > 2 ) && ( $step_count <= 3  );
			if ( $related ) {
				$title_score = get_title_match_score( $post_title, $post->post_title );
				$post->termcount += $title_score;
				$related_posts[] = $post;
			}
		}

		$related_posts = wp_list_sort( $related_posts, 'termcount', 'DESC' );
		$related_posts = array_values( $related_posts );

		if ( -1 !== (int) $max_posts ) {
			$max_posts     = absint( $max_posts ) ? absint( $max_posts ) : 25;
			$related_posts = array_slice( $related_posts, 0, $max_posts );
		}

		return $related_posts;
	}

	/**
	 * Add termcount to order post by most terms in common.
	 *
	 * @param Array $pieces The list of clauses for the query.
	 */
	public static function add_termcount( $pieces ) {
		global $wpdb;

		$pieces['fields'] .= ", count(distinct $wpdb->term_relationships.term_taxonomy_id) as termcount";
		$pieces['orderby'] = ' termcount DESC, ' . $pieces['orderby'];

		return $pieces;
	}

	/**
	 * Creates a tax query for taxonomy terms.
	 *
	 * @param array  $terms    Array with term objects.
	 * @param string $taxonomy Taxonomy name.
	 * @return array|false A Tax query or false if no terms where found.
	 */
	private static function get_tax_query( $terms, $taxonomies ) {
		$tax_query  = array();

		foreach ( $taxonomies as $taxonomy ) {
			$tax_terms = wp_list_filter( $terms, array( 'taxonomy' => $taxonomy ) );

			if ( ! $tax_terms ) {
				continue;
			}

			$tax_query[] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => wp_list_pluck( $tax_terms, 'term_id' ),
			);
		}

		return $tax_query;
	}

	/**
	 * Get post terms
	 * Deprecated terms are excluded.
	 *
	 * @param WP_Post $post       Post object.
	 * @param array   $taxonomies Taxonomy names.
	 * @return array Array with term objects.
	 */
	public static function get_terms( $post, $taxonomies = array() ) {

		if ( ! $taxonomies ) {
			$taxonomies = self::get_taxonomies();
		}

		$terms = wp_get_object_terms( $post, $taxonomies );
		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		$terms = self::exclude_terms( $terms );
		if ( empty( $terms ) ) {
			return array();
		}

		return array_values( $terms );
	}

	/**
	 * Exclude terms.
	 *
	 * @param array $terms Array with term objects.
	 * @return array Array with term objects.
	 */
	private static function exclude_terms( $terms ) {
		$excluded_terms = self::get_excluded_terms();

		foreach ( $terms as $key => $term ) {
			if ( in_array( $term->name, $excluded_terms ) ) {
				unset( $terms[ $key ] );
			}
		}
		return array_values( $terms );
	}

	public static function get_taxonomies() {
		return array(
			'wp-parser-related-words',
			'wp-parser-package',
			'wp-parser-source-file',
		);
	}

	/**
	 * Terms to exclude in the related posts query.
	 *
	 * @return Array Array with excluded term ids.
	 */
	private static function get_excluded_terms() {
		return array(
			// Title word terms.
			'deprecated',
			'deprecated-php',
			'ms-deprecated-php',
			'pluggable-deprecated-php',

			// File terms.
			'wp-admin/includes/deprecated.php',
			'wp-admin/includes/ms-deprecated.php',
			'wp-includes/deprecated.php',
			'wp-includes/ms-deprecated.php',
			'wp-includes/pluggable-deprecated.php',

			// Package terms.
			'Deprecated',
			'WordPress',
		);
	}
}
