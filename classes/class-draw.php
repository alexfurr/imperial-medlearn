<?php

class medlearnDraw
{
	

	static function drawDeptAdminList($deptID)
	{
		
		$html = '';


		$adminUsers = imperialQueries::getDeptAdmins($deptID);
		
		$userCount = count($adminUsers);
		
		if($userCount==0)
		{
			$html.='No administrators found';
			return $html;
		}
		
		$html.= '<table class="imperial-table imperialTableFullWidth">';
		$html.= '<tr><th>Last Name</th><th>First Name</th><th></th><th>Email</th><th></th></tr>';
		foreach ($adminUsers as $username => $userInfo)
		{
			$firstName = $userInfo['firstName'];
			$lastName = $userInfo['lastName'];
			$email = $userInfo['email'];		
			$html.= '<tr><td>'.$lastName.'</td><td>'.$firstName.'</td>';
			$html.= '<td>'.$username.'</td>';
			$html.= '<td>'.$email.'</td>';
			
			
			$jsonData = array(
				"action"	=> "removeDeptAdmin",
				"updateDiv" => "deptAdminList",
				"args" 		=> array 
				(
					"username" => $username,
					"deptID"	=> $deptID
				),
			);

			$jsonItem = json_encode($jsonData);
			
			$html.= '<td><button class="delete imperial-button" onclick = \'javascript:imperialClickEvent('.$jsonItem.');\'>Remove</button></td>';
			
			$html.= '</tr>';
		}
		$html.= '</table>';
		
		return $html;
		
	}
	
	
	
	static function imperialFeedback($text, $class="success")
	{
		$html = '<div class="imperial-feedback imperial-feedback-'.$class.'">';
		$html.=$text;
		$html.='</div>';
		
		return $html;
	}
	
	
	static function drawGradeDistributionGraph($args)
	{
		
		$html='';
		$unitCode = $args['unitCode'];
		$highlightGrade = $args['studentMark'];
		$academicYear = $args['academicYear'];
		$chartDiv = $args['chartDiv'];
		$allGrades = $args['allGrades'];

		
		$allGradesArray = array();
		foreach ($allGrades as $gradeInfo)
		{
			$studentID = $gradeInfo['studentID'];
			$unitMark = $gradeInfo['unitMark'];
			
			$allGradesArray[$unitMark][] = $studentID;
			
		}
		ksort($allGradesArray);
		
		
		
		// Go through the array and get a COUNT of each gradeInfo	
		$countGradesArray = array();
		foreach ($allGradesArray as $thisGrade => $studentIDs)
		{
			$countGradesArray[$thisGrade] = count($studentIDs);
		}
		
		
		$html.=  "
			<script type=\"text/javascript\">
			google.charts.load('current', {packages: ['corechart']});
			google.charts.setOnLoadCallback(drawChart);


			function drawChart() {
			// Define the chart to be drawn.

			";

			$html.= "var data = google.visualization.arrayToDataTable([
			['Grade', 'Count', { role: 'style' }],";
			foreach ($countGradesArray as $thisGrade => $thisCount)
			{

			$myColor = "#9bacc9";
			if($highlightGrade==$thisGrade){$myColor="#a30618";}
			$html.= "['".$thisGrade."%', ".$thisCount.", '".$myColor."'],	";  
			}

			$html.= "]);";

			$html.= "
			var options = {
			width: 600,
			height: 150,
			legend: { position: 'none' },

			hAxis: { textPosition: 'none' },


			};";


			// Instantiate and draw the chart.
			$html.= " var chart = new google.visualization.ColumnChart(document.getElementById('".$chartDiv."'));
			chart.draw(data, options);
			}


			</script>	";

			return $html;




		
		
	}

}



?>