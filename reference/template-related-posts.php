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
</section>
<section class="description">
	<?php
		display_related_posts();
	?>
</section>
<section class="description">
	<h3><?php _e( 'Related terms', 'wporg' ); ?></h3>
	<?php
		display_table_related_terms_used();
	?>
</section>



