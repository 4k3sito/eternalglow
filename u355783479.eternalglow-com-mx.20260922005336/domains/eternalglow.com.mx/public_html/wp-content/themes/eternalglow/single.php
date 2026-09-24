<?php get_header(); ?>
	
    <?php the_post(); ?>

    <?php
        $thumb = get_thumbnail();
        $date = get_the_date();
        $author = get_the_author_posts_link();
        $author = str_replace('<a', '<a class="text-body"', $author);
    ?>

    <div <?php post_class('section'); ?>>
        <div class='entry'>
            <div class='container'>
                <div class='entry-content blog-content'>
						
					<div class='height-md-80 height-48'></div>

                    <div class='row g-md-40 g-48'>
                        <div class='col-md'>

                            <h1 class='mb-md-32 mb-48 text-center text-md-left'><?php the_title(); ?></h1>
                            <div class='fs-14 fs-md-18 fw-medium mb-md-56 mb-48 text-center text-md-left'>
                                By <?php _p($author); ?>, <?php _p($date); ?>
                            </div>
                            
                            <div class="ratio ratio-16x9 mb-24 rounded-3 overflow-hidden">
                                <img src='<?php _p($thumb->url); ?>' alt='<?php the_title(); ?>' class='object-fit-cover'/>
                            </div>

                            <div class='nlm'>
                                <?php the_content(); ?>
                            </div>

                            <div class='height-md-56 height-48'></div>

                            <div class='row justify-content-end align-items-center g-30'>
                                <?php if(has_tag()){ ?>
                                    <div class='col-md'>
                                        <div class='row g-15 fs-14 a-secondary fw-medium fst-italic'>
                                            <?php the_tags('<div class="col-auto">', '</div><div class="col-auto">', '</div>'); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class='col-md-auto'>
                                    <?php get_template_part('share'); ?>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-auto'>
                            <?php get_sidebar(); ?>
                        </div>
                    </div>

					<div class='height-80'></div>
                    
                </div>
            </div>
        </div>
    </div>
    
<?php get_footer(); ?>