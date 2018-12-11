<?php 
get_header();
?>
<div id="imperial_page_title">
<h1 class="entry-title">My Tutees</h1>
</div>
<main id="content">
<div class="entry-content">

<?php

$myTutees = imperialTutorQueries::getMyTutees($loggedInUsername);

foreach ($myTutees as $yos => $myTutees)
{
	$tuteeStr = '';
	$emailStr = '';
	
	foreach ($myTutees as $studentInfo)
	{
		$firstName = $studentInfo['first_name'];
		$lastName = $studentInfo['last_name'];
		$cid = $studentInfo['userID'];
		$studentUsername = $studentInfo['username'];
		$email = $studentInfo['email'];
		
		
		$emailStr.=$email.'; ';
		// Get the wordpress user ID if it exists
		$wpUserMeta = get_user_by('login', $studentUsername);
		/*
		$wpID=0;
		if($wpUserMeta)
		{
			$wpID = $wpUserMeta->ID;
		}
		*/
		$avatar = get_user_avatar( $cid );
		
		$tuteeStr.= '<a href="'.get_site_url().'/profile/?username='.$studentUsername.'">';
		$tuteeStr.= '<div class="myTuteesStudent">';
		$tuteeStr.= '<div class="studentAvatar">'.$avatar.'</div>';
		$tuteeStr.= '<div class="studentName">'.$firstName.' '.$lastName.'</div>';
		$tuteeStr.= '<div class="studentID">'.$cid.'</div>';
		$tuteeStr.= '</div>';
		$tuteeStr.= '</a>';
	}
	
	echo '<div class="contentBox">';
	echo  '<h2>Year '.$yos.'</h2>';
	
	echo '<a href="mailto:'.$emailStr.'" class="imperial-button"><i class="fas fa-envelope"></i> Email tutees in Year '.$yos.'</a>';
	
	echo '<div class="myTuteesOuterWrap">';
	echo '<div class="myTuteesWrap">';
	echo $tuteeStr;	
	echo '</div>';
	echo '</div>';
	echo '</div>';
}






?>

</div>
</main>
<?php  get_sidebar(); ?>
<?php get_footer(); ?>