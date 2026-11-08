<?php
/**
 * Site footer.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
?>
<footer class="kh-site-footer">
	<div class="kh-shell">
		<?php
		if ( is_active_sidebar( 'footer' ) ) :
			?>
			<div class="kh-footer-widgets"><?php dynamic_sidebar( 'footer' ); ?></div><?php endif; ?>
		<div class="kh-footer-bottom">
			<p>
			<?php
			if ( kumo_hybrid_option( 'footer_text' ) ) {
				echo esc_html( kumo_hybrid_option( 'footer_text' ) );
			} else {
				echo '&copy; ' . esc_html( wp_date( 'Y' ) ) . ' ' . esc_html( get_bloginfo( 'name' ) ); }
			?>
			</p>
			<?php
			if ( has_nav_menu( 'footer' ) ) :
				?>
				<nav aria-label="<?php esc_attr_e( 'Footer menu', 'kumo-hybrid' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'kh-footer-menu',
						'items_wrap'     => '<ul id="%1$s" class="%2$s" role="list">%3$s</ul>',
						'depth'          => 1,
					)
				);
				?>
				</nav><?php endif; ?>
		</div>
	</div>
</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
