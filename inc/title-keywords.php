<?php
namespace keesiemeijer\DevHub\Related_Posts;

/**
 * Returns array with restricted title words.
 *
 * @return array Array with restricted title words
 */
function get_restricted_words() {
	return apply_filters( 'devhub_restricted_words', array(
			'a', 'an', 'are', 'as', 'at', 'be', 'by', 'for', 'from',
			'get', 'how', 'in', 'is', 'it', 'of', 'on', 'or', 'that', 'the',
			'this', 'to', 'with', 'wp'
		) );
}


/**
 * Returns array with allowed first words in a title.
 *
 * @return array Array with restricted words
 */
function get_allowed_first_words() {
	return apply_filters( 'devhub_allowed_first_words', array(
			'is',  // is_* functions
			'get', // get_* functions
		) );
}

/**
 * Returns allowed keywords and their stems from a title.
 * Restricted words are excluded. See get_restricted_words()
 *
 * @param string $title Title to get words from.
 * @return array Array with Words and word stems.
 */
function get_title_keywords( $title ) {
	$words         = array();
	$stemmed_words = array();

	if ( empty( $title ) ) {
		return array();
	}

	// Allow 'wp' as a single title word.
	if ( 'wp' === strtolower( $title ) ) {
		return array( 'wp' );
	}

	$restricted    = get_restricted_words();
	$allowed_first = get_allowed_first_words();
	$words         = get_words( $title );

	// Get all wp-words and their stems.
	$wp_words = get_wp_words( $words );

	// Remove 'wp' from the words array.
	$words = array_values( array_diff( $words, array( 'wp' ) ) );

	foreach ( $words as $key => $word ) {

		// Keep allowed first words.
		if ( ! $key && in_array( $word, $allowed_first ) ) {
			continue;
		}

		// Remove restricted words.
		if ( in_array( $word, $restricted ) ) {
			unset( $words[ $key ] );
		}
	}

	// Stem words.
	$stemmed_words = array_map( __NAMESPACE__ . '\\PorterStemmer::Stem', $words );

	// Merge all words.
	$words = array_merge( $words, $wp_words, $stemmed_words );

	return array_values( array_filter( array_unique( $words ) ) );
}

/**
 * Returns all words from a title.
 *
 * @param string $title Title.
 * @return string Array with words.
 */
function get_words( $title ) {
	$title = strtolower( $title );
	// remove all non letter characters to underscores.
	$title = preg_replace( '/[^a-z0-9]/', '_', $title );
	// replace multiple underscores with a single underscore.
	$title = preg_replace( '/_+/', '_', $title );

	return explode( '_', trim( $title, '_' ) );
}

/**
 * Returns wp-words if 'wp' is found as a word in the title.
 * wp-words are: wp-{second_word}, second word and second word stem.
 * e.g. wp-words for the function wp_create_term() are: wp-create, create and creat.
 *
 * @param string $words Array with title words.
 * @return array wp words
 */
function get_wp_words( $words ) {
	$wp_words = array();

	if ( empty( $words ) || ! is_array( $words ) ) {
		return array();
	}

	foreach ( $words as $key => $word ) {

		// Check if word is 'wp'.
		if ( 'wp' !== $word ) {
			continue;
		}

		// Check if there is a next word.
		if ( ! isset( $words[ $key + 1 ] ) ) {
			continue;
		}

		// Create wp-words.
		$wp_words[] = 'wp-' . $words[ $key + 1 ];
		$wp_words[] = $words[ $key + 1 ];
		$wp_words[] = PorterStemmer::Stem( $words[ $key + 1 ] );
	}

	return array_values( array_unique( $wp_words ) );
}

/**
 * Match two titles and return score based on similarities.
 *
 * Todo: make matching less greedy.
 *
 * @param  string $title1 Title.
 * @param  string $title2 Title to match similarities.
 * @return int    Greater than 0 if similarity found. 0 for no similarities.
 */
function get_title_match_score( $title1, $title2 ) {
	$score      = 0;
	$words      = get_words( $title1 );
	foreach ( $words as $word ) {
		if ( $word && (false !== strpos( $title2, $word )) ) {
			$score = $score + 0.1;
		}
	}

	return $score;
}
