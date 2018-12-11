<?php 
/* Template Name: My Tutor */ 
 get_header(); ?>
 <div id="imperial_page_title">
<h1 class="entry-title">My Tutor</h1>
</div>
<main id="content">
<div class="entry-content">



<?php
 
$currentAcademicYear = get_site_option("current_academic_year");
$acYearText = imperialNetworkUtils::getNiceAcademicYear($currentAcademicYear);

// Get Current User Meta
$loggedInUsername = $_SESSION['username'];


// Get my tutor

$loggedInUsername = "cb4115";
$tutorInfo = imperialTutorQueries::getMyTutor($loggedInUsername);

$tutorUsername = $tutorInfo['username'];

echo $tutorUsername;

?>

</div> <!-- End of entry content -->
</main>
<?php  get_sidebar(); ?>
<?php get_footer(); ?>