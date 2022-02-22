<?php
/*
Plugin Name: Football Grid Squares
Plugin URI:  https://footballgidsquares.com/
Description: Create single or multiple football grid squares using a simple shortcode and invite players to choose squares.
Version:     1.3.2
Author:      Insight Dezign
Author URI:  https://insightdezign.com/
License:     GPL2
 
Football Grid Squares is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Football Grid Squares is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Football Grid Squares If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//* Create upload directory and role on activation
function fgs_activate() { 
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir . '/football-grid-squares';
    if (! is_dir($upload_dir)) {
       mkdir( $upload_dir, 0700 );
    }
	
	$capabilities = array(
		'read' 			=> true,
		//'publish_squares' => true,
		'edit_squares' 	=> true,
		'delete_squares' 	=> true		
	);	
	
	add_role( 'fgs-admin', 'Squares Admin', $capabilities );
	
	$role = get_role( 'administrator' );
  	$role->add_cap( 'edit_square' );
	$role->add_cap( 'read_square' );
	$role->add_cap( 'delete_square' );
	$role->add_cap( 'edit_squares' );
	$role->add_cap( 'edit_other_squares' );
	$role->add_cap( 'publish_squares' );
	$role->add_cap( 'read_private_squares' );
} 
register_activation_hook( __FILE__, 'fgs_activate' );

function fgs_deactivate() {
	remove_role( 'fgs-admin' );
	
	$role = get_role( 'administrator' );
  	$role->remove_cap( 'edit_square' );
	$role->remove_cap( 'read_square' );
	$role->remove_cap( 'delete_square' );
	$role->remove_cap( 'edit_squares' );
	$role->remove_cap( 'edit_other_squares' );
	$role->remove_cap( 'publish_squares' );
	$role->remove_cap( 'read_private_squares' );
}
register_deactivation_hook( __FILE__, 'fgs_deactivate' );

//* Create Custom Post Type Squares
if ( ! function_exists('fgs_create_squares_post_type') ) {

// Register Custom Post Type
function fgs_create_squares_post_type() {

	$labels = array(
		'name'                  => 'Squares',
		'singular_name'         => 'Square',
		'menu_name'             => 'Squares',
		'name_admin_bar'        => 'Square',
		'archives'              => 'Square Archives',
		'attributes'            => 'Square Attributes',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'All Squares',
		'add_new_item'          => 'Add New Square',
		'add_new'               => 'Add New',
		'new_item'              => 'New Square',
		'edit_item'             => 'Edit Square',
		'update_item'           => 'Update Square',
		'view_item'             => 'View Square',
		'view_items'            => 'View Squares',
		'search_items'          => 'Search Square',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into square',
		'uploaded_to_this_item' => 'Uploaded to this square',
		'items_list'            => 'Squares list',
		'items_list_navigation' => 'Squares list navigation',
		'filter_items_list'     => 'Filter squares list',
	);
	$capabilities = array(
		'edit_post'             => 'edit_square',
		'read_post'             => 'read_square',
		'delete_post'           => 'delete_square',
		'edit_posts'            => 'edit_squares',
		'edit_others_posts'     => 'edit_other_squares',
		'publish_posts'         => 'publish_squares',
		'read_private_posts'    => 'read_private_squares',
	);
	$args = array(
		'label'                 => 'Square',
		'description'           => 'Football Grid Squares',
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'page-attributes' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-grid-view',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capabilities'          => $capabilities,
		'show_in_rest'          => true,
	);
	register_post_type( 'square', $args );

}
add_action( 'init', 'fgs_create_squares_post_type', 0 );

}

//* Register Styles and Scripts
function fgs_wp_enqueue_scripts() {
    wp_register_script( 'fgs_bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', '', '', true );
    wp_register_script( 'fgs_script', plugin_dir_url( __FILE__ ) . 'js/scripts.js', '', '', true );
    wp_register_style( 'fgs_bootstrap_style', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css');
    wp_register_style( 'fgs_style', plugin_dir_url( __FILE__ ) . 'css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'fgs_wp_enqueue_scripts' );

//* Shortcodes
//[football_squares_grid] shortcode
function football_squares_grid($params = array()){
    extract(shortcode_atts(array(
		'team_one' => 'team one',
		'team_two' => 'team two',
		'symbol' => '$',
		'price' => '5.00'
	), $params));

    include plugin_dir_path( __FILE__ ) . 'classes/fsg.class.php';
    
    wp_enqueue_script( 'fgs_bootstrap' );
    //wp_enqueue_script( 'fgs_script' );
    wp_enqueue_style( 'fgs_bootstrap_style' );
    wp_enqueue_style( 'fgs_style' );

    global $post;
    $post_id = get_the_ID();    
    $post_slug = $post->post_name;
    $upload = wp_upload_dir();
    $single = true;

    $squares = new football_squares;
    $squares->password = get_post_meta( $post_id,'fgs-password',$single );
    $squares->data = $upload['basedir'] . '/football-grid-squares/' . $post_id . '-data.txt';
    $squares->ndata = $upload['basedir'] . '/football-grid-squares/' . $post_id . '-ndata.txt';
    $squares->team_one = $team_one;
    $squares->team_two = $team_two;
    $squares->price = $price;
    $squares->currency_symbol = $symbol;

    $output = '<div class="fgs-game-wrapper">';
    $output .= $squares->build();
    $output .= '</div>';
    $output .= '<div style="clear:both"></div>';

    return $output;
}
add_shortcode( 'football_squares_grid', 'football_squares_grid' );

//* Default content for Squares
function fgs_editor_content( $content, $post ) {
    switch( $post->post_type ) {
        case 'square':
            $content = '[football_squares_grid team_one="AFC" team_two="NFC" symbol="$" price="5.00"]
<h2>Rules</h2>

<h3>THE NUMBERS FOR THE FOOTBALL SQUARES WILL BE SELECTED WHEN THE GRID IS FILLED.</h3>
<strong>How it works:</strong>

Once all the squares have been selected we will randomly generate numbers from 0-9 for each team in the football game and assign that number to a particular row or column. These numbers represent the last number in the score of each team. In other words, if the score is AFC 17 - NFC 14, then the winning square is the one with an AFC number of 7, and an NFC number of 4.

<strong>Winnings Breakdown for Squares</strong>
<ul>
 	<li>End of 1st Quarter: 20%</li>
 	<li>End of 2nd Quarter: 20%</li>
 	<li>End of 3rd Quarter: 20%</li>
 	<li>End of Game: 40%</li>
</ul>';
        break;
    }
    return $content;
}
add_filter( 'default_content', 'fgs_editor_content', 10, 2 );

//* Hide other users squares
function fgs_set_only_author( $wp_query ) {
    global $current_user;
    $user = wp_get_current_user();
    if( is_admin() && !current_user_can('edit_others_posts') && (in_array( 'fgs-admin', (array) $user->roles)) ) {
        $wp_query->set( 'author', $current_user->ID );
    }
}
add_action('pre_get_posts', 'fgs_set_only_author' );

?>