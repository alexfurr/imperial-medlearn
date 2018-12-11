<?php
		if(!class_exists('imperialTutors') )
		{
			echo 'Tutors plugin not activated';
			die();
		}	
		switch ($view)
		{
			case "forms":
			{			

				if($myProfile==true)
				{
					echo '<h2>My Sign Offs</h2>';
				}	
				else
				{
					echo '<h2>Student Sign Offs</h2>';
				}
			
				form2_draw::drawUserSubmissions($username);				
				break;

			}
			
			case "form-submission":
			{
				$formID = $_GET['formID'];		
				echo form2_draw::drawFormSubmissions($username, $formID);				
				break;
			}
			
			
			default:
			
			
				// Get the current username
				$tutorUsername = $_GET['username'];			
				// Get the current month
				
			
				$calendar = imperialTutorsDraw::drawBookingCalendar();
				
				echo '<h2>Pick your booking slot</h2>';

				echo '<div id="calendarWrap">';		
				echo $calendar;			
				echo '</div>';
			
			break;

		}
		
		
?>