<?php
		$page='';
		if(isset($_GET['page']) )
		{
			$page = $_GET['page'];
		}
		
		switch ($page)
		{
				
			case "ethics":
			{				
				
				echo '<span class="fa-stack fa-2x">
					<i class="fas fa-circle fa-stack-2x"></i>
					<i class="fas fa-balance-scale fa-stack-1x fa-inverse"></i>
					</span>';
				
				echo ' Ethics and Law<hr/>';				
				
								
				$currentAcademicYear = get_site_option("form2AcademicYear");

				if(isset($_GET['academicYear']) )
				{
					$currentAcademicYear = $_GET['academicYear'];

				}
				$currentNiceAcademicYearText = imperialNetworkUtils::getNiceAcademicYear($currentAcademicYear);				

				
				// Set the filter vars
				$rotationFilter = "";
				$specialtyFilter = "";

				// Check for POST and set current vars
				$currentSpecialty = '';
				$currentRotation = '';
				$formPosted= false;


				if(isset($_POST['specialty']) )
				{
					$formPosted = true;
					$currentSpecialty = $_POST['specialty'];
					$currentRotation = $_POST['rotation'];
					if(isset($_POST['sites']) )
					{
						$currentSite = $_POST['sites'];
					}
				}


				echo '<a href="?view=reports" class="backButton">Back to reports</a>';
				
				echo '<form class="imperial-form" method="post"  action="?view=reports&page=ethics&runReport=true">';

				// Get all placement types	
				$specialityLookup	= imperialPlacementsQueries::getSpecialtyArray();

				$mySpecialties = imperialPlacementsQueries::getSpecialtiesInYear($currentAcademicYear);
				
				echo '<hr/><label for="specialty">Specialty</label> ';
				echo '<select name="specialty" id="specialty" onchange="showRotationsDropdown();">';
				echo '<option value="">Please select...</option>';
				
				foreach ($mySpecialties as $specialtyCode => $sName)
				{
										
					
					echo '<option value="'.$specialtyCode.'"';
					if($currentSpecialty==$specialtyCode)
					{
						echo ' selected ';
					}
					
					echo '>'.$sName.'</option>';
					
				}
				echo '</select>';
				
				
				echo '<div id="rotations_div">';
				if($formPosted==true)
				{
					echo form2_draw::drawRotationDropdown($currentSpecialty, $currentAcademicYear, $currentRotation);
				}
				echo '</div>';
				
				
				echo '<div id="sites_div">';
				if($formPosted==true && $currentRotation)
				{
					echo form2_draw::drawSitesDropdown($currentSpecialty, $currentRotation, $currentAcademicYear, $currentSite);
				}	
				
				echo '</div>';
					
				echo '<hr/><input type="submit" value="Run Report" >';				
				echo '<input type="hidden" id="academicYear" value="'.$currentAcademicYear.'">';
				echo '</form>';
				
				$runReport = false;
				if(isset($_GET['runReport'] ) )
				{
					
					// Get the modules required for each discipline
					
					// Array of the topic IDs for each of the relevent fields
					// Array of post IDs for the topics
					// See https://medlearn.imperial.ac.uk/ethics-and-law-emodules/wp-admin
					$blogID = 35; // REAL ONE
					$eModulesArray = array(
						
						"OG"	=> array (1021, 886, 635),
						"PA"	=> array (121, 43),
						"GPPHCC"=> array (749, 1629, 1250),
						"PY"	=> array (1470, 1361),
					);
					
					
					
					/*
					// TEMP FOR DEV
					// Hard code the blog ID of the ethics eModules
					$blogID = 6;
					$eModulesArray = array(
						
						"OG"	=> array (889, 890, 640)
					);	
					*/					
					
					
					
					if(isset($eModulesArray[$currentSpecialty]) )
					{
						
						$myModules = $eModulesArray[$currentSpecialty];
						$runReport = true;
						
					}
					else
					{
						echo 'No Ethics eModules found for this specialty';
					}

					
					
					// Create blank array of the users for this report
					$userReportArray = array();

					
					$args = array
					(
						"academicYear" => $currentAcademicYear,
						"rotation"	=> $currentRotation,
						"specialty"	=> $currentSpecialty,
						"site"		=> $currentSite,
					
						
					);
					$userListArray = imperialPlacementsQueries::getStudentsByRotation($args);					
				
					$userCount = count($userListArray);

					
					if($userCount>=1 && $runReport==true)
					{
	
						$tableStr= '<table id="reportTable">';
						
						$tableStr.= '<thead><tr><th>Name</th><th>Username</th><th>CID</th>';
						
						
						switch_to_blog( $blogID );		


						//	Get Array of ALL stats
						$ek_user_stats_db= new ek_user_stats_db();
						$userRecords = ek_user_stats_queries::getActivityFromBlogID($blogID);
						
						$masterUserStatsLookup = array();
						
						// Got through the user records and add each page ID as a new KEY for a user array
						foreach ($userRecords as $entryInfo)
						{
							$userID = $entryInfo['user_id'];
							$pageID = $entryInfo['page_id'];
							
							$masterUserStatsLookup [$userID][$pageID] = true;
						}

						
						// Create the master topic Array for pages
						$masterTopicPageArray = array();
						foreach ($myModules as $topicID)
						{						

							// Get the topic Name							
							$topicTitle = get_the_title($topicID);
							$tableStr.= '<th>'.$topicTitle.'</th>';

						
							$args = array(
								'posts_per_page'   => -1,
								'orderby'          => 'menu_order',
								'order'            => 'ASC',
								'include'          => '',
								'exclude'          => '',
								'post_type'        => 'topic_session',
								'post_parent'      => $topicID,
								'post_status'      => 'publish',
							);
							$sessions = get_posts( $args );

							foreach ($sessions as $sessionInfo)
							{
								
								// get an array of all pages in this topic
								$sessionID = $sessionInfo->ID;

								
								// Go thorugh the sessions and add the page IDs to the array
								$args = array(
									'posts_per_page'   => -1,
									'orderby'          => 'menu_order',
									'order'            => 'ASC',
									'include'          => '',
									'exclude'          => '',
									'post_type'        => 'session_page',
									'post_parent'      => $sessionID,
									'post_status'      => 'publish',
								);
								$sessionPages = get_posts( $args );	

								foreach ($sessionPages	as $pageInfo)
								{
									$pageID = $pageInfo->ID;									
									
									$masterTopicPageArray[$topicID][] = $pageID;
								}								
								
								
							}
						}
												
						restore_current_blog();
						
						
						$tableStr.= '</tr></thead>';

						
						foreach ($userListArray as $userInfo)
						{
							
							$thisStudentID = $userInfo['userID'];

							$userInfo = imperialQueries::getUserInfo($thisStudentID, "userID");
							
							
							$username = strtolower($userInfo['username']);
							$firstName = $userInfo['first_name'];
							$lastName = $userInfo['last_name'];

							
							$tableStr.= '<tr>';
							
							$tableStr.= '<td>'.$lastName.', '.$firstName.'</td>';
							$tableStr.= '<td>'.$username.'</td>';
							$tableStr.= '<td>'.$thisStudentID.'</td>';		
															
							// Get the User ID
							$wpUserMeta = get_user_by( 'login', $username );
							
							if($wpUserMeta==false)
							{								
								foreach ($myModules as $topicID)
								{
									$tableStr.='<td><span class="failText">0%</span></td>';
								}
							}
							else
							{
								
								foreach ($myModules as $topicID)
								{
								
									$wpUserID = $wpUserMeta->ID;
									
									// Go through the pages for this topic - see if they have viewed it
									$thisPageArray = $masterTopicPageArray[$topicID];
									
									$thisPageCount = count($thisPageArray);
									$tempCount = 0;
									
									foreach($thisPageArray as $pageID)
									{
										
										if(isset($masterUserStatsLookup[$wpUserID][$pageID]) )
										{
											$tempCount++;
										}	

									}
									
									
									if($thisPageCount==0)
									{
										$tableStr.='<td><span class="failText">0%</span></td>';
									}
									else
									{
										
										// Calulcate percent complete									
										$thisPercentComplete = round(($tempCount/$thisPageCount)*100, 0);	
									
										
										
										if($thisPercentComplete=="100")
										{
			
											$thisPercentCompleteText='<div class="fa-stack fa-1x">
											<i class="fa fa-circle fa-stack-1x"  style="color:green;"></i>
											<i class="fa fa-check fa-stack-1x fa-inverse fa-xs"></i>
											</div><span class="successText">Complete</span>';
										}
										elseif($thisPercentComplete==0)
										{
											$thisPercentCompleteText='<span class="failText">0%</span>';
										}
										else
										{
											$thisPercentCompleteText='<span class="alertText">'.$thisPercentComplete.'%</span>';
										}
										$tableStr.= '<td>'.$thisPercentCompleteText.'</td>';
									}
																	
								}								
							}
							$tableStr.= '</tr>';
							
						}
						
						$tableStr.= '</table>';
						
						
						
						echo $tableStr;
						// Enable Data table
						?>	
						<script>
						jQuery(document).ready( function () {
							jQuery('#reportTable').DataTable(
							{
								
								"pageLength": 100,
								
							}
							
							
							
							);
						} );

						</script>
						<?php
					}
				}
					
				
				
				
				
				
				
				
				
				
				
				
				
				
				
			break;
			}				
			default:
			{

				echo '<a href="?view=reports&page=ethics">';
				
				
				echo '<span class="fa-stack fa-2x">
					<i class="fas fa-circle fa-stack-2x"></i>
					<i class="fas fa-balance-scale fa-stack-1x fa-inverse"></i>
					</span>';
				
				echo ' Ethics and Law</a>';

				
				
			break;
			}
		}		
?>