<?php 
/* Template Name: Help Home page */ 
 get_header();
 
$searchAction = get_site_url().'?s=search';
$templateImageURL = get_stylesheet_directory_uri().'/assets/help-images/';
 

 
 ?>
<main id="content">
<div class="entry-content">
<div class="imperial-homepage"> <!-- Start of homepage container -->

<div class="help-home-search">
<div class="label-wrap">
<label for="help-search">Search the help pages</label>
</div>
<div class="help-home-searchbox">

<form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
<input type="text" name="s" id="s" class="main-search-box" placeholder="Search for anything..">
<button type="submit" value="Search" class="main-search-button">Search</button>
</form>
</div>
</div>

<div class="medhelp-home-wrap">

<?php
$itemsArray = array(
    array(
        "name"  => "Technical Help",
        "image" => "technical-help.jpg",
        "url"   => "technical-help",
        "alt"   => "Searching on the internet",
    ),
    array(
        "name"  => "Careers",
        "image" => "careers.jpg",
        "url"   => "careers",
        "alt"   => "Candidates waiting for an interview",
    ),

    array(
        "name"  => "Clinical Skills",
        "image" => "clinical-skills.jpg",
        "url"   => "clinical-skills",
        "alt"   => "Clinical setting",
    ),

    array(
        "name"  => "Welfare and Tutoring",
        "image" => "welfare.jpg",
        "url"   => "welfare-and-personal-tutoring",
        "alt"   => "Group of classmates chatting together",

    ),

    array(
        "name"  => "A-Z",
        "image" => "a-z.jpg",
        "url"   => "a-z",
        "alt"   => "Thinking student",
    )
);




$i=1;
$html='';
foreach($itemsArray as $itemMeta)
{

    $thisName = $itemMeta['name'];
    $thisImage = $itemMeta['image'];
    $thisURL = $itemMeta['url'];
    $thisALT = $itemMeta['alt'];
    $html.= '<div class="mhi mhi-'.$i.'"><a href="'.$thisURL.'" class="mhi-btn"><span>'.$thisName.'</span>';
    $html.= '<img src="'.$templateImageURL.$thisImage.'" alt="'.$thisALT.'"></a></div>'; 

   $i++;
}

echo $html;


?>

 </div>
                            
                   


</div> <!-- End of Homepage Container-->
</div> <!-- End of entry content -->
</main>
<?php get_footer(); ?>