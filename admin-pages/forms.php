<?php
		$page='';
		if(isset($_GET['page']) )
		{
			$page = $_GET['page'];
		}
		
		switch ($page)
		{
			case "formCats":
			{
				echo form2_drawPages::drawFormCategoriesPage();
				break;
			}
			case "reports":
			{
				form2_drawPages::drawCatReports();
				break;
			}	
			case "rotations":
			{
				form2_drawPages::drawRotations();
				break;
			}	
			
			case "singleForm":
			{
				form2_drawPages::drawSingleForm();
				break;
			}				
			case "forms":
			{
				form2_drawPages::drawForms();
				break;
			}	
			
			case "submissions":
			{
				form2_drawPages::drawSubmissions();
				break;
			}	
			
			case "formMeta":
			{
				form2_drawPages::drawFormMeta();
				break;
			}

			
			default:
			{
				echo form2_drawPages::drawWorkspaces();
				break;
			}
		}		
?>