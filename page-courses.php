<?php 
 get_header();
 ?> 
 <main id="content">
<header class="header">
</header>
<div class="entry-content">

<h1>My Courses</h1>
<?php
$currentUserID = get_current_user_id();

?>

Coming Soon

</div>
</main>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
<style>
#content {
	width:100%;
}
</style>

<?php get_footer(); ?>