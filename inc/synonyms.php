<?php
namespace keesiemeijer\DevHub\Related_Posts;

/**
 * WordPress word synonyms.
 *
 * Array key and value relationship: synonym => synonym words
 *
 * Synonym words should be similar in meaning or have a relationship to the synonym.
 * Synonym words should not have dual meanings in WordPress.
 * (e.g. The word editor is referred to as a user role or the texteditor).
 */
function get_synonyms() {
	return array(

		'capability' => array(
			'capabilities',
			'cap',
			'caps',
			'role',
			'roles',
		),

		'user' => array(
			'users',
			'author',
			'authors',
			'admins',
			'role',
			'capability',
			// Synonym admin or editor have other meanings in WP as well.
		),

		'location' => array(
			'path',
			'paths',
			'dir',
			'directory',
			'directories',
			'subdirectory',
			'subdirectories',
			'uri',
			'url',
			'urls',
			'siteurl',
			'link',
			'links',
			'permalink',
			'permalinks',
			'slug',
			'guid',
		),

		'file' => array(
			'files',
			'filename',
			'filenames',
			'filesystem',
			'filetype',
		),

		'language' => array(
			'languages',
			'i18n',
			'translate',
			'translation',
			'translations',
			'locale',
			'locales',
			'localize',
			'rtl',
			'textdomain',
		),

		'date' => array(
			'dates',
			'time',
			'weekday',
			'weekstartend',
			'year',
			'years',
			'month',
			'months',
			'day',
			'days',
			'gmt',
			'timezone',
			'mysql2date',
			'datetime',
			'checkdate',
			'calendar',
		),

		'post-type' => array(
			'post',
			'page',
			'attachment',
			'revision',
		),

		'taxonomy' => array(
			'taxonomies',
			'term',
			'terms',
			'cat',
			'cats',
			'category',
			'categories',
			'post_tag',
			'post_tags',
			// Synonym tag or tags have other meanings in WP as well.
		),

		'status' => array(
			'statuses',
			'stati',
			'publish',
			'published',
			'unpublished',
			'future',
			'pending',
			'private',
			'draft',
			'drafts',
			'trash',
			'trashed',
		),

		'meta' => array(
			'metadata',
			'termmeta',
			'postmeta',
		),

		'id' => array(
			'postid',
		),

		'network' => array(
			'mu',
			'ms',
			'wpmu',
			'blog',
			'bloginfo',
			'multisite',
		),

		'image' => array(
			'images',
			'gallery',
			'galleries',
			'thumbnails',
			'thumbnail',
		),

		'exist' => array(
			'exists',
			'has',
			'is',
		),

		'adjacent' => array(
			'next',
			'previous',
			'ancestor',
			'ancestors',
			'parent',
			'parents',
			'child',
			'children',
		),

		'validate' => array(
			'check',
			'verify',
			'nonce',
			'whitelist',
			'blacklist',
			'sanitize',
			'escape',
			'checkdate',
		),

		'integer' => array(
			'number',
			'numbers',
			'counts',
			'count',
		),

		'parameter' => array(
			'arg',
			'args',
			'argument',
			'arguments',
			'param',
			'params',
		),

		'dimension' => array(
			'length',
			'size',
			'sizes',
		),

		'feed' => array(
			'rss',
			'rss2',
			'atom',
			'rdf',
		),

		'stylesheet' => array(
			'style',
			'styles',
			'cssclass',
		),

		'javascript' => array(
			'script',
			'scripts',
		),

		'content' => array(
			'excerpt',
			'excerpts',
		),
	);
}

/**
 * WordPress abbreviations
 *
 * If an abbreviation is found the full word is used as a synonym word.
 *
 * @return  array with abbreviations and their corresponding words
 */
function get_abbreviations() {
	return array(
		'img'   => 'image',
		'svg'   => 'image',
		'jpeg'  => 'image',
		'thumb' => 'thumbnail',
		'attr'  => 'attribute',
		'atts'  => 'attribute',
		'esc'   => 'escape',
		'sql'   => 'query',
		'int'   => 'integer',
		'bool'  => 'boolean',
		'str'   => 'string',
		'auth'  => 'authentication',
		'css'   => 'stylesheet',
		'js'    => 'javascript',
		'src'   => 'location',
		'cat'   => 'category',
		'info'  => 'information',
		'doc'   => 'document',
		'prev'  => 'previous',
		'mce'   => 'editor',
		'pref'  => 'preference',
		'prefs' => 'preference',
		'rand'  => 'random',
		'tax'   => 'taxonomy',
	);
}

/**
 * Similar WordPress words.
 *
 * If a key is found the value is used as a synonym word.
 * If a value is found the key is used as a synonym word.
 *
 * @return array Array with similar (or opposite) meaning words
 */
function get_similar_words() {
	return array(
		'write'         => 'read',
		'writable'      => 'readable',
		'trashed'       => 'untrashed',
		'trash'         => 'untrash',
		'authorize'     => 'authenticate',
		'authorization' => 'authentication',
		'installed'     => 'uninstalled',
		'install'       => 'uninstall',
		'encode'        => 'decode',
		'serialize'     => 'unserialize',
		'register'      => 'unregister',
		'enqueue'       => 'dequeue',
		'login'         => 'logout',

		'css'           => 'style',

		// Single and plural of top used words
		// Be aware that words like post and posts mean different things in WP.
		'comment'    => 'comments',
		'term'       => 'terms',
		'count'      => 'counts',
		'taxonomy'   => 'taxonomies',
		'user'       => 'users',
		'image'      => 'images',
		'menu'       => 'menus',
		'attachment' => 'attachments',
		'theme'      => 'themes',
		'author'     => 'authors',
		'category'   => 'categories',
		'plugin'     => 'plugins',
		'setting'    => 'settings',
	);
}
