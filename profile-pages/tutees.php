<h2>My Tutees</h2>

<?php

$myTutees = imperialTutorQueries::getMyTutees($username);





$tuteeCount = count($myTutees);

if($tuteeCount==0)
{
	echo 'You have no tutees';
}
else
{


	echo '<a href="?view=tutee-timeslots&username='.$username.'" class="imperial-button"><i class="fas fa-calendar-alt"></i> Tutee Appointments</a>';


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
			$args = array(			
				"CID"		=> $cid,
			);
			$avatarURL = get_user_avatar_url( $args);
			
			$tuteeStr.= '<a href="'.get_site_url().'/profile/?username='.$studentUsername.'">';
			$tuteeStr.= '<div class="myTuteesStudent">';
			$tuteeStr.= '<div class="studentAvatar">';
			$tuteeStr.= '<div class="profilePageAvatar">';
			$tuteeStr.='<div class="rounded-image">';
			$tuteeStr.='<img src="'.$avatarURL.'"></div></div></div>';
			$tuteeStr.= '<div class="studentName">'.$firstName.' '.$lastName.'</div>';
			$tuteeStr.= '<div class="studentID">'.$cid.'</div>';
			$tuteeStr.= '</div>';
			$tuteeStr.= '</a>';
		}
		
		echo '<div class="contentBox">';
		echo  '<h3>Year '.$yos.'</h3>';
		
		echo '<a href="mailto:'.$emailStr.'" class="imperial-button"><i class="fas fa-envelope"></i> Email tutees in Year '.$yos.'</a>';
		
		echo '<div class="myTuteesOuterWrap">';
		echo '<div class="myTuteesWrap">';
		echo $tuteeStr;	
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	}
		
?>