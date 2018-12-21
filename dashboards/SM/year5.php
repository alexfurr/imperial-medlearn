<?php 
/* Template Name: Home page */ 
 get_header(); ?>
<?php
 
$currentAcademicYear = get_site_option("current_academic_year");
$acYearText = imperialNetworkUtils::getNiceAcademicYear($currentAcademicYear);

$username="";
$deptID="";
// Get Current User Meta
if(isset($_SESSION['username']) )
{
	$username = $_SESSION['username'];
}

$studentID = $_SESSION['userID'];

// Get Current User Meta
if(isset($_SESSION['deptID']) )
{
	$deptID = $_SESSION['deptID'];
}

?>
<main id="content">
<div class="entry-content">


<div class="imperial-homepage">


<!-- Start of left Col -->
<div class="home-main">



<div class="dashboardHomeLeftDiv">
<div class="dashTitle">Latest News</div>
<div class="latestNews">



<?php
// Get the latest news

$myNewsArray = array(); // Create news array for merging news items

// Get teh Current Site URL
$rootBlog = get_site_option('imperial_root_blog');
$homeURL = get_home_url($rootBlog);


$args = array( 'posts_per_page' => 3, 'category_name' => "year-5, news, students" );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) :
	setup_postdata( $post );
	
	
	// Add to the master array
	
	$newsURL = get_the_permalink();
	$postID = get_the_ID();
	$newsTitle = get_the_title();
	$newsExcerpt = get_the_excerpt();
	$newsDate = get_the_date();	
	$categories = get_the_category();
	$catArray = array();
	$UKdate = imperialNetworkUtils::getUKdate($newsDate);
	
	
	foreach ($categories as $catMeta)
	{
		$catSlug = $catMeta->slug;
		$catName = $catMeta->name;
		
		$catArray[] = array(
			"slug"	=> $catSlug,
			"name"	=> $catName,
		);
	}
	
	
	
	$blogID = get_current_blog_id();
	
	$imageInfo = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
	$image_url = $imageInfo[0];
	
	
	// Ge tthe categories involved as well
	
	$myNewsArray[] = array
	(
		"postID"		=> $postID,
		"blogID"		=> $blogID,	
		"newsTitle"		=> $newsTitle,
		"newsExcerpt"	=> $newsExcerpt,
		"newsDate" 		=> $newsDate,
		"newsURL" 		=> $newsURL,
		"newsImage"		=> $image_url,
		"catArray"		=> $catArray,
		"homeURL"		=> $homeURL,
		"UKdate"		=> $UKdate,
	);
	
endforeach;

// Also get the news from the NEWS blog


$newsBlogID = 52;
switch_to_blog($newsBlogID);

$args = array( 'posts_per_page' => 3, 'category_name' => "year-5, news, students" );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) :
	setup_postdata( $post );
	
	
	// Add to the master array
	
	$newsURL = get_the_permalink();
	$newsTitle = get_the_title();
	$newsExcerpt = get_the_excerpt();
	$newsDate = get_the_date();
	$postID = get_the_ID();
	$categories = get_the_category();
	$catArray = array();	
	$UKdate = imperialNetworkUtils::getUKdate($newsDate);

	
	
	
	foreach ($categories as $catMeta)
	{
		$catSlug = $catMeta->slug;
		$catName = $catMeta->name;
		
		$catArray[] = array(
			"slug"	=> $catSlug,
			"name"	=> $catName,
		);
	}
	
	$imageInfo = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
	$image_url = $imageInfo[0];
	
	
	// Get the categories involved as well	
	$myNewsArray[] = array
	(
		"postID"		=> $postID,
		"blogID"		=> $newsBlogID,
		"newsTitle"		=> $newsTitle,
		"newsExcerpt"	=> $newsExcerpt,
		"newsDate" 		=> $newsDate,
		"newsURL" 		=> $newsURL,
		"newsImage"		=> $image_url,
		"catArray"		=> $catArray,
		"homeURL"		=> $homeURL,
		"UKdate"		=> $UKdate,
		
	);
	
endforeach;
restore_current_blog();

// Sort the array by date
$myNewsArray = wp_list_sort( 
    $myNewsArray, 
    'UKdate', 
    'DESC' 
);



