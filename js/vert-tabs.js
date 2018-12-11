jQuery(document).ready(function($) {
	
	
	/* Vert Menu tabs - jquery NON href */
	
	// Get the query string and look for tab start
	let params = new URLSearchParams(location.search.slice(1));
	
	var startTab = params.get('tab');
	
	if(startTab==null)
	{
		startTab=1;
	}
		
	startTab--; // Take one away as it is array
	
	/* Firstly hide all the tabs then show the first tab */
	$(".imperial-vert-tabs-content").children().hide();	
	$(".imperial-vert-tabs-content > div").eq(startTab).show();

	// Apply class to the active tab
	$(".imperial-vert-tabs-toggle ul li").eq(startTab).addClass("activeTab");	
	
	//Listener for tabs
	$(".imperial-vert-tabs-toggle ul li").on("click", function() {
		var clickedTab = $(this).index();
		
		/* Remove the active class from all tabs and readd to clicked tab */
		$(".imperial-vert-tabs-toggle ul li").removeClass("activeTab");
		$(".imperial-vert-tabs-toggle ul li").eq(clickedTab).addClass("activeTab");
		
		// Hide all content for divs in the main wrap
		$(".imperial-vert-tabs-content").children().hide();
		
		
		// Finally show the div we have clicked
		$(".imperial-vert-tabs-content > div").eq(clickedTab).show();
	});
	
	

	/* Code below for STATIC menu items i.e. when clicked load anew page */
	
	
	// Add Chevron to LI items
	$('.imperial-vert-menu-items li').append('<i class="fas fa-chevron-right"></i>');
	
	
	// Listener for clicable admin menu stuff	
	$(".imperial-vert-menu-items li").on("click", function() {
		
		
		// Get the menu item text
		var menuText = $(this).html();
		menuText = menuText.split('<')[0];
		menuText = menuText.replace(/\s+/g, '-').toLowerCase();
		
		// Get the Page URL		
		var pageURL = [location.protocol, '//', location.host, location.pathname].join('');		
		// Redirect to the same page with query string as menu item
		window.location.href = pageURL+"?view="+menuText;
		
		
		
	});
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
});