<?php

$userID = isset( $_POST['userID'] ) ? intval( $_POST['userID'] ) : 0;

if ( $userID )
{

	$uploadDirInfo = array( 
		'basedir' => $_POST['basedir'], 
		'baseurl' => $_POST['baseurl'] 
	);
	$uploadDir = $uploadDirInfo['basedir'] . '/umedia/' . $userID;

	if ( ! file_exists( $uploadDir ) ) {
		mkdir( $uploadDir, 0777, true );
	}

	ini_set( "memory_limit", "12M" ); 
	ini_set( "max_execution_time", "300" );
	ini_set( "allow_url_fopen", "1" );

	$urls = array();
	foreach ( $_FILES['images']['error'] as $j => $error )
	{
		if ( $error == 0 )
		{
			$tmp_name = $_FILES['images']['tmp_name'][ $j ];
			$fileExt = strrchr( $_FILES['images']['name'][ $j ], '.' );
			$filename = '_profile-img' . $fileExt;
			
			$success = move_uploaded_file( $tmp_name, $uploadDir . '/' . $filename );
			if ( $success ) {
				$urls[] = $uploadDirInfo['baseurl'] . '/umedia/' . $userID . '/' . $filename;
			}
		}
	}

	if ( isset( $urls[0] ) ) {
		echo implode( ',', $urls );
	}
	
}

die();

?>