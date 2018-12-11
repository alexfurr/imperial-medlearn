<?php
		echo '<div class="profile-page-wrap">';
		
		
		// Check if they are dept admin


	//	$isDeptAdmin = $_SESSION['isDeptAdmin'];
	//	$isNetworkAdmin = $_SESSION['isNetworkAdmin'];
		
		
		if($isNetworkAdmin==true)
		{			
			echo '<div class="smallText">';
			if($_SESSION['username']==$username)
			{
				echo 'You are logged in as this user';
			}
			else
			{
				echo '<a href="?username='.$username.'&action=adoptRole">Swap to this user</a>';
			}
			echo '</div>';
		}
		
		echo '<h2>'.$fullname.'</h2>';
		
		/* Tutor Booking */
		if(class_exists('imperialTutors') )
		{
				
			if($isMyTutor==true)
			{
				echo '<div class="contentBox"><strong>'.$fullname.' is your tutor</strong><br/>';
				echo '<a href="' .get_permalink(). '?view=tutor-bookings&username='.$username.'" class="imperial-button">Book a meeting</a>';
				
				
				// Check for upcoming meetings
				$upcomingMeetings = imperialTutorQueries::getMyUpcomingSlots($loggedInUsername, $username);
				
				$meetingCount = count($upcomingMeetings);
				
				if($meetingCount>=1)
				{
					echo '<h3>Upcoming Tutor Appointments</h3>';
					
					echo '<table class="imperial-table">';
					foreach($upcomingMeetings as $meetingInfo)
					{
						$slotID = $meetingInfo['slotID'];
						$slotDate = $meetingInfo['slotDate'];
						$duration = $meetingInfo['duration'];
						$location = $meetingInfo['location'];
						$slotDateText = date('jS F Y', strtotime($slotDate) );
						$slotDateTextTime = date('g:i a', strtotime($slotDate) );
						
						echo '<tr>';
						echo '<td>'.$slotDateText.'</td>';
						echo '<td>'.$slotDateTextTime.'</td>';
						echo '<td>'.$duration.' mins</td>';						
						echo '<td>'.$location.'</td>';
						echo '<td>'.$location.'</td>';
						$formAction = '?username='.$username;
						echo '<td><a href="javascript:confirmSlotCancelCheck(\''.$slotID.'\', \''.$formAction.'\')" class="imperial-button">Cancel</a></td>';
						
						echo '</tr>';
					}			
					echo '</table>';						
				}

				echo '</div>';
			
			}
		}		
		

		if($userType>1)
		{
			echo '<span class="smallText">CID '.$cid.'</span><br/>';			
			echo '<strong>Year '.$yos.'</strong><br/>';
			echo '<span class="greyText">'. $programmeCode .' : '.$programmeName.'</span><br/>';
		}
		
		if($preferredEmail)
		{
			$email = $preferredEmail;
		}
		
		echo '<h4>Email</h4>';
		echo '<a href="mailto:'.$email.'">'.$email.'</a>';


		
		if($userBio)
		{
			echo '<hr/><h4>About Me</h4>';
			echo wpautop($userBio);
		}

		if($userType==1)
		{
			if($isDeptAdmin==true)
			{
				
				// See if they are a tutor
				$myTutees = imperialTutorQueries::getMyTutees($loggedInUsername);
				
				$tuteeCount = count($myTutees);
				$masterCount = 0;
				
				if($tuteeCount>=1)
				{
					foreach ($myTutees as $yosTutees)
					{
						$tempCount = count($yosTutees);
						$masterCount= $masterCount +$tempCount;
					}
					echo '<br/><br/>This member of staff is a tutor with '.$masterCount.' tutees.';
				}
			}
			
		}

		echo '</div>'; // End of wrap
		
?>