$currentNewsItem=1;
foreach ($myNewsArray as $newsItem)
{
	
	if($currentNewsItem<=2)
	{
	
		$newsTitle = $newsItem['newsTitle'];
		$newsExcerpt = $newsItem['newsExcerpt'];
		$newsDate = $newsItem['newsDate'];
		$newsURL = $newsItem['newsURL'];
		$newsImage = $newsItem['newsImage'];
		$catArray = $newsItem['catArray'];
		$homeURL = $newsItem['homeURL'];
		
		
		$newsExcerpt = wp_trim_words( $newsExcerpt, 15, '...' );
	
		
		
		if($newsImage=="")
		{
			$newsImage = get_stylesheet_directory_uri().'/assets/images/news-default.png';
		}
		
		
		echo '<div class="homeNewsItem">';
		echo '<a href="'.$newsURL.'">';
		echo '<div class="homeNewsWrap">';
		echo '<div class="newsImage">';
		echo '<img src="'.$newsImage.'">';
		echo '</div>';
		echo '<div class="homeNewsContent">';
		echo '<div class="homeNewsDate">'.$newsDate.'</div>';		
		echo '<div class="homeNewsTitle">'.$newsTitle.'</div>';
		echo '<div class="homeNewsExcerpt">'.$newsExcerpt.'<br/>[Read more...]</div>';	
		echo '<div class="homeNewsCategories">';
		echo '<span class="homeCategoriesTitle">Categories : </span>'; 
		foreach ($catArray as $catMeta)
		{
			$catName = $catMeta['name'];
			$catSlug = $catMeta['slug'];
			echo '<a href="'.$homeURL.'/blog/category/'.$catSlug.'" class="catList">'.$catName.'</a> ';
		}
		
		echo '</div>';
		
		
		echo '</div>';
		echo '</div>';
		echo '</a>';
		
		echo '</div>';	
	}
	
	$currentNewsItem++;
	
	
	
}




?>

</div>
</div>


<div class="dashboardHomeLeftDiv">

<div class="dashTitle">My Sign Offs</div>
<?php

$username = $_SESSION['username'];
$formArray = form2_draw::getFormDashboardInfo($username);

echo '<div class="dashboardFormList">';
foreach ($formArray as $catInfo)
{	
	$catName = $catInfo['catName'];	
	$catForms = $catInfo['forms'];
	
	$formCount = 0;
	$submissionCount = 0;
	
	$totalRequiredSubmissions = 0;

	foreach ($catForms as $formInfo)
	{
		$formCount ++;
		
		$requiredSubmissions = $formInfo['requiredSubmissions'];
		$totalSubmissions = $formInfo['totalSubmissions'];
		

		$totalRequiredSubmissions = $totalRequiredSubmissions + $requiredSubmissions;
		
		if($formInfo['hasCompleted']==true)
		{
			$submissionCount = $submissionCount + $requiredSubmissions;
		}
		else
		{
			$submissionCount = $submissionCount + $totalSubmissions;
		}
	}
	
	
	// Precent Complete
	$percentComplete = round( ($submissionCount/$totalRequiredSubmissions)*100, 0);								

	$args = array(
		"number" => $percentComplete,
		"text" => $percentComplete.'%',

	);
	
	$percentbar= imperialNetworkDraw::drawRadialProgress($args);

	
	
	
	echo '<a href="profile/?view=forms">';
	echo '<div class="dashboardFormListItem">';
	echo '<div class="catRadialPercent">';
	echo $percentbar;
	echo '</div>';
	echo '<div class="catName">';
	echo $catName;
	echo '</div>';
	echo '<div class="formCount">';
	echo $submissionCount.' of '.$formCount.' forms complete';
	echo '</div>';
	echo '</div>';
	echo '</a>';
	
}

echo '</div>';



?>



</div>





</div> <!-- End of Main left box wrap --->


<!-- Start of right Col -->
<div class="home-sidebar"> 

<?php
$homeHelpTopicsArray = array
(
	array(
	"topicName" => 'My Courses',
	"topicInfo"	=> 'Handbooks and courses',
	"topicURL"	=> 'sites',
	"accent"	=> '#A51900',
	),
	
	array(
	"topicName" => 'Help and FAQs',
	"topicInfo"	=> 'Search the help pages',
	"topicURL"	=> 'med-students',
	"accent"	=> '#0F8291',
	),

	
);

// Get teh template URL
$templateImageURL = get_stylesheet_directory_uri().'/assets/home-images/';


$myPlacements =  imperialPlacementsQueries::getStudentPlacements($studentID);

// Get the speicality array for looup


$html='';

		
$currentPlacementInfo = imperialPlacementsQueries::getCurrentNextPlacement($studentID);


