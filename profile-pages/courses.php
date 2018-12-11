<h2>My Courses</h2>
<?php

$userID = getUserIDfromUsername($username);

echo imperialNetworkDraw::drawUserSites($userID);
		
?>