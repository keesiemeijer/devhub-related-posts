<?php
/**
 * Reference Template: Title Keywords
 *
 * @package wporg-developer
 * @subpackage Reference
 */

namespace keesiemeijer\DevHub\Related_Posts;
?>


<hr />
<section class="description">
	<h2><?php _e( 'Related Posts', 'wporg' ); ?></h2>
	<?php
		display_table_related_terms_used();
		display_related_posts();
	?>
</section>
