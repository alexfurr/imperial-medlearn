<h2>Tutors</h2>

<?php
// Get and array of tutors 
$currentAcademicYear = get_site_option("form2AcademicYear");
$homeURL = get_home_url();


$allTutors = imperialTutorQueries::getAllTutors($currentAcademicYear);

$tutorCount = count($allTutors);

// Get ALL allocations for lookup tuttee counts
$allAllocations = imperialTutorQueries::getAllTutorAllocations($currentAcademicYear);

$masterLookupArray = array();
foreach ($allAllocations as $allocationMeta)
{
	$thisTutor = $allocationMeta['tutorUsername'];
	$studentUsername = $allocationMeta['username'];
	
	$masterLookupArray[$thisTutor][] = $studentUsername;
}

$now = date('Y-m-d H:i:s');

echo '<strong>'.$tutorCount.'</strong> tutors found<hr/>';

echo '<table class="imperial-table" id="userTable">';
echo '<thead>';
echo '<tr><th>Title</th><th>Name</th><th>Email</th><th>Tutee Count</th><th>Upcoming slots</th><th>Seen</th></tr>';
echo '</thead>';
foreach ($allTutors as $tutorInfo)
{
	$tutorUsername = $tutorInfo['tutorUsername'];
	$title = $tutorInfo['title'];
	$firstName = $tutorInfo['first_name'];
	$lastName = $tutorInfo['last_name'];
	$email = $tutorInfo['email'];
	
	$tuteeArray = 	$masterLookupArray[$tutorUsername];
	$tuteeCount = count($tuteeArray);

	
	// Get the number of upcoming active slots
	$allSlots = imperialTutorQueries:: getAllSlots($tutorUsername);	
	
	$activeSlots = 0;
	
	$tuteeCheckArray = array();
	foreach ($allSlots as $slotInfo)
	{
		$slotDate = $slotInfo->slotDate;
		$tuteeUsername = $slotInfo->tuteeUsername;
		
		
		if($tuteeUsername<>"")
		{
			$tuteeCheckArray[$tuteeUsername] = true;
		}
		
		if($slotDate >=$now)
		{
			$activeSlots++;
		}
	}
	
	
	
	$tuteeBookCheckCount = 0;
	// Now get the percentage of tutees who have booked slots
	foreach($tuteeArray as $tuteeUsername)
	{
		if (array_key_exists($tuteeUsername,$tuteeCheckArray))
		{
			$tuteeBookCheckCount++;
		}		
		
		
	}
	
	

	
	echo '<tr>';
	
	echo '<td>'.$title.'</td>';
	echo '<td><a href="'.$homeURL.'/profile/?view=tutees&username='.$tutorUsername.'">'.$lastName.', '.$firstName.'</a></td>';
	echo '<td><a href="mailto:'.$email.'">'.$email.'</a></td>';
	
	echo '<td><strong>'.$tuteeCount.'</strong> tutees</td>';
	echo '<td><strong>'.$activeSlots.'</strong> slots available</td>';
	echo '<td><strong>'.$tuteeBookCheckCount.' / '.$tuteeCount.'</strong> tutees</td>';
	
	
	
	echo '</tr>';

	
	
}

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