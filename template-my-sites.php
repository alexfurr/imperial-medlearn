<?php 
/* Template Name: My Sites Page */ 
 get_header(); ?>
<main id="content">
<header class="header">
<h1 class="entry-title">
<?php the_title(); ?>
</h1>
</header>
<div class="entry-content">


<?php
$user_id = get_current_user_id();
$user_blogs = get_blogs_of_user( $user_id );

foreach ($user_blogs AS $user_blog) {
	
	$siteURL = $user_blog->siteurl;
	$blogName = $user_blog->blogname;
    echo '<li><a href="'.$siteURL.'">';
	echo $blogName;
	echo '</li>';
}
echo '</ul>';
?>


</div>
</main>
<?php  get_sidebar(); ?>
<?php get_footer(); ?>