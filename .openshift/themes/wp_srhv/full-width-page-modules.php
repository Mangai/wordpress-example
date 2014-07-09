<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Full Content Template
 *
Template Name:  Full Width Page Modules (no sidebar)
 *
 * @file           full-width-page-modules.php
 * @package        Responsive
 * @author         Patrik Bernhardsson
 * @copyright      2003 - 2014 hv.se
 * @license        license.txt
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */

get_header(); ?>

<div id="content-full" class="grid col-940">


	<?php if( have_posts() ) : ?>

		<?php while( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'loop-header' ); ?>

			<?php responsive_entry_before(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php responsive_entry_top(); ?>

				<?php get_template_part( 'post-meta-page' ); ?>
				<div class="modules">
			
<?php 
	//wp_nav_menu( array( 'theme_location' => 'menu-moduler', 'menu_class' => 'menu-moduler' ) ); 
	//wp_nav_menu( array( 'walker' => new MV_Cleaner_Walker_Nav_Menu(),'theme_location' => 'menu-moduler', 'menu_class' => 'menu-moduler' ) );
	
?>	
			
				<?php
$postid = get_the_ID();
$mypages = get_pages( array( 'child_of' => $postid, 'sort_column' => 'post_date', 'sort_order' => 'Asc', 'parent' => $postid) );
foreach( $mypages as $page ) {
	$post_thumbnail_id = get_post_thumbnail_id( $page->ID );
	$thumb_url = wp_get_attachment_image_src($post_thumbnail_id,'thumbnail-size', true);
	

?><div class="module half" style="background-image: url(<?php echo $thumb_url[0]; ?> );">
<a href="<?php echo get_page_link( $page->ID ); ?>" class="fill-div"></a>
<div class="textoverlay">
  <h2><a href="<?php echo get_page_link( $page->ID ); ?>"><?php echo $page->post_title; ?></a></h2>
  <p class="pufftext"><?php the_field('pufftext', $page->ID ); ?></p>
</div>
</div>
<?php
}//End of foreach
?>
				</div>

				<div class="post-entry">
					<?php the_content( __( 'Read more &#8250;', 'responsive' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<div class="pagination">' . __( 'Pages:', 'responsive' ), 'after' => '</div>' ) ); ?>
				</div>
				<!-- end of .post-entry -->

				<?php get_template_part( 'post-data' ); ?>

				<?php responsive_entry_bottom(); ?>
			</div><!-- end of #post-<?php the_ID(); ?> -->
			<?php responsive_entry_after(); ?>

			<?php responsive_comments_before(); ?>
			<?php comments_template( '', true ); ?>
			<?php responsive_comments_after(); ?>

		<?php
		endwhile;

		get_template_part( 'loop-nav' );

	else :

		get_template_part( 'loop-no-posts' );

	endif;
	?>

</div><!-- end of #content-full -->

<?php get_footer(); ?>
