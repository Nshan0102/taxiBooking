<?php 
require get_template_directory().'/assets/inc/functions-admin.php';

add_action('wp_enqueue_scripts', 'style_theme');
add_filter('document_title_separator', 'my_sep');

function my_sep($sep){
	$sep = ' | ';
	return $sep;
}

function style_theme(){
	wp_enqueue_style( 'style', get_stylesheet_uri());
	wp_enqueue_style( 'default', get_template_directory_uri().'/assets/css/bootstrap.min.css');
	wp_enqueue_style( 'fonts', get_template_directory_uri().'/assets/css/style.css');
}
































 ?>