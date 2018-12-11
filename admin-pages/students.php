<div class="contentBox">


<h2>User Search</h2>
					
<?php


$myAction = "addDeptAdmin";
$args = array
(
	"href"		=> true,
	"link"		=> get_site_url()."/profile/",
	"qrystr"	=> array ("username"),
);


echo imperialNetworkDraw::userSearchForm($args, "userProfileResultsDiv");

?>
</div>


<?php


// MBBS or BMB
$currentProgrammeCode = "MBBS";
if(isset($_GET['programme']) )
{
	$currentProgrammeCode = $_GET['programme'];	
}

$currentYos = 1;
if(isset($_GET['yos']) )
{
	$currentYos = $_GET['yos'];	
}

$programmeArray = array("MBBS", "BMB");

foreach ($programmeArray as $thisProgrammeCode)
{
	echo '<a href="?view=students&yos='.$currentYos.'&programme='.$thisProgrammeCode.'" class="imperial-button ';
	if($currentProgrammeCode==$thisProgrammeCode)
	{	
		echo 'selected';
	}
	echo '">';

	echo $thisProgrammeCode;

	echo '</a>';
	
	
}
echo '<br/>';

$yos = 1;

while ($yos<=6)
{
	
	if($currentYos==$yos)
	{
		echo '<strong>';
	}
	else
	{
		echo '<a href="?view=students&yos='.$yos.'&programme='.$currentProgrammeCode.'">';
	}
	
	echo 'Year '.$yos;
	
	if($currentYos==$yos)
	{
		echo '</strong>';
	}	
	else
	{
		echo '</a>';
	}
	
	echo ' | ';
	
	$yos++;	
}


// get the list of students
$myUsers = imperialQueries::getStudentsByYOS($currentYos, $currentProgrammeCode);


$userCount= count($myUsers);

echo '<hr/><strong>'.$userCount.'</strong> students found.<br/>';

if($userCount>=1)
{
	
	$homeURL = get_site_url();	

	
	// Get the programme lookup codes
	$courseCodeLookupArray = imperialQueries::getCourseCodeArray();
	
	
	echo '<table id="userTable">';
	echo '<thead>';
	echo '<tr><th></th><th>Name</th><th>Username</th><th>Student ID</th><th>Email</th><th>Programme</th></tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach ($myUsers as $userInfo)
	{
		
		$firstName = $userInfo['first_name'];
		$lastName = $userInfo['last_name'];
		$email = $userInfo['email'];
		$username = $userInfo['username'];
		$cid = $userInfo['userID'];
		$programme = $userInfo['programme'];
		$programmeName = $courseCodeLookupArray[$programme]['name'];
		
		
		$avatarArgs = array(			
			"CID"		=> $cid,
		);		
		$avatarURL = get_user_avatar_url( $avatarArgs);		
		
		echo '<tr valign="middle">';		
		echo '<td width="75px">';
		
		echo '<a href="'.$homeURL.'/profile/?username='.$username.'"><img src="'.$avatarURL.'"></a>';
		
		echo '</td>';
		
		echo '<td><a href="'.$homeURL.'/profile/?username='.$username.'">'.$lastName.', '.$firstName.'</a></td>';
		echo '<td>'.$username.'</td>';
		echo '<td>'.$cid.'</td>';
		echo '<td><a href="mailto:'.$email.'">'.$email.'</a></td>';
		
		echo '<td>'.$programmeName.' ('.$programme.')</td>';
		echo '</tr>';
		
	}
	echo '</tbody>';
	echo '</table>';
	?>
	<script>
	jQuery(document).ready( function ()
	{
		jQuery('#userTable').DataTable(
		{
			"order": [[ 1, "asc" ]],
			"pageLength": 25,
		}
		);
	} );	
	

	</script>	
	

	
	
	<?php

}
?>
