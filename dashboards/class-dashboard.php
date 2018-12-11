<?php


$medlearnDashboard = new medlearnDashboard();
class medlearnDashboard
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
			
		add_filter( 'template_include', array($this, 'my_callback' ) );
  
	}   

	
	
	// Loadup dashboard for year group
	function my_callback( $original_template )
	{
				
		if ( is_front_page() )
		{
			

			// Get the year of study
			$userMeta = imperialQueries::getUserInfo($_SESSION['username']);
            
            
			$yos = $userMeta['yos'];
            $userType = $userMeta['user_type'];
            

			
			// Check the root blog ID
			
			$rootBlogID = get_site_option("imperial_root_blog");
			$thisBlogID = get_current_blog_id();
			
			
			// Only do the redirect on the root blog
			if($rootBlogID<>$thisBlogID)
			{

				return $original_template;
			}
			

			if($userType<>1)
            {
                switch ($yos)
                {

                    case "6":

                        $new_template = dirname(__FILE__) . '/SM/year6.php';

                        return $new_template;


                    break;

                    case "5":

                        $new_template = dirname(__FILE__) . '/SM/year5.php';

                        return $new_template;


                    break;

                    default:

                        return $original_template;

                    break;

                }	
            }
            
            if($userType==1)
            {

                $new_template = dirname(__FILE__) . '/SM/staff.php';
					
				return $new_template; 
            }

		
		}
		else
			
		{
			return $original_template;
		}

	}	
	
	

}









?>