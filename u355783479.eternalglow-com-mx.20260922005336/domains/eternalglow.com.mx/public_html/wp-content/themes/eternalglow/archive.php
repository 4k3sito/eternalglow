<?php get_header(); ?>

    <?php $term = get_current_term(); ?>
    <div <?php post_class('section'); ?>>
        <div class='entry'>
            <div class='container'>
                <div class='entry-content'>

            
                    <h1><?php _p($term->name); ?></h1>
                    <div class="height-40"></div>
                    <?php if(have_posts()){ ?>
                        <?php get_template_part('loop', 'posts'); ?>
                    <?php }  else { ?>
                        <?php get_template_part('no-found'); ?>
                    <?php } ?>
                    
                </div>
            </div>
        </div>
    </div>
    
<?php get_footer(); ?>