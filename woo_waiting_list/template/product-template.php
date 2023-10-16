<?php
// header part
get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<article class="post-12 page type-page status-publish hentry">
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
	</main>
</div>

<?php
// footer part
get_footer();