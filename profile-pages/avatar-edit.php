
<a href="<?php echo $pageURL; ?>" class="backButton">Back to your profile</a>
<h2>Change your Avatar</h2>		

<div class="user-summary">
	<div class="user-avatar fL">
		<div class="profile-avatar"><?php
		
		$args = array(
			'CID' => $_SESSION['userID'],
			'userID' => get_current_user_id(),
			'size'	=> "square",
		);

			
		echo get_user_avatar( $args );
		?></div>
	</div>
</div>

<?php                
if ( has_avatar( $args ) ) { 
?>
	<div class="clearB"></div>
	<form action="<?php echo $pageURL; ?>" method="post">
		<input type="submit" name="delete_user_avatar" value="<?php _e( 'Remove Avatar', 'mexsu' ); ?>" />
	</form>
<?php 
}

get_template_part('libs/avatar-upload/avatar-upload-form');
?>
