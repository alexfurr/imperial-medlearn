<?php 
get_header();

$loggedInUsername = $_SESSION['username'];
$loggedInUserType = $_SESSION['userType'];

$showAdminPage=false;
$isMyProfile = false;
$isMyTutee=false;
$isMyTutor=false;
$isNetworkAdmin=false;
$isDeptAdmin=false;

if(isset($_SESSION['isDeptAdmin']) )
{
	if($_SESSION['isDeptAdmin']==true)
	{
		$isDeptAdmin = true;
	}
}

if(isset($_SESSION['isNetworkAdmin']) )
{
	if($_SESSION['isNetworkAdmin']==true)
	{
		$isNetworkAdmin = true;
		
	}
}

// The current Profile View Tutor
$thisUserTutorUsername = '';

// Current logged in user tutor
$myTutorUsername = '';
if(isset($_SESSION['tutorUsername']) )
{
	
	$myTutorUsername=$_SESSION['tutorUsername'];
}



// Avatar Stuff
$pageURL = get_permalink();
$feedback = '';

$view = '';
if(isset($_GET['view']) )
{
	$view=$_GET['view'];
}

if(isset($_GET['username']))
{
	$requestUsername=$_GET['username'];	
	
	if($loggedInUsername==$requestUsername)
	{
		$username = $loggedInUsername;
		$thisUserTutorUsername = $myTutorUsername;

		$isMyProfile=true;
	}
	else
	{
		$showPage = false;
		
		$tutorInfo=imperialTutorQueries::getMyTutor($requestUsername);
		$thisUserTutorUsername = $tutorInfo['username'];
					
		if($isDeptAdmin==true || current_user_can('manage_network') )
		{
			$showAdminPage=true;
		}	
		
		$username = $requestUsername;			

	}	
}
else
{
	$username = $loggedInUsername;
	$isMyProfile=true;	
	$thisUserTutorUsername = $myTutorUsername;
}

if($username==$myTutorUsername)
{
	$isMyTutor=true;
}




// Get the viewed profile wordpress ID
$user_info = get_user_by( 'login', $username );
if($user_info)
{
	$wp_userID = $user_info->ID;
}
else
{
	$wp_userID = 0;
}


