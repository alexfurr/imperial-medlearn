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



sdfsdfsd


</div>

</div> <!-- End of Flex container -->

</div> <!-- End of entry content -->
</main>
<?php  get_sidebar(); ?>
<?php get_footer(); ?>