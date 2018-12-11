<?php
		if(!class_exists('imperialTutors') )
		{
			echo 'Tutors plugin not activated';
			die();
		}		
		
?>

<h2>My Tutor</h2>
<?php

echo '<div class="contentBox">';

if($thisUserTutorUsername<>"")
{
	
	$tutorMeta = get_user_by('login', $thisUserTutorUsername);
	$tutor_wp_userID = '';
	
	$thisTutorInfo = imperialQueries::getUserInfo($thisUserTutorUsername);
	$tutorName = $thisTutorInfo['title'].' '.$thisTutorInfo['first_name'].' '.$thisTutorInfo['last_name'];
	$tutorEmail = $thisTutorInfo['email'];
	echo '<strong>'.$tutorName.'</strong><br/>';
	
	
	if(isset($tutorMeta->ID) )
	{
		
		$tutor_wp_userID = $tutorMeta-> ID;
	
	}

	$args = array(
		"userID"	=> $tutor_wp_userID,
	);
	$avatarURL = get_user_avatar_url( $args);
	$hash = create_hash();
	$avatarURL = $avatarURL.'?hash=$hash';
	
	echo '<a href="?username='.$thisUserTutorUsername.'">';				
	echo '<div class="profilePageAvatar" style="text-align:left">';
	echo '<div class="rounded-image">';
	echo '<img src="'.$avatarURL.'" class="profile-avatar">';		
	echo '</div></div></a>';

	echo '<a href="">'.$tutorEmail.'</a>';	
	

	if(isset($_SESSION['tutorUsername']) )
	{
		
		
		// Check for slots
		if($thisUserTutorUsername==$_SESSION['tutorUsername'])
		{
			
			
			echo '<hr/><strong>Book a meeting</strong><br/>';
			// Check that there are meeting slots available
			$upcomingSlots = imperialTutorQueries::getActiveSlots($thisUserTutorUsername);		
			
			$availableSlots = 0;
			foreach ($upcomingSlots as $slotInfo)
			{
				if($slotInfo->tuteeUsername=="")
				{
					$availableSlots++;
				}
				
			}
			if($availableSlots>=1)
			{
				echo '<br/><span class="smallText">'.$availableSlots.' meeting slots available</span><br/>';
				echo '<br/><a href="' .get_permalink(). '?view=tutor-bookings&username='.$thisUserTutorUsername.'" class="imperial-button">Book a meeting</a>';
			}
			else
			{
				echo 'There are no available timeslots to meet your tutor';
			}
		}
	}
	
	
	
	
}
else
{
	echo '<span class="greyText">No tutor found</span>';
}	
echo '</div>';
		
		
?>