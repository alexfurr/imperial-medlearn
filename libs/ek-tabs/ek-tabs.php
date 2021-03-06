<?php
$ekTabs = new ekTabs();
class ekTabs
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
		//Add Front End Jquery and CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'frontendEnqueues' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueues' ));	
	}
	
	
	function adminEnqueues()
	{
		wp_enqueue_script('jquery');
		wp_register_style( 'ek-tabs-styles',  get_stylesheet_directory_uri() . '/libs/ek-tabs/ek-tabs.css' );
		wp_enqueue_style( 'ek-tabs-styles' );		
		wp_enqueue_script('ek-tabs-js', plugin_dir_url( __FILE__ ) . '/ek-tabs.js', array( 'jquery' ) ); #JS for managing resopnse options
		
	}
	
	function frontendEnqueues ()
	{	
		wp_enqueue_script('jquery');
		
		// Tabs
		wp_register_style( 'ek-tabs-styles',  get_stylesheet_directory_uri() . '/libs/ek-tabs/ek-tabs.css' );
		wp_enqueue_style( 'ek-tabs-styles' );
		wp_enqueue_script('ek-tabs-js', get_stylesheet_directory_uri().'/libs/ek-tabs/ek-tabs.js' );
		
			
		
	}	
}
?>