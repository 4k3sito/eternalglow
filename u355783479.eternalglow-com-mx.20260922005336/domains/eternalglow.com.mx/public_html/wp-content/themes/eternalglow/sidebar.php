<?php
    $query = new WP_Query(array(
        'posts_per_page' => 5,
    ));
    global $post;
?>
<div class='sidebar width-md-480 nlm'>
    <div class='mb-48'>
        <h2>Entradas recientes</h2>
    </div>
    <?php if ( $query->have_posts() ) { ?>
        <?php while ( $query->have_posts() ) { ?>
            <?php 
                $query->the_post(); 
                $thumb = get_thumbnail(0, 'medium');
                $date = get_the_date();
            ?>
            <div class='border rounded-10 overflow-hidden mb-32 position-relative'>
                <a href='<?php the_permalink(); ?>' class='row g-0'>
                    <div class='col-auto'>
                        <div class="d-block ratio ratio-16x9 h-100 width-200">
                            <img src='<?php _p($thumb->url); ?>' alt='<?php the_title(); ?>' class='object-fit-cover'/>
                        </div>
                    </div>
                    <div class='col'>
                        <div class='fst-italic p-sm-24 p-20 d-flex flex-column justify-content-center h-100'>
                            <h4 class='fs-18 fw-semibold mb-8'><?php the_title(); ?></h4>
                            <div class='fs-16 fw-light mb-8'><?php _p($date); ?></div>
                            <div class='fs-16 fw-semibold text-body text-decoration-underline'>
                                Continuar leyendo
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
        <?php wp_reset_postdata(); ?>
    <?php } ?>
</div>