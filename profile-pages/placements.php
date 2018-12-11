<?php
		if(!class_exists('imperialPlacementsDraw') )
		{	
			echo 'Placements plugin not activated';
			die();	
		}
		else
		{
			if($isMyProfile==true)
			{
				echo '<h2>My Placements</h2>';
			}	
			else
			{
				echo '<h2>Student placements</h2>';
			}			
			
			echo imperialPlacementsDraw::drawUserPlacements($cid);
		}
?>