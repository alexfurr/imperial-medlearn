<?php

$userID = get_current_user_id();
$uploadDirInfo = wp_upload_dir();
$form_process_url = get_permalink();

?>
		
	<div class="imageUploadWrap">
		
		<h2><?php _e( 'Upload a new image', 'mexsu' ); ?></h2>
		
		<div id="uploaded-images-wrap"></div>
		
		<div class="textWrapWrap">
			<div id="textWrap1" class="textWrap"><button id="switchToRegular">Upload Avatar Image</button>
			<br/>OR<br/></div>
			<div id="textWrap2" class="textWrap" style="display:none;"><button id="switchToDrop"><?php _e( 'Use drag-drop uploader', 'mexsu' ); ?></button></div>
		</div>
		
		<div class="uploaders">
			<div id="dropArea" class="drop-files-container">
				<?php _e( 'Drop your image here', 'mexsu' ); ?>
				<br><br>
			</div>
			
			<div id="formWrap" style="display:none;">
				<form enctype="multipart/form-data" id="uploadForm" method="post" action="<?php echo $form_process_url; ?>">
					<input type="file" id="filesInput" name="images[]" multiple="multiple" value="" />
					<input type="hidden" name="userID" value="<?php echo $userID; ?>" />
					<input type="hidden" name="basedir" value="<?php echo esc_attr( $uploadDirInfo['basedir'] ); ?>" />
					<input type="hidden" name="baseurl" value="<?php echo esc_attr( $uploadDirInfo['baseurl'] ); ?>" />
					<input type="submit" name="submitProfilePic" value="Upload" id="formSubmit" />
				</form>
			</div>
			
			<div id="progressWrap" style="display:none;">
				<progress></progress>
			</div>
		</div>
		
		<div class="clearB"></div>
	
		
	</div>	
		
	
	<script>
	
	var DROP_IMG = {
			
		init: function () {
			this.setListeners();
		},
	
		addFiles: function ( dropped ) {
			var uploadFormData = new FormData( jQuery('#uploadForm')[0] ); 
			var fileType;
			var j;
			
			if( dropped.length > 0 ) { 
				for( var j = 0; j < dropped.length; j++ ) {
					fileType = dropped[ j ].type;
					if ( 'image/jpeg' === fileType || 'image/png' === fileType || 'image/gif' === fileType ) {
						uploadFormData.append( 'images[]', dropped[ j ] );  
					}
				}
			}
			jQuery('#progressWrap').show();
			this.request( uploadFormData );
		},
		
		request: function ( data ) {
			jQuery.ajax({
				url:			'<?php echo get_stylesheet_directory_uri(); ?>/libs/avatar-upload/avatar-form-process.php',
				type: 			'POST',
				data:			data,
				cache: 			false,
				contentType:	false,
				processData: 	false,
				success: function ( response, status ) {
					console.log( response );
					jQuery('#progressWrap').hide();
					if ( response != '' ) {
						var images = response.split(',');
						DROP_IMG.drawImages( images );
					}
				},
				error: function ( jqXHR, status, error ) {
					jQuery('#progressWrap').hide();
					console.log( error );
				},
				xhr: function() {  // Custom XMLHttpRequest
					var myXhr = jQuery.ajaxSettings.xhr();
					if( myXhr.upload ){ // Check if upload property exists
						myXhr.upload.addEventListener( 'progress', DROP_IMG.uploadProgress, false ); // For handling the progress of the upload
					}
					return myXhr;
				},
			});
		},
		
		uploadProgress: function ( e ) {
			if( e.lengthComputable ) {
				jQuery('progress').attr({ value: e.loaded, max: e.total });
			}
		},
		
		
		drawImages: function ( images ) {
			var j;
			var l = images.length;
			var html = '';
			
			html += '<form method="post" action="<?php echo $form_process_url; ?>">';
			for ( j = 0; j < l; j += 1 ) {
				html += '<div class="imageThumb"><img src="' + images[ j ] + '?hash=<?php echo create_hash(); ?>" /></div>';
				html += '<input type="hidden" name="newProfileImageFile_' + j + '" value="' + images[ j ] + '" />';
			}
			html += '<br class="clearB"><input type="submit" name="submit_user_avatar" value="<?php _e( 'Use this image', 'mexsu' ); ?>" /><br><br>';
			
			html += '</form>';
			jQuery('#uploaded-images-wrap').append( html );
		},
		
		
		setListeners: function () {
		
			//uploader switch buttons
			jQuery('#switchToRegular').on( 'click', function () {
				jQuery('#textWrap1, #dropArea').hide();
				jQuery('#textWrap2, #formWrap').slideDown();
			});
			jQuery('#switchToDrop').on( 'click', function () {
				jQuery('#textWrap2, #formWrap').hide();
				jQuery('#textWrap1, #dropArea').slideDown();
			});
			
			//drop-area
			jQuery('.drop-files-container').on( 'dragover', function ( e ) {
				e.preventDefault();  
				e.stopPropagation();
				jQuery('.drop-files-container').addClass('dragging');
			});
			jQuery('.drop-files-container').on( 'dragleave', function( e ) {
				e.preventDefault();  
				e.stopPropagation();
				jQuery('.drop-files-container').removeClass('dragging');
			});
			jQuery('.drop-files-container').on( 'drop', function( e ) {
				e.preventDefault();  
				e.stopPropagation();
				jQuery( this ).removeClass('dragging');
				var files = e.originalEvent.dataTransfer.files;
				DROP_IMG.addFiles( files ); 
				return false;
			});
			
			//prevent browser default action if files not dropped in drop-area
			jQuery('.wrap').on( 'dragover', function( e ) {
				e.preventDefault();  
				e.stopPropagation();
			});
			jQuery('.wrap').on( 'dragleave', function( e ) {
				e.preventDefault();  
				e.stopPropagation();
			});
			jQuery('.wrap').on( 'drop', function( e ) {
				e.preventDefault();  
				e.stopPropagation();
			});
			
			//form submit
			jQuery('#formSubmit').on( 'click', function ( e ) {
				DROP_IMG.addFiles([]);
				e.preventDefault();
			});
		}
	
	};
	

	jQuery( document ).ready( function () {
		DROP_IMG.init();
	});

	</script>

	
	
	
	
	
	
	
	<style>
	
	.imageUploadWrap {
		clear:		both;
		padding:	20px 0 0 0;
	}
		
	.drop-files-container {
		background-color:#fff;
		width:auto;
		height:200px;
		border: 2px dashed #d0d0d0;
		padding-top: 80px;
		text-align:	center;
		font-size:	28px;
		color:		#aaa;
	}
	.dragging {
		background-color:#acf;
		border: 2px solid #a0a0a0;
	}
	
	#formWrap {
		background:#fff;
		width:auto;
		height:200px;
		border: 1px solid #d8d8d8;
		margin:	0px auto;
		padding-top: 80px;
		text-align:	center;
	}
	.textWrap,
	#uploaded-images-wrap {
		width:auto;
		margin:	0px auto;
		text-align:	left;
		padding-top: 0px;
		color: #888;
	}
	
	.textWrap {
		padding-top: 0px;
	}
	

	#progressWrap  {
		width:60%;
		margin:	0px auto;
		text-align:	center;
		padding: 10px 0 0 0;
	}
	
	button,
	#formSubmit	{
		padding:10px;
	}
	
	.imageThumb {
		width: 180px;
		height:auto;
		margin: 0 0 10px 0;
		float: left;
		overflow:hidden;
	}
	.imageThumb img {
		width:	100%;
		height:	auto;
	}
	.clearB { 
		clear: both; 
	}
	
	.spinnerwrap {
		display: inline-block;
		height:10px;
		padding:0px 100px 0 100px;
	}
	.spinner {
		background-image: url('loader.gif');
		background-repeat: repeat;
		background-color:	#0f0;
		border:1px solid #d0d0d0;
	}
	
	.uploaders {
		height:240px;
	}
	
	</style>
	
	
	
