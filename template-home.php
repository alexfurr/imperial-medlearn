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


// Get Current User Meta
if(isset($_SESSION['deptID']) )
{
	$deptID = $_SESSION['deptID'];
}

?>
<main id="content">
<div class="entry-content">
<div id="medlearn-welcome">
Welcome to MedLearn
</div>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; endif; ?>

<div class="imperial-homepage">


<!-- Start of left Col -->
<div class="home-main">

<div class="imperial-news box-shadow">
<div class="box-heading">Latest News</div>
<?php $the_query = new WP_Query( 'posts_per_page=2' ); ?>
<?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
<div class="home-news-wrap">
<div class="home-news-date">
<?php
$myDate =  get_the_date();
echo $myDate;
?>
</div>
<div class="home-news-title">
<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
</div>
<div class="home-news-except">
<?php the_excerpt(__('(Read more…)')); ?>
</div>

<div class="home-news-categories">
Categories : 
<?php
echo get_the_category_list('|');
?>
</div> <!-- End of Categories -->
</div> <!-- End of News Wrap -->

<?php 
endwhile;
wp_reset_postdata();
?>
</div>

<br/>
<div class="imperial-useful-sites box-shadow">


<?php
$usefulSitesArray = array
(
	array ("Blackboard", "bb_logo.png", "Your Learning Environment", "https://bb.imperial.ac.uk"),
	array ("Sofia", "sofia_logo.png", "Your Curriculum", "https://sofia.med.ic.ac.uk/"),
	array ("icsmsu", "su_logo.png", "Your Students Union", "http://www.icsmsu.com/"),
	array ("Panopto", "panopto_logo.png", "Recorded Lectures", "http://imperial.cloud.panopto.eu "),
	array ("eService", "eservice_logo.png", "My Student Records", "http://www.imperial.ac.uk/studenteservice"),
	array ("Library", "library_logo.png", "Library Services", "http://www.imperial.ac.uk/admin-services/library/"),

	
);

?>
<div class="box-heading">Useful Sites</div>

<div class="useful-sites-content">


<?php
foreach ($usefulSitesArray as $siteInfo)
{
	$siteName = $siteInfo[0];
	$siteLogo = $siteInfo[1];
	$siteText = $siteInfo[2];
	$siteURL = $siteInfo[3];
	echo '<a href="'.$siteURL.'">';
	echo '<div>';
	echo '<div class="useful-sites-logo">';
	echo '<img src="'.get_stylesheet_directory_uri().'/assets/home-images/'.$siteLogo.'">';
	echo '</div>';
	echo '<div class="useful-sites-name">'.$siteName.'</div>';
	echo '<div class="useful-sites-info">'.$siteText.'</div>';

	echo '</div>';
	echo '</a>';
}
?>

</div><!-- End of useful sites content -->



</div> <!-- End of Main left box wrap --->

</div>	






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