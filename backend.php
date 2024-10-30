<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://mukiiblog.wordpress.com/
 * @since             1.0.0
 * @package           Mk_Page_Title
 *
 * @wordpress-plugin
 * Plugin Name:       MK Page Title
 * Description:       Use this plugin to change the title of particular page.
 * Version:           1.0.0
 * Author:            Mukesh Kumar
 * Author URI:        https://mukiiblog.wordpress.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mk-page-title
 */

/*
 *  Here we add meta box
 *  @function: mk_add_my_meta
 * 
 *  */
add_action( 'add_meta_boxes', 'mk_add_my_meta' );
function mk_add_my_meta(){
    add_meta_box( 'custom_page_title', 'Change the Page title', 'mk_meta_function' );
}

/*
 *  Here the function will show the text box at Backend.
 * 
 *  @function: mk_meta_function
 * 
 *  Use of global variable $post
 *  */
function mk_meta_function(){
    global $post;
    ?>
        <input type="text" class="my_title" name="my_title" value="<?php echo esc_attr( get_post_meta( $post->ID, 'mk_custom_post_title', true ) ); ?>">
        <span><i>Add only text values.</i></span><br />
    <?php
    
}

/*
 *  Here the function will update the title at Backend.
 * 
 *  @function: mk_save_title_data
 * 
 *  Use of global variable $post
 *  */
add_action( 'save_post', 'mk_save_title_data' );
function mk_save_title_data(){
    
    global $post;
//    $my_title = sanitize_text_field( esc_attr( $_POST['my_title'] ) );
    $my_title = sanitize_text_field( $_POST['my_title'] );

    if( isset( $my_title ) && !empty( $my_title ) ):
        if( is_numeric( $my_title ) ){
            
            delete_post_meta( $post->ID, 'mk_custom_post_title' );
        }else{
            update_post_meta( $post->ID, 'mk_custom_post_title', esc_attr( $my_title ) );
        }
    else:
        delete_post_meta( $post->ID, 'mk_custom_post_title' );
        return false;
    endif;
    
}

/*
 *  The _wp_render_title_tag have the basic functionality
 * 
 *  @default: _wp_render_title_tag
 * 
 *  Use custom function with this.
 *  */
if ( has_action( 'wp_head','_wp_render_title_tag' ) == 1 ) {
    remove_action( 'wp_head','_wp_render_title_tag',1 );
    add_action( 'wp_head', 'mk_show_post_title' );
}

/*
 *  The function will remove the default <title> tag with custom title.
 * 
 *  $customTitle: custom title
 * 
 *  */
function mk_show_post_title(){
    if ( have_posts() ) : the_post();
	  $customTitle = get_post_meta( get_the_ID(), 'mk_custom_post_title', true );
	  if ( $customTitle ) {
		echo "<title>$customTitle</title>";
      } else {
    	echo "<title>";
	      if ( is_tag() ) {
	         single_tag_title( "Tag Archive for &quot;" ); echo '&quot; - '; }
	      elseif ( is_archive() ) {
	         wp_title( '' ); echo ' Archive - '; }
	      elseif ( ( is_single() ) || ( is_page() ) && ( !( is_front_page() ) ) ) {
	         wp_title( '' ); echo ' - '; }
	      if ( is_home() ) {
	         bloginfo( 'name' ); echo ' - '; bloginfo( 'description' ); }
	      else {
	          bloginfo( 'name' ); }
	      if ( $paged>1 ) {
	         echo ' - page '. $paged; }
        echo "</title>";
    }
    else :
      echo "<title>Page Not Found</title>";
	endif;
	rewind_posts();
}
