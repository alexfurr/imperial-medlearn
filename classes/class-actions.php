<?php


$medlearnActions = new medlearnActions();

// Checks globally for GET Actions and applies apporpriate actions
class medlearnActions
{

	//~~~~~
	function __construct ()
	{		
	

		$this->addWPActions();		
	}
	
	function addWPActions ()
	{
	
		//add_action( 'wp_loaded', array($this, 'checkForMedlearnActions' ) );
	}
	
	
	
	static function checkForMedlearnActions()
	{

		if(isset($_GET['action']) )
		{
			
			$myAction = $_GET['action'];
			
			$loggedInUsername = $_SESSION['username'];
			
			// Set the username var as well
			if(isset($_GET['username']))
			{
				$requestUsername=$_GET['username'];	
				
				if($loggedInUsername==$requestUsername)
				{
					$username = $loggedInUsername;

				}
				else
				{
					$username = $requestUsername;			
				}	
			}
			else
			{
				$username = $loggedInUsername;
				$thisUserTutorUsername = $myTutorUsername;
				$isMyProfile=true;	
			}			
			
			
			
			switch ($myAction)
			{
				case "updateProfile":
				
					$retrieved_nonce="";
					if(isset($_REQUEST['_wpnonce'])){$retrieved_nonce = $_REQUEST['_wpnonce'];}
					if (wp_verify_nonce($retrieved_nonce, 'profile_edit' ) )
					{
						
						global $wpdb;
						global $imperialNetworkDB;				
						$userTable = $imperialNetworkDB::imperialTableNames()['dbTable_users'];	
						$username = $_POST['username'];	
						
						if(isset($_POST['username']) )
						{
							// Do the update				
							$wpdb->query( $wpdb->prepare(
								"UPDATE   ".$userTable." SET 
								preferred_email = %s,
								title = %s,
								job_title=%s,
								user_bio=%s
								WHERE username = %s",
								sanitize_email( $_POST['preferredEmail'] ),
								$_POST['title'],
								wp_kses_post ($_POST['jobTitle']),
								wp_kses_post ($_POST['aboutMe']),
								$_POST['username']
							));  
						
							
							$feedback = 'Profile Updated';
							$feedback_class = 'imperial-feedback-success';
						}
					}			
				
				break;
				
				
				case "cancelSlot":
					$slotID = $_POST['slotID'];
					
					// Get the logged in username			
					$slotInfo = imperialTutorQueries::getSlotInfo($slotID);
					$tutorUsername = $slotInfo['tutorUsername'];
					$tuteeUsername = $slotInfo['tuteeUsername'];
								
					$canDeleteSlots = false;
					if($loggedInUsername==$tutorUsername){$canDeleteSlots=true;}
					if($loggedInUsername==$tuteeUsername){$canDeleteSlots=true;}			
					if($_SESSION['isDeptAdmin']==true){$canDeleteSlots=true;}
				
					if($canDeleteSlots==true)
					{
						
						imperialTutorActions::cancelSlot($slotID);
						

						$feedback = 'Slot Cancelled';
						$feedback_class = 'imperial-feedback-success';
						
					}
					else
					{
						$feedback = 'You cannot cancel this slot';
						$feedback_class = 'imperial-feedback-error';
					}		
				
				
				break;
				
				case "bulkDeleteSlots":
				

					// Get the logged in username	
					$username = $_GET['username'];
					$canDeleteSlots = false;
					if($loggedInUsername==$username){$canDeleteSlots=true;}
					if($_SESSION['isDeptAdmin']==true){$canDeleteSlots=true;}
				
					if($canDeleteSlots==true)
					{
					
						if(!empty($_POST['check_list'])) {
							foreach($_POST['check_list'] as $slotID)
							{
								imperialTutorActions::cancelSlot($slotID);

							}
							
							$feedback = 'Slots cancelled';
							$feedback_class = 'imperial-feedback-success';

							
						}
						else
						{
							$feedback = 'No slots were selected';
							$feedback_class = 'imperial-feedback-error';
			
							
						}
					}
					else
					{
						$feedback = 'You do not have permission to do that';
						$feedback_class = 'imperial-feedback-error';

					}

					
					
								

				
				
				break;		
				
				
				
				case "createSlots":
				
					$retrieved_nonce="";
					if(isset($_REQUEST['_wpnonce'])){$retrieved_nonce = $_REQUEST['_wpnonce'];}
					if (wp_verify_nonce($retrieved_nonce, 'addSlotsNonce' ) )
					{						
						imperialTutorActions::createSlots($username);	
						$feedback = 'Slots Created';
						$feedback_class = 'imperial-feedback-success';
					}
				
				break;
				
				
				case "addPastMeeting":
				
					$addMeeting=true;
					if($_POST['whichTutee']=="")
					{
						$feedback = 'No tutee selected';
						$feedback_class = 'imperial-feedback-error';
						$addMeeting=false;				
					}
					
					if($_POST['prevMeetingDate']=="")
					{
						$feedback = 'No date selected';
						$feedback_class = 'imperial-feedback-error';
						$addMeeting=false;				
					}			
					
					
					if($addMeeting==true)
					{
						imperialTutorActions::createPreviousSlot();
						$feedback = 'Slots Created';
						$feedback_class = 'imperial-feedback-success';
					}
				break;
				
				
			}
			
		}	











		
	}
	

}



?>