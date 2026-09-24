<?php
    $animation = get_css_animation($animation);
    $class = generate_class($class, array('jss-services-list', $responsive, $animation));
    $terms = get_terms([
        'taxonomy' => 'tax_category',
        'hide_empty' => false,
    ]);
    $active = $terms ? $terms[0]->term_id: 0;
    global $post;
?>
<div class='<?php _p($class); ?>'>

    <div class="mx-auto rounded-24 bg-white shadow p-24 mb-40">
        <div class="row g-sm-32 g-16">
            <?php foreach($terms as $term){ ?>
                <div class="col-sm-4">
                    <a href="#<?php _p($term->slug); ?>" id='service-<?php _p($term->slug); ?>' class="btn btn-block btn-service<?php _p($term->term_id == $active ? ' active': ''); ?>">
                        <?php _p($term->name); ?>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <div>
        <?php foreach($terms as $term){ ?>
            <div id="<?php _p($term->slug); ?>" data-tab="#service-<?php _p($term->slug); ?>" class="services services-content <?php _p($term->slug); ?><?php _p($term->term_id == $active ? ' active': ''); ?>">
                <div class="row g-24">
                    <?php
                        $posts = get_services($term->term_id);
                    ?>
                    <?php foreach($posts as $post){ ?>
                        <?php setup_postdata($post); ?>
                        <div class="col-sm-4">
                            <?php get_template_part('service', 'box'); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php wp_reset_postdata(); ?>

</div>