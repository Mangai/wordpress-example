<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Full Content Template
 *
Template Name:  Full Width Page Sites (no sidebar)
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
<style>
	.main-nav{
		display: none;
	}
</style>	
<div id="content-full" class="grid col-940">


	<?php if( have_posts() ) : ?>

		<?php while( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'loop-header' ); ?>

			<?php responsive_entry_before(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php responsive_entry_top(); ?>

				<?php get_template_part( 'post-meta-page' ); ?>
				<div class="row">

					<?php
						$all_sites = get_all_sites();
						//print_r($all_sites);
						  foreach ($all_sites as $tempone) {
							echo '<div class="col-xs-6 col-sm-3 knappar">';
							    foreach ($tempone as $key=>$temptwo) {
								    switch ($key) {
									    case 'Blog_Id':
									        $Blog_Id = $temptwo;
									        break;
									    case 'Name':
									        $Name = $temptwo;
									        break;
									    case 'Path':
									        $Path = $temptwo;
									        break;
									}
							    	//echo "$key: $temptwo", "<br/>\n";								    	
							    }
							    //echo '<button type="button" class="btn btn-primary btn-lg">'. $Name .'</button>';
							    echo '<a href="'. $Path .'" class="btn btn-primary btn-lg" role="button">'. $Name .'</a>';
						    echo '</div>';
						    echo "\n";
						  }
						/*
						foreach ($all_sites as $key => $site){
						//echo $key;
						echo $site[$key]['Name'];
							echo '<div class="module half">';
							
							echo '</div>';
						}
						*/
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
