<h2>My Grades</h2>
<?php

// Get unit Code lookup


$unitCodeLookupArray = imperialQueries::getAllAssessmentCodes();

$html='';

// Create academic Year array
$masterGradesArray = array();


$myGrades  = imperialQueries::getMyAssessments($cid);
foreach($myGrades as $gradeInfo)
{
	$yearOfProgramme = $gradeInfo['yearOfProgramme'];
	$academicYear = $gradeInfo['academicYear'];
	$unitMark = $gradeInfo['unitMark'];
	$unitGrade = $gradeInfo['unitGrade'];
	$gradeDate = $gradeInfo['gradeDate'];
	$unitCode = $gradeInfo['unitCode'];
	$unitName = $unitCodeLookupArray[$unitCode];

	
	$masterGradesArray[$academicYear][] = array(
		"unitMark"=>$unitMark,
		"unitGrade"=>$unitGrade,
		"gradeDate"=>$gradeDate,
		"unitCode"=>$unitCode,
		"unitName"=>$unitName,
		"yearOfProgramme"=>$yearOfProgramme,
	
	);
}



if($_SESSION['userType']==1)
{
	//echo '<div class="imperial-feedback imperial-feedback-alert smallText">';
	//echo 'Percentile data and distribution charts are for review by tutors alone to help discuss academic strategies and areas for improvement with their Tutees.<br/><strong>Please do not show your Tutees</strong>';
	//echo '</div>';
	
	
	
}



// Count the grades
$gradeCount = count($masterGradesArray);

if($gradeCount>=1)
{

	foreach ($masterGradesArray as $academicYear => $acYearAssignments)
	{
		
		
		$niceAcademicYear = imperialNetworkUtils::getNiceAcademicYear($academicYear);
		

		$html.= '<h3>'.$niceAcademicYear.'</h3>';
		$html.='<table class="imperial-table no-hover imperial-align-middle">';

		
		foreach ($acYearAssignments as $theseGrades)
		{
		
			$unitName = $theseGrades['unitName'];
			$unitCode = $theseGrades['unitCode'];
			$unitGrade = $theseGrades['unitGrade'];
			$gradeDate = $theseGrades['gradeDate'];
			$yearOfProgramme = $theseGrades['yearOfProgramme'];
			$unitMark = $theseGrades['unitMark'];
			
			$gradeDate =  Datetime::createFromFormat('Y-m-d',  $gradeDate);
			$gradeDate = $gradeDate->format('jS F, Y');


			$clickIDStr = preg_replace("/[^A-Za-z0-9 ]/", '', $unitCode);


			$chartDiv = "chart_".$academicYear."_".$clickIDStr;
			if($unitMark==0){$unitMark='-';}
			
			$showGraph=false;
			
			$percentileStr = '';

			if(is_numeric($unitMark))
			{
				$showGraph=true;
				$unitMarkStr = '<span class="unitMark">'.$unitMark.'<span class="smallText">%</span></span>';

				$allGrades = imperialQueries::getUnitGrades($unitCode, $academicYear);
				// Calulate the distribution as well
				$args = array(
					"studentMark"=> $unitMark,
					"unitCode"	=> $unitCode,
					"academicYear"	=> $academicYear,
					"chartDiv"	=> $chartDiv,
					"allGrades"	=> $allGrades,
				);				
				

				
				$percentileStr='';
				
		
				
				//$html.= medlearnDraw::drawGradeDistributionGraph($args);
			
				$thisPercentile = medlearnUtils::gerPercentile($unitMark, $allGrades);
				$percentileText = imperialNetworkUtils::getOrdinal($thisPercentile);
				
				$args = array(
					"number" => $thisPercentile,
					"text" => $percentileText,
					"size"	=> "50",
					"colour"	=> "#a30618",

				);
				$percentileStr.= imperialNetworkDraw::drawRadialProgress($args);
				
			}
			else
			{
				$unitMarkStr = $unitMark;
			}
			
			$gradeStr = imperialNetworkUtils::getGradeString($unitGrade);
			
			$html.='</tr>';
			if($_SESSION['userType']==1)
			{
				//	$html.='<td>'.$percentileStr.'</td>';
			}				
			
			
			$html.='<td width="20px">'.$unitMarkStr.'</td>';	
	
			$html.='<td>';
			
			$html.='<strong>'.$unitName.'</strong>';
			
			if($_SESSION['userType']==1 && $showGraph==true)
			{
				
				$html.='<br/><span class="smallText greyText">'.$unitCode.'</span>';
				
				if($showGraph==true)
				{
				
					$myClickID = 'showGraph_'.$academicYear.'_'.$clickIDStr;
					//$html.=' : <a id="'.$myClickID.'" href="javascript:toggleDistGraph(\''.$chartDiv.'\')" class="smallText"><i class="far fa-chart-bar"></i> View Distribution</a>';
					//$html.='<div id="'.$chartDiv.'"/ style="display:none;"></div>';
				}

			}
			$html.='</td>';
			$html.='<td>'.$gradeStr.'</td>';
			$html.='<td>Year '.$yearOfProgramme.'</td>';
			if($_SESSION['userType']==1)
			{			
			
				$html.='<td>'.$gradeDate.'</td>';
			}
			

			$html.='</tr>';
		}	
		$html.='</table>';

		
		
	}
}
else	
{
		$html.='No grades found';
}

/*$html.='<script>				
function toggleDistGraph(chartID)
{
	jQuery( "#"+chartID ).toggle( "fast");
}	

</script>';
	
	*/
	


echo $html;





		
?>