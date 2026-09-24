<?php get_header(); ?>
	
    <?php the_post(); ?>

    <div <?php post_class('section'); ?>>
        <div class='entry'>
            <div class='container'>
                <div class='entry-content'>
  
                    <?php the_content(); ?>
                    
                </div>
            </div>
        </div>
    </div>
    
<?php get_footer(); ?>