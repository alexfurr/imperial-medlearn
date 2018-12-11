<?php 
 get_header();


// Check if they are a sys admin or faulcty admin.
// If not the exit

if(!class_exists('imperialQueries') )
{
	echo 'Imperial Network Plugin not active';
	die();
}


$canViewPage=false;
// Get admin list for this dept
if(isset($_GET['deptID']) )
{
	$deptID = $_GET['deptID'];
}
else
{
	$deptID = "WM"; // DEFUALT TO MEDICINE
}

// Check if is dept admin
$isDeptAdmin = $_SESSION['isDeptAdmin'];;
				
if($isDeptAdmin==true)
{
	$canViewPage=true;
}	

// Finally see if they are network admin
if(isset($_SESSION['isNetworkAdmin']) ) 
{
	
	if($_SESSION['isNetworkAdmin']==true)
	{
		$canViewPage=true;
	}
}


if($canViewPage==false)
{
	$siteRoot =  get_site_url();
	echo '
	<script>	
	window.location.replace("'.$siteRoot.'");
	</script>
	';

	die();
}



// Get the dept Name
$deptInfo = imperialQueries::getDeptInfo($deptID);
$deptName = $deptInfo['deptName'];


if(isset($_GET['action']) )
{
	$myAction = $_GET['action'];
	switch ($myAction)
	{
		case "addAdmin":
			$username = $_POST['username'];
			
			imperialNetworkActions::addDeptAdmin($deptID, $username);
			
		break;
	}
}


$view='';
if(isset($_GET['view']) )
{
	$view=$_GET['view'];
}
else
{
	$view = 'students';
}



?>
 <div id="imperial_page_title">
<h1 class="entry-title"><?php  echo $deptName.' Admin'; ?></h1>
</div>

<main id="content">



    <div class="grid-wrapper">
        <div class="grid-sidebar">
            <div class="nav-toggle-wrap"><div id="nav_toggle_button"><i id="nav_toggle_icon" class="fas fa-ellipsis-v"></i></div></div>
            <div class="gridNav">
                <div class="imperial-vert-tabs-menu">
                    <ul class="chevronMenu imperial-vert-menu-items">
					<?php
					$menuItems = array(
					"Students",
					"Forms",
					"Reports",
					"Administrators",
					"Tutors",
					"Data",
					
					);
					
					foreach($menuItems as $menuItem)
					{
						echo '<li';
						
						if($view==strtolower($menuItem) )
						{
							echo ' class="activeTab" ';
						}
						
						echo '>'.$menuItem.'</li>';
					}
					?>
                    </ul>
                </div>
            </div>
        </div>



        <div class="grid-content">
            <div class="entry-content">
                <div class="">
					<?php


					$includeFile = get_stylesheet_directory() . '/admin-pages/'.$view.'.php';
				
					if(file_exists($includeFile) )
					{
						include_once ($includeFile);
					}
					else
					{
						echo 'Include Page does not exist<br/>"/admin-pages/'.$view.'.php';
					}
					?>

                </div><!-- End of imperial-vert-tabs-content -->
            </div>
        </div>

     
    </div><!-- End of grid-wrapper -->

</main>


<script>
jQuery( document ).ready( function () {
    jQuery('#nav_toggle_button').on( 'click', function ( e ) {
        jQuery('.grid-sidebar').toggleClass('mobile-mode-wide');
        
        var current_rotation = jQuery('#nav_toggle_icon').attr('data-fa-transform' );
        if ( 'rotate-90' === current_rotation ) {
            jQuery('#nav_toggle_icon').attr('data-fa-transform', 'rotate-0' );
        } else {
            jQuery('#nav_toggle_icon').attr('data-fa-transform', 'rotate-90' );
        }
    });
    jQuery('.imperial-vert-tabs-menu li').on( 'click', function ( e ) {
         jQuery('.grid-sidebar').removeClass('mobile-mode-wide');
         jQuery('#nav_toggle_icon').attr('data-fa-transform', 'rotate-0' );
    });
});
</script>




<style>
#content {
	width:100%;
}
</style>

<?php get_footer(); ?>