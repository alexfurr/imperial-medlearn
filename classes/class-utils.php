<?php

class medlearnUtils
{
	

	static function gerPercentile($unitGrade, $allGrades)
	{

	
		asort($allGrades);
		// Get the 	percentile
		$percentileArray = array();
		
		foreach ($allGrades as $gradeInfo)
		{
			$unitMark = $gradeInfo['unitMark'];					
			$percentileArray[] = $unitMark;

		}
		
		$totalGrades = count($percentileArray);		

		
		$foundHigher = false; // Set this to true when we get to a value higher than the grade in question
		foreach ($percentileArray as $thisKey =>  $thisGrade)
		{
			if($thisGrade>$unitGrade && $foundHigher==false)
			{
				$lowerThanValues = ($thisKey);	
				$thisPercentile = round(($lowerThanValues/$totalGrades)*100);	
				
				return $thisPercentile;
			}
		}
		
		return "100"; // If they can't find higher they are in the top!
		
	}
}



?>