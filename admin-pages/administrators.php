<h2>Department Admins</h2>

<?php	

$myAction = "addDeptAdmin";
$args = array
(
	"myAction"	=> "addDeptAdmin",
	"deptID" 	=> $deptID, 
	"updateDiv"	=> "deptAdminList",	
);

echo imperialNetworkDraw::userSearchForm($args);						
?>
<div id="deptAdminList">
	<?php
	//echo '<form class="imperial-form" action="?deptID='.$deptID.'&tab=2&action=addAdmin" method="post">';
	//echo    '<input type="text" name="username" id="username">';
	//echo    '<button type="submit" class="imperial-button">Add User</button>';
	//echo '</form>';
	echo medlearnDraw::drawDeptAdminList($deptID);
	?>
</div> <!-- End of Dept Admin List -->