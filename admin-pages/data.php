<h2>Data Upload</h2>


<?php

if ( isset( $_GET['action'] ) )
{


	
	$myAction = $_GET['action'];
	switch ($myAction)
	{

		case "userCSVupload":		
			echo medlearnData::uploadUserCSV();		
		break;	
		
		case "rotationCSVupload":
			echo medlearnData::uploadRotationsCSV();
		break;
		
		case "resultsCSVupload":
		echo medlearnData::uploadGradesCSV();
		break;
			
	}
	
	
	
}
?>
<div class="contentBox">
<h2>Students</h2>
<form name="csvUploadForm" action="?view=data&action=userCSVupload"  method="post" enctype="multipart/form-data">
Upload your user list as a CSV file with the following columns:<br/>

<span class="smallText">
<?php
$userColumns = medlearnData::getExpectedUserColumns();

foreach ($userColumns as $thisCol)
{
	
	echo $thisCol.' | ';
}
?>

</span><hr/>
<input type="file" name="csvFile" size="20"/><br/>
<input type="submit" value="Upload" name="submit" class="button-primary" />
<?php
// Add nonce
wp_nonce_field('CSV_UploadNonce');    
?>

</form>
</div>



<?php
if(current_user_can('manage_network'))
{
?>


	<div class="contentBox">
	<h2>Placement Allocations / Rotations</h2>


	<h3>How to update the placements</h3>

	<ol>
	<li>Log into InPlace</li>
	<li>Download the report "Allocation Report - 2018/19 MEDLEARN"</li>
	<li>Upload it below</li>
	</ol>


	<hr/>
	<form name="csvUploadForm" action="?view=data&action=rotationCSVupload"  method="post" enctype="multipart/form-data">


	<?php
	$academicYearArray = imperialNetworkUtils::getAcademicYearsArray();
	$siteAcademicYear = get_site_option('current_academic_year');

	echo '<label for="academicYear">Academic Year</label><br/>';
	echo '<select name="academicYear" id="academicYear">';
	foreach($academicYearArray as $academicYear => $niceAcademicYear)
	{
		echo '<option value="'.$academicYear.'"';
		
		if($siteAcademicYear==$academicYear)
		{
			echo ' selected ';
		}
		
		echo '>'.$niceAcademicYear.'</option>';
	}
	echo '</select><hr/>';

	?>


	Upload your rotation CSV file with the following columns:<br/>

	<span class="smallText">
	<?php
	$userColumns = medlearnData::getExpectedRotationColumns();

	foreach ($userColumns as $thisCol)
	{
		
		echo $thisCol.' | ';
	}
	?>

	</span><hr/>
	<input type="file" name="csvFile" size="20"/><br/>
	<input type="submit" value="Upload" name="submit" class="button-primary" />
	<?php
	// Add nonce
	wp_nonce_field('CSV_rotation_UploadNonce');    
	?>

	</form>
	</div>


	<div class="contentBox">
	<h2>Exam Results</h2>


	<h3>How to update the exam results</h3>
	This should only need to be done once a year

	<ol>
	<li>Log into ICA https://wlsprd.imperial.ac.uk/analytics</li>
	<li>Dashboards > My Dashboard </li>
	<li>Download Exam results</li>
	</ol>


	<hr/>
	<form name="csvUploadForm" action="?view=data&action=resultsCSVupload"  method="post" enctype="multipart/form-data">



	Upload your rotation CSV file with the following columns:<br/>

	<span class="smallText">
	<?php
	$gradeColumns = medlearnData::getExpectedGradeColumns();

	foreach ($gradeColumns as $thisCol)
	{
		
		echo $thisCol.' | ';
	}
	?>

	</span><hr/>
	<input type="file" name="csvFile" size="20"/><br/>
	<input type="submit" value="Upload" name="submit" class="button-primary" />
	<?php
	// Add nonce
	wp_nonce_field('CSV_grades_UploadNonce');    
	?>

	</form>
	</div>

<?php
}
?>