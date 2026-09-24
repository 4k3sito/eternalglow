<?php 
	extract($args);
    global $wp_query; 
    $paged = get_query_var('paged', 1);
    $query = isset($query) ? $query: $wp_query;
?>
<div class='row g-32'>
    <?php while($query->have_posts()){ ?>
        <?php $query->the_post(); ?>
        <div class='col-sm-6 col-md-4'>
            <?php get_template_part('single', 'box'); ?>
        </div>
    <?php } ?>
</div>
<?php if($query->max_num_pages > 1){ ?>
    <div class='d-flex justify-content-center mt-48'>
        <?php pagination($paged, $query->max_num_pages); ?>
    </div>
<?php } ?>
