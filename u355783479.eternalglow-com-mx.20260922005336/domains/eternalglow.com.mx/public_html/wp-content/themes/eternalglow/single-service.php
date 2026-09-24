<?php get_header(); ?>
	
    <?php the_post(); ?>

    <?php
        $thumb = get_thumbnail();
        $terms = get_the_terms(get_the_ID(), 'tax_category');
        $related = [];
        if($terms){
            $related = get_related_services(get_the_ID(), $terms[0]->term_id);
        }
    ?>

    <div <?php post_class('section'); ?>>
        <div class='entry'>
            <div class='container'>
                <div class='entry-content'>

                    <div class='row g-md-40 g-48'>
                        <div class='col-md'>

                            <div class='nlm'>
                                <?php the_content(); ?>
                            </div>
                            
                        </div>
                        <?php if($terms && $related){ ?>
                            <div class='col-md-auto d-none d-md-block'>
                                <div class="border p-16 rounded-8 width-md-305 maxw-305">
                                    <div class="bg-primary ml--16 mb-24 rounded-end-8 px-16 py-8 text-white">
                                        <?php _p($terms[0]->name); ?>
                                    </div>
                                    <div class="overflow-auto maxh-270">
                                        <div class="d-grid gap-12 overflow-hidden">
                                            <?php foreach($related as $_post){ ?>

                                                <div>

                                                    <div class="row g-10 align-items-center">
                                                        <div class="col">
                                                            <a href='<?php the_permalink($_post); ?>'>
                                                                <?php _p($_post->post_title); ?>
                                                            </a>
                                                        </div>
                                                        <div class="col-auto">
                                                            <a href='<?php the_permalink($_post); ?>'>
                                                                <img src="<?php images_url('arrow-right.svg'); ?>" alt="" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                    
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
<?php get_footer(); ?>