if(isset($_GET['action']) )
{
	
	$myAction = $_GET['action'];
	
	
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
					
			imperialTutorActions::createSlots($username);	
			$feedback = 'Slots Created';
			$feedback_class = 'imperial-feedback-success';
		
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



$userInfo = imperialQueries::getUserInfo($username);



$firstName = $userInfo['first_name'];
$lastName = $userInfo['last_name'];
$userEmail = $userInfo['email'];
$yos = $userInfo['yos'];
$programmeCode = $userInfo['programme'];

// Get the programme name from the code
$programmeName = imperialQueries::getCourseNameFromCode($programmeCode);


//$programmeName = $userInfo['programmeName'];
$email = $userInfo['email'];
$cid = $userInfo['userID'];
$userType = $userInfo['user_type'];
$title = $userInfo['title'];
$userBio = imperialNetworkUtils::convertTextFromDB($userInfo['user_bio']);
$preferredEmail = $userInfo['preferred_email'];
$jobTitle = imperialNetworkUtils::convertTextFromDB($userInfo['job_title']);


$fullname = $firstName.' '.$lastName;

if($userType==1)
{
	$fullname = $title.' '.$fullname;
}

if($isMyProfile==true)
{
	$h1Title =  'My Profile';
}
else{
	$h1Title= $fullname;
}





?>
<div id="imperial_page_title">
<h1 class="entry-title"><?php echo $h1Title; ?></h1>
</div>
<?php










// Avatar
if ( isset( $_POST['submit_user_avatar'] ) )
{
    save_user_avatar( $wp_userID, sanitize_text_field( $_POST['newProfileImageFile_0'] ) );
    
    //feedback message
    $feedback = 'Avatar updated';
    $feedback_class = 'imperial-feedback-success';
}
elseif ( isset( $_POST['delete_user_avatar'] ) ) 
{
    delete_user_avatar( $wp_userID );
    
    //feedback message
    $feedback = 'Your avatar has been removed.';
    $feedback_class = 'imperial-feedback-success';
}



$args = array(
	"userID"	=> $wp_userID,
	"CID"		=> $cid,
);
$avatarURL = get_user_avatar_url( $args);
$hash = create_hash();
$avatarURL = $avatarURL.'?hash=$hash';

$subMenu = '';

$subMenu.='<div class="profilePageAvatar">';
$subMenu.= '<div class="rounded-image">';
$subMenu.= '<img src="'.$avatarURL.'" class="profile-avatar">';		
$subMenu.= '</div>';

if($isMyProfile==true)
{		

	$subMenu.= '<div class="editProfileLinks"><a href="' .get_permalink(). '?view=edit-avatar">Edit Avatar</a> ';		
	$subMenu.= '<a href="' .get_permalink(). '?view=edit-profile">Edit Profile</a></div>';		
	
}	
$subMenu.='</div>';

/* Start of Sub Menu */

$menuItems = '';
$subMenu.='<ul>';



$showFullProfileLinks = false;

// if its my profile show everything
if($isMyProfile==true)
{
	$showFullProfileLinks= true;
}

if($loggedInUserType==1 && $userType>1)
{
	//$showFullProfileLinks= true;	// Allow *any* staff to view a students full profile. Do we want this?
}

// Also show full prpfile links if they are dept admin
if($isDeptAdmin==true || $isNetworkAdmin==true)
{
		$showFullProfileLinks= true;

}

// if they are the TUTOR of the student then show everything

if($loggedInUsername==$thisUserTutorUsername)
{
	$showFullProfileLinks= true;
}




if($showFullProfileLinks==true)
{
	$subMenuArrayItems = imperialNetworkUtils::getProfileMenuItems($userType);
	foreach($subMenuArrayItems as $submenuMeta)
	{
		$itemType = $submenuMeta[0];
		$itemName = $submenuMeta[1];
		

		$menuItems.='<a href="?view='.$itemType.'&username='.$username.'">';
		$menuItems.='<li';
		if($view ==  $itemType) {$menuItems.=' class="activeTab"';}	
		$menuItems.='>'.$itemName.' <i class="fas fa-chevron-right submenuChevron"></i></li></a>';	
	}
}
else
{
		$subMenu.='';
		$subMenu.='<li>Profile<i class="fas fa-chevron-right submenuChevron"></i></li>';
}



// See if this person is the tutor otf the student




if($userType>1)
{
	
	if($isMyTutee==true)
	{

		$itemType='tutor-notes';
		$menuItems.='<a href="?view='.$itemType.'&username='.$username.'">';
		$menuItems.='<li';
		if($view ==  $itemType) {$menuItems.=' class="activeTab"';}	
		$menuItems.='>Tutor Notes <i class="fas fa-chevron-right submenuChevron"></i></li></a>';	
		
	}
}

$subMenu.=$menuItems;

$subMenu.='</ul>';




?>
<main id="content">
<div class="entry-content">
<div class="subpage-content-wrap clearfix">
<div class="subpage-submenu">
<?php echo $subMenu; ?>
</div>
<div class="subpage-content">
				



<?php


if ( $feedback ) {
    echo '<div class="imperial-feedback '.$feedback_class.'">' . $feedback . '</div>';
}



switch ($view)
{
	
	
	case "placements":
	{	
		include_once get_stylesheet_directory() . '/profile-pages/placements.php';
		break;
	}
	
	case "forms":
	case "form-submission":
	{
		include_once get_stylesheet_directory() . '/profile-pages/forms.php';
		break;
	}
	
	case "edit-avatar": 
	{
		include_once get_stylesheet_directory() . '/profile-pages/avatar-edit.php';
		break;
	}
	
	case "edit-profile": 
	{
		include_once get_stylesheet_directory() . '/profile-pages/profile-edit.php';
		break;
	}
	
	case "tutor":
	{
		include_once get_stylesheet_directory() . '/profile-pages/my-tutor.php';
		break;
	}
	
	case "tutor-bookings": 
	{
		include_once get_stylesheet_directory() . '/profile-pages/tutor-bookings.php';
		break;
	}	
	
	case "tutees": 
	{
		include_once get_stylesheet_directory() . '/profile-pages/tutees.php';
		break;
	}	

	case "tutee-timeslots": 
	{
		include_once get_stylesheet_directory() . '/profile-pages/tutee-timeslots.php';
		break;
	}
	
	case "courses": 
	{
		include_once get_stylesheet_directory() . '/profile-pages/courses.php';
		break;
	}
	
	case "tutor-notes": 
	{
		include_once get_stylesheet_directory() . '/profile-pages/tutor-notes.php';
		break;
	}	
	
	
	case "grades":
	{
		include_once get_stylesheet_directory() . '/profile-pages/grades.php';
		break;
	
	}
	
	
	default:
		include_once get_stylesheet_directory() . '/profile-pages/index.php';
	break;
}


?>
</div>
</div>
</div>
</main>
<style>
.entry-content {
    padding-left: 0px;
    padding-top: 0px;
}

.subpage-submenu ul li
{

	border-top: 1px solid #f4f4f4;
}


</style>
<?php  get_sidebar(); ?>
<?php get_footer(); ?>