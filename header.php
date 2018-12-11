<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width" />
        <?php wp_head(); ?>
    </head>
    
    <body <?php body_class(); ?>>
        <div id="wrapper" class="hfeed">
            
            <header id="header">
				<div id="branding">
				<div id="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_html( get_bloginfo( 'name' ) ); ?>" rel="home">
				<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
				</a>
				</div>
				<div id="site-description"><?php bloginfo( 'description' ); ?></div>
				</div>			
			
			
                <nav id="menu" class="desktop-only">
                    <!--<label class="toggle" for="toggle">&#9776; Menu</label>
                    <input id="toggle" class="toggle" type="checkbox" />-->
                    <?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'depth' => 2 ) ); ?>
                </nav>
                
                <div class="mobile-menu mobile-only">
                    <div id="mobile_menu_toggle_button"><i class="fas fa-bars"></i></div>
                    <div id="mobile_menu_container">
                        <?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'depth' => 1 ) ); ?>
                    </div>
                </div>
                
                <script>
                jQuery( document ).ready( function () {
                    jQuery('#mobile_menu_toggle_button').on( 'click', function ( e ) {
                         jQuery('#mobile_menu_container').slideToggle();
                    });
                });
                </script>
                
                
            </header>
            
            <div id="container">
            