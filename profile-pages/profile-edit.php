	
		<a href="<?php echo $pageURL; ?>" class="backButton">Back to your profile</a>
		
		
		<?php
		echo  '<form action="?action=updateProfile" method="post" class="imperial-form">';
		echo '<div class="profile-page-wrap">';
		
		
		echo '<h2>Edit my Details</h2>';
		
		echo '<h3>';
		
		
		if($userType==1)
		{
			
			echo $title.' ';
		}
		echo $firstName.' '.$lastName.'</h3>';
		
		
		if($userType>1)
		{
			echo $programmeName.'<br/>';		
			echo 'Year '.$yos.'<br/>';			
			echo 'CID <b>'.$cid.'</b><br/>';
		}
		
		echo '<a href="mailto:'.$email.'">'.$email.'</a>';	
		
		
		// Custom for staff
		if($userType==1)
		{
			echo '<label for="preferredEmail">Preferred Email</label>';
			echo '<input type="text" id="preferredEmail" name="preferredEmail" value="'.$preferredEmail.'">';
			
			echo '<label for="jobTitle">Job Title</label>';
			echo '<input type="text" id="jobTitle" name="jobTitle" value="'.$jobTitle.'">';
			
			
			$titlesArray = array
			(
				"Mr.",
				"Mrs.",
				"Miss.",
				"Ms.",
				"Dr.",
				"Prof.",
				"Rev."
			);
			echo '<label for="title">Title</label>';
			echo '<select name="title" id="title">';
			echo '<option value="">Please Select</option>';
			foreach ($titlesArray as $thisTitle)
			{
				echo '<option value="'.$thisTitle.'"';
				if($title==$thisTitle)
				{
					echo ' selected ';
				}
				
				echo '>'.$thisTitle.'</option>';
			
			}
			echo '</select>';
		}
		
		echo '<br/><br/><label for="aboutMe">About me</label>';
		$settings  = array( 
		'media_buttons' => false ,
		'textarea_rows' => 10,
		'quicktags' => false,
		
		);
		wp_editor( $userBio, "aboutMe", $settings );		
		
		echo '<input type="submit" value="Update Profile">';
		echo '<input type="hidden" value="'.$username.'" name="username">';
		

		
		echo '</div>'; // End of wrap	
		
		wp_nonce_field('profile_edit'); 
		
		echo '</form>';
		