<?php

// Include medlearn Classes
include_once get_stylesheet_directory() . '/classes/class-draw.php';
include_once get_stylesheet_directory() . '/classes/class-data-upload.php';
include_once get_stylesheet_directory() . '/classes/class-utils.php';
include_once get_stylesheet_directory() . '/dashboards/class-dashboard.php';


// include the Tabs
if (!class_exists('ekTabs'))
{
	include_once get_stylesheet_directory() . '/libs/ek-tabs/ek-tabs.php';
}







$medlearn = new medlearn();
class medlearn
{
	//~~~~~
	function __construct ()
	{		
		$this->addWPActions();		
	}
	
	
/*	---------------------------
	PRIMARY HOOKS INTO WP 
	--------------------------- */	
	function addWPActions ()
	{
			
		
		//Frontend
		add_action( 'wp_enqueue_scripts', array( $this, 'frontendEnqueues' ), 1 );	
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_child_theme_styles'), PHP_INT_MAX);

		
		//add_action( 'admin_enqueue_scripts', array( $this, 'adminSettingsEnqueues' ) );
        
        if ( is_admin() ) {
            add_action( 'admin_head', array( $this, 'admin_head' ) );
            add_action( 'admin_footer', array( $this, 'admin_footer' ) );
        }
		
		// Chave POSTS to ANNOUCEMENTS
		add_action( 'admin_menu', array($this, 'change_posts_to_news' ) );
		add_action( 'init', array($this, 'change_posts_object' ) );
		
	
		
		
        
	}
	
    
    function admin_head() 
    {
    ?>
        <style>
        
        </style>
    <?php
    }	
    
    
    function admin_footer() 
    {
        global $imperial_adminBar;
        $imperial_adminBar->admin_bar();
    }
    

	function enqueue_child_theme_styles() {
	}	
	
	function adminSettingsEnqueues ()
	{
		//WP includes
		
		global $wp_scripts;	
		$parent_style = 'imperial-theme'; 
	
		
		// get the jquery ui object
		$queryui = $wp_scripts->query('jquery-ui-core');
		// load the jquery ui theme
		$url = "https://ajax.googleapis.com/ajax/libs/jqueryui/".$queryui->ver."/themes/smoothness/jquery-ui.css";	
		wp_enqueue_style('jquery-ui-smoothness', $url, false, null);	


		// Add the admin css		
		wp_enqueue_style( 'admin-child-styles', get_stylesheet_directory_uri() . '/css/admin.css');
		
		// Custom Admin JS
		//wp_enqueue_script('imperial_course_admin_js', get_stylesheet_directory_uri().'/js/admin.js', array( 'jquery' ) );
		
		//wp_enqueue_style( 'progress-bars', get_stylesheet_directory_uri() . '/css/progress_bar.css' );
		//wp_enqueue_style( 'radial-progress', get_stylesheet_directory_uri() . '/css/radial_progress.css' );
		
		
	}	
	
	
	
	
	function frontendEnqueues ()
	{
		//Scripts
		wp_enqueue_script('jquery');

		
		//DataTables js
		//wp_register_script( 'datatables-TEST', ( '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js' ));
		//wp_enqueue_script( 'datatables-TEST' );
		
		//DataTables css
		//wp_enqueue_style('datatables-style','//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css');		
		
		// Custom Admin JS
		wp_enqueue_script('imperial_vert_tabs_js', get_stylesheet_directory_uri().'/js/vert-tabs.js', array( 'jquery' ) );


		// Load imperial parent theme
		wp_enqueue_style( 'imperial-theme', get_template_directory_uri().'/style.css' );
		
		// Load Dashabord sscripts
		wp_enqueue_style( 'medlearn-dashboards', get_stylesheet_directory_uri().'/styles/dashboards.css' );
		wp_enqueue_style( 'medlearn-medhelp', get_stylesheet_directory_uri().'/styles/medhelp.css' );
		
		
		// Load the Google charts
		wp_enqueue_script('google_charts', '//www.gstatic.com/charts/loader.js' );		
		
		
	}	
	
	// These two functions change all POSTS references to NEWS
	static function change_posts_to_news() {
		global $menu;
		global $submenu;
		$menu[5][0] = 'Announcements';
		$submenu['edit.php'][5][0] = 'Announcements';
		$submenu['edit.php'][10][0] = 'Add Announcement';
	}
	
	static function change_posts_object() {
		global $wp_post_types;
		$labels = &$wp_post_types['post']->labels;
		$labels->name = 'Announcements';
		$labels->singular_name = 'Announcement';
		$labels->add_new = 'Add Announcement';
		$labels->add_new_item = 'Add Announcement';
		$labels->edit_item = 'Edit Announcements';
		$labels->new_item = 'Announcements';
		$labels->view_item = 'View Announcements';
		$labels->search_items = 'Search Announcements';
		$labels->not_found = 'No Announcements found';
		$labels->not_found_in_trash = 'No Announcements found in Trash';
		$labels->all_items = 'All Announcements';
		$labels->menu_name = 'Announcements';
		$labels->name_admin_bar = 'Announcements';
	}	
		
	
}









?>