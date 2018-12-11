<?php
		if(!class_exists('F2DASH_wp') )
		{
			echo 'Forms plugin not activated';
			die();
		}	
		switch ($view)
		{
			case "forms":
			{			

				if($isMyProfile==true)
				{
					echo '<h2>My Sign Offs</h2>';
				}	
				else
				{
					echo '<h2>Student Sign Offs</h2>';
				}
				

			
				echo form2_draw::drawUserSubmissions($username);				
				break;

			}
			
			case "form-submission":
			{
				$formID = $_GET['formID'];		
				echo form2_draw::drawFormSubmissions($username, $formID);				
				break;
			}

		}
		
		
?>