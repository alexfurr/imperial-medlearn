jQuery(document).ready(function($) {
	
	
	
	// Get the query string and look for tab start
	let params = new URLSearchParams(location.search.slice(1));
	
	var startTab = params.get('tab');
	
	if(startTab==null)
	{
		startTab=1;
	}
		
	startTab--; // Take one away as it is array
	

	
	/* Firstly hide all the tabs then show the first tab */
	$(".ekTabsContent").children().hide();	
	$(".ekTabsContent > div").eq(startTab).show();
	
	// Apply class to the active tab
	$(".ekTabs ul li").eq(startTab).addClass("activeTab");
	
	
	//Listener for tabs
	$(".ekTabs ul li").on("click", function() {
		var clickedTab = $(this).index();
		
		/* Remove the active class from all tabs and readd to clicked tab */
		$(".ekTabs ul li").removeClass("activeTab");
		$(".ekTabs ul li").eq(clickedTab).addClass("activeTab");
		
		// Hide all content for divs in the main wrap
		$(".ekTabsContent").children().hide();
		
		
		
		// Finally show the div we have clicked
		$(".ekTabsContent > div").eq(clickedTab).show();
	});
	
});