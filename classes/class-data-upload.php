<?php

class medlearnData
{
	
	
	
	static function getExpectedRotationColumns()
	{
		
		$rotationColumns = array
		(
			"Student CID",
			"Agency ID",
			"Parent Agency ID",
			"Placement Name",
			"Code",
			"Start Date",
			"End Date",
			"Placement Delivered by",
			"Staff Type Agency",
			"Group Name",
		);
		
		return $rotationColumns;
		
	}	
	
	
	
	
	static function getExpectedUserColumns()
	{
		
		$userColumns = array
		(
			"Student CID",
			"Status",
			"Title",
			"Given Names",
			"Preferred Given Name",
			"Surname",
			"Sex",
			"Programme",
			"Academic Year",
			"Course Year",
			"Email",
			"Student Username",
			"Tutor Name",
			"Tutor Email",
			"Tutor Username",

		);
		
		return $userColumns;
		
	}
	
	static function getExpectedGradeColumns()
	{
		
		$userColumns = array
		(
			"Person Number",
			"Program Code",
			"Academic Year",
			"year Of Program",
			"Unit Department Code",
			"Unit Department Description",
			"unit Title",
			"Unit Mark",
			"Unit Grade",
			"Unit Version",
			"Unit Code",
			"unit Class",
			"Unit Attempt Last Updated Date",

		);
		
		return $userColumns;
		
	}	
	
	
	static function uploadRotationsCSV()
	{

		// Check the nonce before proceeding;	
		$retrieved_nonce="";
		if(isset($_REQUEST['_wpnonce'])){$retrieved_nonce = $_REQUEST['_wpnonce'];}
		if (wp_verify_nonce($retrieved_nonce, 'CSV_rotation_UploadNonce' ) )
		{

	
			// Upload temp file to temp dir un uploads 
			$uploadPath = medlearnUtils::getTempUploadDir();
			$newFilename = $uploadPath.'/tempRotationsImport.csv';
			$error = '';
						
			if(isset($_FILES['csvFile']['tmp_name']))
			{
				
				global $wpdb;
				global $imperialPlacementsDB;
				
				
				
				$dbName = $imperialPlacementsDB->dbTable_placementAllocations;
				
				$academicYear = $_POST['academicYear'];
				
				$deletedStudentsArray = array(); // Create array of students who have had their previous data deleted and only do it once.
				$totalStudentCount = 0;
				
				// Delete the old data
				$table  = $wpdb->prefix . $dbName;
				//$wpdb->query("DELETE FROM $table WHERE academicYear= '".$academicYear."'");
				
				move_uploaded_file($_FILES['csvFile']['tmp_name'], $newFilename);
				
				// Go through the CSV stuff
				ini_set('auto_detect_line_endings',1);
				$handle = fopen($newFilename, 'r');
				
				$expectedColumns = medlearnData::getExpectedRotationColumns();
				
				$currentLineNo=1;
				while (($data = fgetcsv($handle, 1000, ',')) !== FALSE)
				{

					// Error check based on the column titles
					if($currentLineNo==1)
					{

						foreach ($expectedColumns as $thisCol => $expectedColumn)
						{
					
					
							$actualCol = strtolower(trim($data[$thisCol]));
							$checkExpectedColumn = strtolower($expectedColumn);
							
							if($checkExpectedColumn <> $actualCol )
							{
							
								$error.='<div class="imperial-feedback imperial-feedback-error">';

								$error.= 'Column '.($thisCol+1).' ('.$data[$thisCol].') does not match the expected name ('.$expectedColumn.')';
								$error.='</div>';
								return $error;
							}		
							
						}


					}
					else
					{
						$studentID= $data[0];
						$agencyID= $data[1];
						$parentAgencyID= $data[2];
						$specialtyName= $data[3];
						$specialtyCode= $data[4];
						$startDate= $data[5];
						$endDate= $data[6];
						$department= $data[7];
						$agencyType= $data[8];
						$rotationString= $data[9];

						$startDateStamp = DateTime::createFromFormat('d/m/Y', $startDate );
						$endDateStamp = DateTime::createFromFormat('d/m/Y', $endDate );
						
						$startDateMYSQL = date_format($startDateStamp,"Y-m-d");
						$endDateMYSQL = date_format($endDateStamp,"Y-m-d");
						
								
								
						// Pad the student ID up to make 8 characters
						$studentID = str_pad($studentID, 8, "0", STR_PAD_LEFT);									
								

						
						// Get the rotation number by searching the rotation string. BAH
						// Check for the string "Year 2" - if it exists then its rotation 1
						if (strpos($rotationString, 'Year 2') !== false) // YEAR 1
						{
							$rotation = 1;
						}
						elseif(strpos($rotationString, 'Year 3') !== false) // YEAR 3
						{
							$rotationStringArray = explode(" ", $rotationString);
							$rotationKey = array_search("Rotation",$rotationStringArray);
							$rotationValue = $rotationStringArray[$rotationKey+1];							
							$rotationKey = array_search("Attachment",$rotationStringArray);
							$rotationValue = $rotationStringArray[$rotationKey+1];
							
						}
						else
						{
							$rotationStringArray = explode(" ", $rotationString);
							$rotationKey = array_search("Rotation",$rotationStringArray);
							$rotationValue = $rotationStringArray[$rotationKey+1];
						}					
						
						
						
						
						

						

										/*		
						
						
						echo '<hr/>';
						echo 'Student ID = '.$studentID.'<br/>';
						
						echo 'Date Start = '.$startDateMYSQL.'<br/>';
						echo 'End  = '.$endDateMYSQL.'<br/>';
						echo 'Date  = '.$startDateMYSQL.'<br/>';
						echo 'agencyID  = '.$agencyID.'<br/>';
						echo 'parentAgencyID  = '.$parentAgencyID.'<br/>';
						echo 'specialtyCode  = '.$specialtyCode.'<br/>';
						echo 'department  = '.$department.'<br/>';
						echo 'rotationString  = '.$rotationString.'<br/>';
						echo 'rotationValue = '.$rotationValue;

						*/
						
						
						if(!in_array($studentID, $deletedStudentsArray ) )
						{
							// Delete Previous data for this student								
							$deletedStudentsArray[] = $studentID;				
							
							//echo $SQL.'<br/>';
							$wpdb->delete( $table, array( 'studentID' => $studentID, 'academicYear' => $academicYear ) );
							//echo 'Delete '.$studentID.'<br/>';
							$totalStudentCount++;
							
						}
						
						
						
						
						
						$wpdb->insert( 
							$wpdb->prefix . $dbName, 
							array( 
								'agencyID' 		=> $agencyID,
								'parentAgencyID'=> $parentAgencyID,
								'studentID' 	=> $studentID,
								'startDate' 	=> $startDateMYSQL,
								'endDate' 		=> $endDateMYSQL,
								'department'	=> $department,
								'academicYear'	=> $academicYear,
								'rotation'		=> $rotationValue,
								'specialty'		=> $specialtyCode,
							),
							array( '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
						);	
						
						
					
					}

					$currentLineNo++;	
				}
				
			} // End if file type is CSV
		// Now delete the temp file
		unlink ($newFilename);	
		

			
		$feedback = '';
					
		$feedback.='<div class="imperial-feedback imperial-feedback-success">';
		$feedback.= '<h2>Upload Report</h2>';
		$feedback.= '<strong>Placement Allocations Uploaded OK';
		$feedback.= '</div>';
		
		return $feedback;		
		
			
		} // End of nonce check
	
	}
	

	static function uploadUserCSV()
	{
		

		// Check the nonce before proceeding;	
		$retrieved_nonce="";
		if(isset($_REQUEST['_wpnonce'])){$retrieved_nonce = $_REQUEST['_wpnonce'];}
		
		
		
		if (wp_verify_nonce($retrieved_nonce, 'CSV_UploadNonce' ) )
		{
			
			
			// Upload temp file to temp dir un uploads 
			$uploadPath = medlearnUtils::getTempUploadDir();
			$newFilename = $uploadPath.'/tempUsersImport.csv';
			$error = '';			
			
			
			
			if(isset($_FILES['csvFile']['tmp_name']))
			{
				
				move_uploaded_file($_FILES['csvFile']['tmp_name'], $newFilename);
				
				// Go through the CSV stuff
				ini_set('auto_detect_line_endings',1);
				$handle = fopen($newFilename, 'r');

				global $wpdb;
				global $imperialNetworkDB;					
				
				
				$titlesArray = array
				(
					"Professor",
					"Prof",
					"Dr",
					"Mr",
					"Miss",
					"Ms",
					"Doctor",
					"Mrs",
				);
				
				//$thisTable = $wpdb->prefix . $networkTables['dbTable_facultyList'];
				$userTable = $imperialNetworkDB::imperialTableNames()['dbTable_users'];
				$tutorAllocationstable = $imperialNetworkDB::imperialTableNames()['dbTable_tutorAllocations'];
				$importCount = 0;
				$errorCount = 0;
				$errorLog='';
				
				$currentRow=1;

									
				while (($data = fgetcsv($handle, 1000, ',')) !== FALSE)
				{

					
					$expectedUserColumns = medlearnData::getExpectedUserColumns();
					// Error check the columns
					$error='';
					if($currentRow==1)
					{
						
						
						$expectCheckCol=1;
						foreach ($expectedUserColumns as $thisCol => $expectedColumn)
						{
					
							$actualCol = strtolower(trim($data[$thisCol]));
							$checkExpectedColumn = strtolower($expectedColumn);
							
						//	echo 'checkExpectedColumn = '.$checkExpectedColumn.'<br/>';
							
							
							//echo 'Check Col '.$expectCheckCol.'<br/>';
							if($checkExpectedColumn <> $actualCol && $expectCheckCol<15)
							{
							
								$error.='<div class="imperial-feedback imperial-feedback-error">';

								$error.= 'Column '.($thisCol+1).' ('.$data[$thisCol].') does not match the expected name ('.$expectedColumn.')';
								$error.='</div>';
	
								return $error;
							}
							
							$expectCheckCol++;
							
							
						}

					}
					else
					{
						
						$userID = utf8_encode(trim($data[0])); // A
						$status = utf8_encode(trim(strtolower($data[1]))); // B
						$title = utf8_encode(trim($data[2])); // C
						$firstName = utf8_encode(trim(ucfirst($data[3]))); // D
						$preferredFirstName = utf8_encode(trim(ucfirst($data[4]))); // E
						$surname = utf8_encode(trim(ucfirst($data[5])));	// F	
						$gender = utf8_encode(trim(lcfirst($data[6])));	// G												
						$programmeCode = utf8_encode(trim($data[7]));	// H												
						$academicYear = filter_var($data[8], FILTER_SANITIZE_NUMBER_FLOAT);	// I					
						$yos = filter_var($data[9], FILTER_SANITIZE_NUMBER_FLOAT); //J
						$email = utf8_encode(trim(strtolower($data[10]))); //K
						$username = utf8_encode(trim(strtolower($data[11]))); // L
						$tutorName = utf8_encode(trim($data[12])); //M
						$tutorEmail = utf8_encode(trim($data[13])); //N
						$tutorUsername = utf8_encode(trim(strtolower($data[14]))); //O
						
						
						// Correct email issues with non utf chars
						//$email = preg_replace('/[^\p{L}\p{N}\s]/u', '', $email);
						//$tutorEmail = preg_replace('/[^\p{L}\p{N}\s]/u', '', $tutorEmail);
						
						
						$gender = substr($gender, 0, 1);
						
						$userID = imperialNetworkUtils::getValidStudentID($userID); // Pad out with Zeros
						
						
						
						//
						
						// Create Tutor Components
						$tutorTitle='';
						$tutorFirstName='';
						$tutorSurname='';

						// Split the tutorname into parts
						$tutorNameParts = explode(" ", $tutorName);
						
						//echo '<pre>';
						//print_r($tutorNameParts);
						//echo '</pre>';
						// Detect if first bit is a title
						$tutorFirstPart = ucfirst($tutorNameParts[0]);
						
						if(in_array($tutorFirstPart, $titlesArray) )
						{
							$tutorTitle = $tutorFirstPart;								
							array_shift($tutorNameParts);
							//echo '<pre>';
							//print_r($tutorNameParts);
							//echo '</pre>';
						}
						
						$tutorSurname = array_pop($tutorNameParts);
						$tutorFirstName = implode(" ", $tutorNameParts);					
						
						// Manually Set vars for medical students
						$user_type=4;
						$deptID = "SM";							
						
						$userTable = $imperialNetworkDB->imperialTableNames()['dbTable_users'];		
						
						// Delete the old data and add it
						if($username<>"" && filter_var($email, FILTER_VALIDATE_EMAIL) )
						{

					
							$importCount++;
							// See if they exist or not
							$userInfo = imperialQueries::getUserInfo($username);
							
							if($userInfo['username']<>"")
							{
								// Do the update
								//echo 'EXISTS = do the update for '.$username.' in '.$userTable.'<br/>';
								
								// Do the update				
								$wpdb->query( $wpdb->prepare(
									"UPDATE  $userTable SET
									userID=%s,
									first_name=%s,
									last_name=%s,
									email=%s,
									user_status=%s,
									programme=%s,
									title=%s,
									yos=%d
									WHERE username = %s",
									$userID,
									$firstName,
									$surname,
									$email,
									$status,
									$programmeCode,
									$title,
									$yos,
									$username
								));
							}
							else
							{
								// Do the insert
								//echo 'DOES NOT EXIST = do the insert<br/>';
								$myFields="INSERT into $userTable (userID, first_name, last_name, username, email, user_type, programme, yos, deptID) ";
								$myFields.="VALUES (%s, %s, %s, %s, %s, %d, %s, %d, %s)";

								$RunQry = $wpdb->query( $wpdb->prepare($myFields,
									$userID,
									$firstName,
									$surname,
									$username,
									$email,
									$user_type,
									$programmeCode,
									$yos,
									$deptID
								));							

							}
							
							// Now process the tutor
							// See if they exist or not
							$tutorInfo = imperialQueries::getUserInfo($tutorUsername);
							
							
							
							// If they have a tutor add to the tutor table
							if($tutorUsername<>"")
							{
								
								
								$wpdb->delete($tutorAllocationstable, array('username' => $username, 'academicYear' => $academicYear));									
								
								
								
								$myFields="INSERT into $tutorAllocationstable (username, tutorUsername, academicYear) ";
								$myFields.="VALUES (%s, %s, %d)";

								$RunQry = $wpdb->query( $wpdb->prepare($myFields,										
									$username,
									$tutorUsername,
									$academicYear
								));
								
								
							}
							
							
							
							if($tutorInfo['username']<>"")
							{
								
								// Do the update
								//echo 'EXISTS = do the update for TUTOR '.$tutorUsername.' in '.$userTable.'<br/>';
								// Do the update	
								
								//echo 'tutorFirstName = '.$tutorFirstName.'<br/>';
								//echo 'tutorSurname = '.$tutorSurname.'<br/>';
								//echo 'tutorEmail = '.$tutorEmail.'<br/>';
								//echo 'title = '.$title.'<br/>';
								//echo 'tutorUsername = '.$tutorUsername.'<br/>';
																
								
								$wpdb->query( $wpdb->prepare(
									"UPDATE  $userTable SET										
									first_name=%s,
									last_name=%s,
									email=%s,										
									title=%s										
									WHERE username = %s",										
									$tutorFirstName,
									$tutorSurname,
									$tutorEmail,										
									$tutorTitle,
									$tutorUsername
								));
								
								
							}
							else
							{
								// Do the insert
								//echo 'DOES NOT EXIST = do the insert<br/>';
								$myFields="INSERT into $userTable (title, first_name, last_name, username, email, user_type, deptID) ";
								$myFields.="VALUES (%s, %s, %s, %s, %s, %d, %s)";

								$RunQry = $wpdb->query( $wpdb->prepare($myFields,	
									$tutorTitle,
									$tutorFirstName,
									$tutorSurname,
									$tutorUsername,
									$tutorEmail,
									1,
									$deptID
								));
							}

						}
						else
						{
							$errorCount++;
							$errorLog.='The Email '.$email.' is invalid.<br/>';
						}	// End of if valid email
						
					}
					$currentRow++;

				} // End CSV loop
				
			} // End if file type is CSV
			// Now delete the temp file
			unlink ($newFilename);
			
			$feedback = '';
						
			$feedback.='<div class="imperial-feedback imperial-feedback-success">';
			$feedback.= '<h2>Upload Report</h2>';
			$feedback.= '<strong>'.$importCount.'</strong> users imported<br/>';
			$feedback.= '<strong>'.$errorCount.'</strong> errors reported';
			if($errorCount>=1)
			{
				$feedback.= '<br/><strong>Error Log</strong><br/> ';$errorLog;
			}
			$feedback.= '</div>';
			
			return $feedback;
		}
	}
	
	
	static function uploadGradesCSV()
	{
		

		// Check the nonce before proceeding;	
		$retrieved_nonce="";
		if(isset($_REQUEST['_wpnonce'])){$retrieved_nonce = $_REQUEST['_wpnonce'];}
		
		
		
		if (wp_verify_nonce($retrieved_nonce, 'CSV_grades_UploadNonce' ) )
		{
			
			// Upload temp file to temp dir un uploads 
			$uploadPath = medlearnUtils::getTempUploadDir();
			$newFilename = $uploadPath.'/tempGradesImport.csv';
			$error = '';	

			
			if(isset($_FILES['csvFile']['tmp_name']))
			{
				
				move_uploaded_file($_FILES['csvFile']['tmp_name'], $newFilename);
				
				// Go through the CSV stuff
				ini_set('auto_detect_line_endings',1);
				$handle = fopen($newFilename, 'r');

				global $wpdb;
				global $imperialNetworkDB;					

				
				$gradesTable = $imperialNetworkDB::imperialTableNames()['dbTable_studentGrades'];
				$assessmentCodesTable = $imperialNetworkDB::imperialTableNames()['dbTable_assessmentCodes'];
				
				$importCount = 0;
				$errorCount = 0;
				$errorLog='';
				
				$currentRow=1;
				
				// Create array of assessment types
				$assessmentCodeArray = array();
									
				while (($data = fgetcsv($handle, 1000, ',')) !== FALSE)
				{

					
					$expectedUserColumns = medlearnData::getExpectedGradeColumns();
					// Error check the columns
					$error='';
					if($currentRow==1)
					{

						$expectCheckCol=1;
						foreach ($expectedUserColumns as $thisCol => $expectedColumn)
						{
					
							$actualCol = strtolower(trim($data[$thisCol]));
							$checkExpectedColumn = strtolower($expectedColumn);
							
						//	echo 'checkExpectedColumn = '.$checkExpectedColumn.'<br/>';
							
							
							//echo 'Check Col '.$expectCheckCol.'<br/>';
							if($checkExpectedColumn <> $actualCol && $expectCheckCol<15)
							{
							
								$error.='<div class="imperial-feedback imperial-feedback-error">';

								$error.= 'Column '.($thisCol+1).' ('.$data[$thisCol].') does not match the expected name ('.$expectedColumn.')';
								$error.='</div>';
	
								return $error;
							}
							
							$expectCheckCol++;
							
							
						}

					}
					else
					{
						
						$studentID = utf8_encode(trim($data[0])); // A
						$academicYear = utf8_encode($data[2]); //C
						$yos = utf8_encode($data[3]); //D
						$assessmentTitle = utf8_encode($data[6]); //G
						$unitMark = utf8_encode($data[7]); //H
						$unitGrade = utf8_encode($data[8]); //I
						$unitCode = utf8_encode($data[10]); //K
						$attemptDate = utf8_encode($data[12]); //M
						
						$studentID = imperialNetworkUtils::getValidStudentID($studentID); // Pad out with Zeros
						
						
						if($attemptDate)
						{
							// Turn the grade date into a date
							$tempDate = DateTime::createFromFormat('d/m/Y', $attemptDate);
							$attemptDate = $tempDate->format('Y-m-d');
							
							
							/*
							echo '<strong>'.$studentID.'</strong><br/>';
							
							echo 'academicYear= '.$academicYear.'<br/>';
							echo 'yos= '.$yos.'<br/>';
							echo 'assessmentTitle= '.$assessmentTitle.'<br/>';
							echo 'unitMark= '.$unitMark.'<br/>';
							echo 'unitGrade= '.$unitGrade.'<br/>';
							echo 'examCode= '.$unitCode.'<br/>';
							echo 'attemptDate= '.$attemptDate.'<br/>';
							*/
						
											
											
											

							
							if($studentID)
							{
								
								// Format the Data
								// Remove any text from year of study to get number only							
								$yos = $numeric_filtered = filter_var($yos, FILTER_SANITIZE_NUMBER_FLOAT);

								// Format Ac year 
								$acYear1 = substr($academicYear, 0, 4);
								$acYear2 = substr($academicYear, -2);
								$academicYear = $acYear1.$acYear2;				
								
								// Remove the old record
								$wpdb->delete($gradesTable, 
									array('studentID' => $studentID, 'academicYear' => $academicYear, 'unitCode' => $unitCode)
								);							
								
								$myFields="INSERT into $gradesTable (studentID, yearOfProgramme, academicYear, unitCode, unitMark, unitGrade, gradeDate) ";
								$myFields.="VALUES (%s, %s, %s, %s, %s, %s, %s)";

								$RunQry = $wpdb->query( $wpdb->prepare($myFields,	
									$studentID,
									$yos,
									$academicYear,
									$unitCode,
									$unitMark,
									$unitGrade,
									$attemptDate
								));	
								
								$importCount++;
							}
						}
						
						// Add the assessment code to the array for adding later
						$assessmentCodeArray[$unitCode] = $assessmentTitle;
					}
					$currentRow++;

				} // End CSV loop
				
				
				// Go through the assessment code array adding it to the table				
				foreach ($assessmentCodeArray as $unitCode => $unitTitle)
				{				
					// See if the code exists					
					if($unitCode)
					{
						$unitCodeCheck = imperialQueries::getAssessmentCode($unitCode);
						
						if(!isset($unitCodeCheck['unitTitle']) )
						{
							$myFields="INSERT into $assessmentCodesTable (unitCode, unitTitle) ";
							$myFields.="VALUES (%s, %s)";

							$RunQry = $wpdb->query( $wpdb->prepare($myFields,	
								$unitCode,
								$unitTitle
							));	
						}
					}					
				}
			} // End if file type is CSV
			// Now delete the temp file
			//unlink ($newFilename);
			
			$feedback = '';
						
			$feedback.='<div class="imperial-feedback imperial-feedback-success">';
			$feedback.= '<h2>Upload Report</h2>';
			$feedback.= '<strong>'.$importCount.'</strong> users imported<br/>';
			$feedback.= '<strong>'.$errorCount.'</strong> errors reported';
			if($errorCount>=1)
			{
				$feedback.= '<br/><strong>Error Log</strong><br/> ';$errorLog;
			}
			$feedback.= '</div>';
			
			return $feedback;
		}
	}	

}



?>