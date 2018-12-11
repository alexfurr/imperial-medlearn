<?php get_header(); ?>
<div id="imperial_page_title">
<h1 class="entry-title">Search Results</h1>
</div>
<main id="content">
<div id="contentWrap">


<?php
$searchType = $_GET['searchType'];
$searchStr = $_GET['s'];


$currentUserType = $_SESSION['userType'];

$homeURL = get_site_url();

echo '<h2>Searching '.$searchType.' for "'.$searchStr.'"</h2>';


echo '<form role="search" method="get" id="search-form" action="'.$homeURL.'/">';		
echo '<input type="" name="s" value="'.$searchStr.'">';
echo '<input type="hidden" name="searchType" value="'.$searchType.'">';
echo '</form>';

switch ($searchType)
{
	case "sites":
	
	
	
		global $wpdb;
		global $imperialNetworkDB;
		$table_name = $imperialNetworkDB::imperialTableNames()['dbTable_courses'];
		$SQL='Select * FROM '.$table_name.' WHERE course_name LIKE "%'.$searchStr.'%"  ORDER by course_name ASC, academic_year DESC';
		
		$mySites = $wpdb->get_results( $SQL, ARRAY_A );
				
		if(count($mySites)==0 )
		{
			echo 'No sites found';
		}
		else
		{
			echo '<table class="imperial-table">';
			
			echo '<tr><th>Site Name</th><th>Year of Study</th><th>Academic Year</th></tr>';
			
			foreach ($mySites as $siteInfo)
			{
				
				$academicYear = $siteInfo['academic_year'];
				$academicYear = imperialNetworkUtils::getNiceAcademicYear($academicYear);
				$yos = $siteInfo['yos'];
				if($yos=="" || $yos=="0"){$yos="-";}
				
				$siteID = $siteInfo['blogID'];
				$siteMeta = get_blog_details( $siteID );
				
				$siteName = $siteMeta->blogname;
				$siteURL = $siteMeta->siteurl;
				
				echo '<tr>';
				echo '<td><a href="'.$siteURL.'">'.$siteName.'</a></td>';
				echo '<td>'.$yos.'</td>';
				echo '<td>'.$academicYear.'</td>';
				echo '</tr>';
				
				
			}			
			echo '</table>';
		}
		
	
	break;
	
	
	case "people":

	
		$myUsers = imperialQueries::getUsers($searchStr);
	
	
		// Get Department lookup array
		$facultyArray = imperialQueries::getFacultyLookupArray();
		
				
		if(count($myUsers)==0 )
		{
			echo 'No people found';
		}
		else
		{
			echo '<table class="imperial-table">';			
			echo '<tr><th></th><th>Name</th>';
			if($currentUserType==1)
			{
				echo '<th>Username</th>';
			}
			
			echo '<th>Email</th><th>Year of Study</th><th>User Type</th><th>Department</th></tr>';
			
			foreach ($myUsers as $userInfo)
			{
				

				$name = $userInfo['last_name'].', '.$userInfo['first_name'];
				$username = $userInfo['username'];
				$email = $userInfo['email'];
				$deptID = $userInfo['deptID'];
				$userType = $userInfo['user_type'];
				$yos = $userInfo['yos'];
				$cid = $userInfo['userID'];
				
				if($yos=="" || $yos==0)
				{
					$yos="-";
				}

				$args = array(			
					"CID"		=> $cid,
				);
				$avatarURL = get_user_avatar_url( $args);			
				
				$userTypeStr = imperialNetworkUtils::getUserTypeStr($userType);
				
				echo '<tr valign="middle">';
				echo '<td width="75px">';
				echo '<a href="'.$homeURL.'/profile/?username='.$username.'"><img src="'.$avatarURL.'"></a>';
				echo '</td>';
				echo '<td><a href="'.$homeURL.'/profile/?username='.$username.'">'.$name.'</a></td>';
				if($currentUserType==1)
				{
					echo '<td class="greyText">'.$username.'</td>';
				}				
				
				echo '<td><a href="mailto:'.$email.'">'.$email.'</a></td>';
				echo '<td>'.$yos.'</td>';
				echo '<td>'.$userTypeStr.'</td>';
				echo '<td>'.$facultyArray[$deptID].' ('.$deptID.')</td>';
				
				echo '</tr>';
				
			}			
			echo '</table>';
		}
		
		break;
		
		
		default:
		?>
		
			<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>
			<?php


			$ID = get_the_id();
			$pageLink = get_the_permalink($ID);



			echo '<div class="contentBox">';
			echo '<div class="contentBoxInner">';
			echo '<div class="searchResultPageType">';
			echo '</div>';
			echo '<a href="'.$pageLink.'"><h2>'.get_the_title($ID).'</h2></a>';
			echo the_excerpt();
			echo '<br/><a href="'.$pageLink.'">Read more</a>';
			echo '</div>';
			echo '</div>';

			?>

			<?php endwhile; ?>
			<?php else : ?>
			<article id="post-0" class="post no-results not-found">
			<h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'generic' ); ?></h1>


			<div class="entry-content">
			<p><?php esc_html_e( 'Sorry, nothing matched your search. Please try again.', 'generic' ); ?></p>
			<?php get_search_form(); ?>
			</div>
			</article>
			<?php endif; ?>				
				
		<?php
		break;
	
	break;
	
}





?>

</main>
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>