if(isset($currentPlacementInfo['current']['agencyID']) )
{
	
	$agencyURL = $currentPlacementInfo['current']['agencyURL'];
	$specialty = $currentPlacementInfo['current']['specialty'];
	$parentName = $currentPlacementInfo['current']['parentName'];
	$rotation = $currentPlacementInfo['current']['rotation'];
	$niceEndDate = $currentPlacementInfo['current']['niceEndDate'];
	$specialtyName = $currentPlacementInfo['current']['specialtyName'];
	$currentPlacementStr= '';
	
	
	// CURRENT PLACEMENT
	$currentPlacementStr.= '<div class="topic-box">';
	$currentPlacementStr.= '<a href="'.$agencyURL.'">';
	
	$checkImage = get_stylesheet_directory().'/assets/images/specialties/'.$specialty.'.png';	
	
	$thisImage = $templateImageURL.'my-sites.png';
	// See if the speiaclty image exists
	if (file_exists($checkImage))
	{	
		$thisImage = get_stylesheet_directory_uri().'/assets/images/specialties/'.$specialty.'.png';
	}
	
	
	
	// Get backgorund based on the ROTATION TYPE with default to generic
	$currentPlacementStr.= '<div class="image" style="background-image:url('.$thisImage.')"></div>';
	$currentPlacementStr.= '<div class="box-heading">Current Placement</div>';
	$currentPlacementStr.= '<div class="box-title">'.$specialtyName.'</div>';
	$currentPlacementStr.= '<div class="box-info" style="background:#02893B">';
	$currentPlacementStr.= $parentName.' (Rotation '.$rotation.')<br/>';
	$currentPlacementStr.='Until '.$niceEndDate;
	$currentPlacementStr.= '</div>';
	$currentPlacementStr.= '</a>';
	$currentPlacementStr.= '</div>';			
	
	echo $currentPlacementStr;
	
}


if(isset($currentPlacementInfo['next']['agencyID']) )
{
	
	$nextPlacementStr ='';
	
	$agencyURL = $currentPlacementInfo['next']['agencyURL'];
	$specialty = $currentPlacementInfo['next']['specialty'];
	$parentName = $currentPlacementInfo['next']['parentName'];
	$rotation = $currentPlacementInfo['next']['rotation'];
	$niceEndDate = $currentPlacementInfo['next']['niceEndDate'];
	$niceStartDate = $currentPlacementInfo['next']['niceStartDate'];	
	$specialtyName = $currentPlacementInfo['next']['specialtyName'];

	
	$checkImage = get_stylesheet_directory().'/assets/images/specialties/'.$specialty.'.png';	
	
	$thisImage = $templateImageURL.'my-sites.png';
	// See if the speiaclty image exists
	if (file_exists($checkImage))
	{	
		$thisImage = get_stylesheet_directory_uri().'/assets/images/specialties/'.$specialty.'.png';
	}	
	

	// NEXT PLACEMENT
	$nextPlacementStr.= '<div class="topic-box">';
	$nextPlacementStr.= '<a href="'.$agencyURL.'">';
	// Get backgorund based on the ROTATION TYPE with default to generic
	$nextPlacementStr.= '<div class="image" style="background-image:url('.$thisImage.')"></div>';
	$nextPlacementStr.= '<div class="box-heading">Next Placement</div>';
	$nextPlacementStr.= '<div class="box-title">'.$specialtyName.'</div>';
	$nextPlacementStr.= '<div class="box-info" style="background:#C81E78">';
	$nextPlacementStr.= $parentName.' (Rotation '.$rotation.')<br/>';
	$nextPlacementStr.= $niceStartDate.' - '.$niceEndDate;
	$nextPlacementStr.= '</div>';
	$nextPlacementStr.= '</a>';
	$nextPlacementStr.= '</div>';			
	
	echo $nextPlacementStr;
	
}














	






foreach ($homeHelpTopicsArray as $topicMeta)
{
	$topicName = $topicMeta['topicName'];
	$topicInfo = $topicMeta['topicInfo'];
	$topicURL = $topicMeta['topicURL'];
	$accent = $topicMeta['accent'];	
	
	$imageURL = str_replace(' ', '-', strtolower($topicName));
	$thisImage = $templateImageURL.$imageURL.'.png';

	echo '<div class="topic-box">';
	echo '<a href="'.$topicURL.'">';
	echo '<div class="image" style="background-image:url('.$thisImage.')"></div>';
	echo '<div class="box-title">';
	echo $topicName;
	echo '</div>';
	echo '<div class="box-info" style="background:'.$accent.'">';
	echo $topicInfo.' >> ';
	echo '</div>';
	echo '</a>';
	echo '</div>';	
}


?>



<div> <!-- End of right col -->

</div>

</div>

</div>

</div> <!-- End of Flex container -->

</div> <!-- End of entry content -->
</main>
<?php  get_sidebar(); ?>
<?php get_footer(); ?>