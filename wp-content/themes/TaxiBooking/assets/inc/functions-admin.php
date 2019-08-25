<?php 
/*
 =================
	ADMIN PAGE
 =================
*/

 function add_admin_page(){
 	add_menu_page( 'Book Orders', "Orders", 'manage_options', 'book_orders', 'book_orders_create_page', get_template_directory_uri().'/assets/img/menu-icon.png', 100 );
 }

add_action('admin_menu', 'add_admin_page');

 function book_orders_create_page(){
 	// Generation of admin page
 	require_once get_template_directory().'/assets/inc/car-book-admin/orders-admin.php';
 }