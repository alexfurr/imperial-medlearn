<?php 
get_header(); ?>
<div id="imperial_page_title">
<h1 class="entry-title"><?php the_title(); ?></h1>
</div>
<main id="content">
<div class="entry-content">

<?php
$current_user = wp_get_current_user();
$username = $current_user->user_login;
$userID = $current_user->ID;
$userMeta = imperialQueries::getUserInfo($username);
$myDeptID = $userMeta['deptID'];
$deptInfo = imperialQueries::getDeptInfo($myDeptID);
$deptName = $deptInfo['deptName'];

// Override dept ID
$myDeptID = "SM";
$deptName = "School of Medicine";

$view = "usersites";



if(isset($_GET['view']) )
{
	$view = $_GET['view'];
}

$currentAcademicYear = get_site_option('current_academic_year');

if(isset($_GET['acyear']) )
{
	$thisAcademicYear=$_GET['acyear'];
}
else
{
	$thisAcademicYear=$currentAcademicYear;
}


switch ($view)
{
	
	case "deptsites":
		// Get all Sites for this dept ID and academic year		
		echo  drawAcYearMenu($currentAcademicYear, $thisAcademicYear, $deptName, $view);		
		echo imperialNetworkDraw::drawDeptModules($myDeptID, $thisAcademicYear);
	break;
	
	
	/*
	case "bycategory":
		// Get all Sites by category for this dept ID and academic year		
		
		
		echo  drawAcYearMenu($currentAcademicYear, $thisAcademicYear, $deptName, $view);
		echo imperialNetworkDraw::drawDeptModulesByCategory($myDeptID, $thisAcademicYear);
	break;
	
	*/
	
	
	case "usersites":
	default:
	
		echo  drawAcYearMenu($currentAcademicYear, $thisAcademicYear, $deptName, $view);
		echo imperialNetworkDraw::drawUserSites($userID);
	
	break;
	
}					

?>

 
</div><!-- End of content -->


</main>
<?php  get_sidebar(); ?>
<?php get_footer(); ?>
<?php
function drawAcYearMenu($currentAcademicYear, $viewingAcYear, $deptName, $viewType)
{
	
	
	$html='';
	$acYearsButtons = '';
	
	$acYearsArray = imperialNetworkUtils::getAcademicYearsArray();
	
	// Get the max Ac year position (current ac year plus one)
	$maxAcYearPos = array_search($currentAcademicYear, array_keys($acYearsArray))+1; // Up a year
	$maxAcYearPos = array_search($currentAcademicYear, array_keys($acYearsArray)); // This year only
	
	$currentAcYearPos=0;
	foreach($acYearsArray as $academicYear => $niceAcademicYear)
	{
				
		if($currentAcYearPos<=$maxAcYearPos)
		{			
			$acYearsButtons.= '<a href="?view='.$viewType.'&acyear='.$academicYear.'" class="imperial-button';
			if($viewingAcYear==$academicYear){$acYearsButtons.= ' selected ';}
			$acYearsButtons.='"> '.$niceAcademicYear.'</a>';
		}

		$currentAcYearPos++;
	}
	$thisNiceAcYear = $acYearsArray[$viewingAcYear];
	
	switch ($viewType)
	{
			case "deptsites":
		//	$html.= '<h2>Current viewing all Courses for '.$deptName.' ('.$thisNiceAcYear.') </h2>';
			
			$html.= '<a href="?acyear='.$viewingAcYear.'">My Courses</a> | ';
			$html.= 'All Courses';
//			| ';			
//			$html.= '<a href="?view=bycategory&acyear='.$viewingAcYear.'">View by Theme</a><br/><br/>';	
			$html.='<br/><br/>'.$acYearsButtons;	
			
			break;
			
			
			/*
			case "bycategory":
			$html.= '<h2>Current viewing : '.$deptName.' courses by theme ('.$thisNiceAcYear.') </h2>';
			
			$html.= '<a href="?acyear='.$viewingAcYear.'">View my sites</a> | ';
			$html.= '<a href="?view=deptsites&acyear='.$viewingAcYear.'"> Department Sites  | </a>';			
			$html.= 'View by Theme</a><br/><br/>'	;
			$html.=$acYearsButtons;	
			
			
			
			break;
			*/
			
			default:
		//	$html.= '<h2>My Courses </h2>';
			
			$html.= 'My Courses | ';
			$html.= '<a href="?view=deptsites&acyear='.$viewingAcYear.'"> All Courses </a>';			
			//$html.= '<a href="?view=bycategory&acyear='.$viewingAcYear.'">View by Theme</a><br/>'	;			
			break;
	}
	
	
	
	return $html;
}
?>