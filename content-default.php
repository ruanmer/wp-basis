<?php
/*
 * The template used for displaying page content in page.php
 */
?>

<article id="post-<?php the_ID(); ?>" class="entry">
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- /.entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- /.entry-content -->
</article><!-- /#page-<?php the_ID(); ?> -->
