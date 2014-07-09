<?php
//add_action ('init', 'register_my_menus');
add_action( 'init', 'register_my_menus' );

function register_my_menus() {
	register_nav_menus(
		array(
			'menu-moduler' => __( 'ModulMeny' )
		)
	);
}

/*
register_my_menus function () {
	register_nav_menus (
		array ('top-navigation' => __ ('Top'), 'footer-menu' => __ ('Footer'))
	);
}*/

if(!function_exists('get_all_sites')){
  /**
   * Retrieves all multisite blogs
   *
   * @return array Blog IDs as keys and blog names as values.
   */
  function get_all_sites() {

    global $wpdb;
    $multisite = array();
    // Query all blogs from multi-site install
    $blogs = $wpdb->get_results("SELECT blog_id,domain,path FROM wp_blogs ORDER BY path");

    // Get primary blog
    $blogname = $wpdb->get_row("SELECT option_value FROM wp_options WHERE option_name='blogname' ");
    //$multisite[1] = $blogname->option_value;

    // For each blog search for blog name in respective options table
    foreach( $blogs as $blog ) {
      // Get rest of the sites
      $blogname = $wpdb->get_results("SELECT option_value FROM wp_".$blog->blog_id ."_options WHERE option_name='blogname' ");
      foreach( $blogname as $name ) {
      	//echo $blog->path;
        //$multisite[$blog->blog_id] = $name->option_value;
        $multisite[$blog->blog_id]['Blog_Id'] = $blog->blog_id;
        $multisite[$blog->blog_id]['Name'] = $name->option_value;
        $multisite[$blog->blog_id]['Path'] = $blog->path;
      }
    }
    //print_r($multisite);
    return $multisite;
  }
}

if( !function_exists( 'get_the_post_thumbnail_by_blog' ) ) {
	function get_the_post_thumbnail_by_blog($blog_id=NULL,$post_id=NULL,$size='post-thumbnail',$attrs=NULL) {
		global $current_blog;
		$sameblog = false;

		if( empty( $blog_id ) || $blog_id == $current_blog->ID ) {
			$blog_id = $current_blog->ID;
			$sameblog = true;
		}
		if( empty( $post_id ) ) {
			global $post;
			$post_id = $post->ID;
		}
		if( $sameblog )
			return get_the_post_thumbnail( $post_id, $size, $attrs );

		if( !has_post_thumbnail_by_blog($blog_id,$post_id) )
			return false;

		global $wpdb;
		$oldblog = $wpdb->set_blog_id( $blog_id );

		$blogdetails = get_blog_details( $blog_id );
		$thumbcode = str_replace( $current_blog->domain . $current_blog->path, $blogdetails->domain . $blogdetails->path, get_the_post_thumbnail( $post_id, $size, $attrs ) );

		$wpdb->set_blog_id( $oldblog );
		return $thumbcode;
	}

	function has_post_thumbnail_by_blog($blog_id=NULL,$post_id=NULL) {
		if( empty( $blog_id ) ) {
			global $current_blog;
			$blog_id = $current_blog;
		}
		if( empty( $post_id ) ) {
			global $post;
			$post_id = $post->ID;
		}

		global $wpdb;
		$oldblog = $wpdb->set_blog_id( $blog_id );

		$thumbid = has_post_thumbnail( $post_id );
		$wpdb->set_blog_id( $oldblog );
		return ($thumbid !== false) ? true : false;
	}

	function the_post_thumbnail_by_blog($blog_id=NULL,$post_id=NULL,$size='post-thumbnail',$attrs=NULL) {
		echo get_the_post_thumbnail_by_blog($blog_id,$post_id,$size,$attrs);
	}
}

class MV_Cleaner_Walker_Nav_Menu extends Walker {
    var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
    var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );
    function start_lvl(&$output, $depth) {
        $indent = str_repeat("\t", $depth);
        //$output .= "\n$indent<ul class=\"sub-menu\">\n";
    }
    function end_lvl(&$output, $depth) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    
    function start_el(&$output, $item, $depth, $args) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes = in_array( 'current-menu-item', $classes ) ? array( 'current-menu-item' ) : array();
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = strlen( trim( $class_names ) ) > 0 ? ' class="' . esc_attr( $class_names ) . '"' : '';
        $id = apply_filters( 'nav_menu_item_id', '', $item, $args );
        $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
        $output .= $indent . '<li' . $id . $value . $class_names .' class="module half" style="background-image: url(http://62.88.129.123/wp_srhv/wp-content/uploads/2014/06/people.jpeg );">';
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
    function end_el(&$output, $item, $depth) {
        $output .= "</li>\n";
    }
}
?>