<?php
/**
 * Archive template.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="kh-main kh-shell" tabindex="-1">
	<header class="kh-page-header">
	<?php
	the_archive_title( '<h1>', '</h1>' );
	the_archive_description( '<div class="kh-lede">', '</div>' );
	?>
	</header>
	<?php get_template_part( 'template-parts/collection' ); ?>
</main>
<?php get_footer(); ?>
