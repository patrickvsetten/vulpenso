<?php

function create_landingspages() {
	register_post_type( 'Landingspages', [
		'labels' => array(
			'name' => __( 'Landingspages' ),
			'singular_name' => __( 'Landingspages' ),
			'add_new' => __('Nieuw item'),
			'add_new_item' => __('Nieuw item'),
			'edit_item' => __('Bewerk item'),
			'new_item' => __('Nieuw item'),
			'view_item' => __('Bekijk item'),
			'search_items' => __('Zoek item'),
			'not_found' =>  __('Geen item gevonden'),
			'not_found_in_trash' => __('Geen item gevonden'),
			'parent_item_colon' => ''
		),
		'public' => true,
		'has_archive' => false,
		'show_ui' => true,
		'query_var' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'show_in_rest' => true,
		'supports' => array('title','thumbnail','revisions', 'editor', 'page-attributes'),
		'rewrite' => [
            'slug'          => false,
            'with_front'    => false,
            'feeds'         => false,
        ],
		'menu_icon' => 'dashicons-text-page',
	]);
}

function na_remove_slug( $post_link, $post, $leavename ) {

  if ( 'landingspages' != $post->post_type || 'publish' != $post->post_status ) {
      return $post_link;
  }

  $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );

  return $post_link;
}
add_filter( 'post_type_link', 'na_remove_slug', 10, 3 );

function na_parse_request( $query ) {

  if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
      return;
  }

  if ( ! empty( $query->query['name'] ) ) {
      $query->set( 'post_type', array( 'post', 'landingspages') );
  }
}
add_action( 'pre_get_posts', 'na_parse_request' );

// register_taxonomy( "landingspages-category",
// 		array("landingspages" ),
// 		array(
// 				"hierarchical" => true,
// 				'show_in_rest' => true,
// 				"labels" => array('name'=>"Categorie",'add_new_item'=>"Nieuwe categorie"),
// 				"singular_label" => __( "Field" ),
// 				"rewrite" => array( 'slug' => 'categorie', 'with_front' => false), // This controls the base slug that will display before each term
// 		)
// );

add_action( 'init', 'create_landingspages' );
