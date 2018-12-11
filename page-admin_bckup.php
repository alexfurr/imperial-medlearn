<?php 
 get_header();

// Check if they are a sys admin or faulcty admin.
// If not the exit

if(!class_exists('imperialQueries') )
{
	echo 'Imperial Network Plugin not active';
	die();
}


$canViewPage=false;
// Get admin list for this dept
if(isset($_GET['deptID']) )
{
	$deptID = $_GET['deptID'];
}
else
{
	echo 'No Dept ID found';
}

// Check if is dept admin
$currentUserID = get_current_user_ID();
$isDeptAdmin = imperialNetworkUtils::isDeptAdmin($currentUserID);
				
if($isDeptAdmin==true)
{
	$canViewPage=true;
}	

// Finally see if they are network admin
if(imperialNetworkUtils::isNetworkAdmin()==true) 
{
	$canViewPage=true;
}


if($canViewPage==false)
{
	
	$siteRoot =  get_site_url();
	echo '
	<script>	
//	window.location.replace("'.$siteRoot.'");
	</script>
	';

	die();
}

// Get the dept Name
$deptInfo = imperialQueries::getDeptInfo($deptID);
$deptName = $deptInfo['deptName'];



if(isset($_GET['action']) )
{
	$myAction = $_GET['action'];
	
	switch ($myAction)
	{
		
		case "addAdmin":
			$username = $_POST['username'];
			
			imperialNetworkActions::addDeptAdmin($deptID, $username);
			
			
		break;
		
	}
	
}





?>
<main id="content">
<header class="header">
<h1 class="entry-title">
<?php
echo $deptName.' Admin';
?>
</h1>
</header>
<div class="entry-content">
<div class="ekTabsWrap vertTabs">
<div class="ekTabs ekTabsCustom">
<ul>
<li>Courses</li>
<li>Admins</li>
</ul>
</div>

<div class="ekTabsContent">
<div>	
List of Courses for this Faculty
</div>
<div>


<h2>Department Admins</h2>
<div id="deptAdminList">
<?php

echo '<form class="pure-form" action="?deptID='.$deptID.'&tab=2&action=addAdmin" method="post">';
echo '<input type="text" name="username" id="username">';
echo '<button type="submit" class="pure-button pure-button-primary">Sign in</button>';

echo '</form>';






echo imperialNetworkDraw:: drawDeptAdminList($deptID);
?>
</div>


</div>
</div> <! -- End of ekTabs Content -->

</div><! -- End of ekTabs wrap -->

</div>

</div>

</main>
<style>
#content
{
	width:100%;
}
</style>
<?php get_footer(); ?>