<?php 
    $thumb = get_thumbnail(0, 'medium');
    $date = get_the_date();
    $author = get_the_author_posts_link();
    $author = str_replace('<a', '<a class="text-body"', $author);
?>
<div class='h-100 mx-auto d-flex flex-column rounded-25 overflow-hidden p-24 border'>
    <div class='mb-24'>
        <a href='<?php the_permalink(); ?>' class="d-block ratio ratio-4x3 overflow-hidden rounded-4">
            <img src='<?php _p($thumb->url); ?>' alt='<?php the_title(); ?>' class='object-fit-cover'/>
        </a>
    </div>
    <div class='d-flex flex-column flex-grow-1'>
        <h4 class='mb-24'>
            <a href='<?php the_permalink(); ?>'><?php the_title(); ?></a>
        </h4>
        <div class='nlm mb-24'>
            <?php the_excerpt(); ?>
        </div>
        <div class='mt-auto'>
            <a href='<?php the_permalink(); ?>' class='btn btn-outline-primary btn-block'>
                Ver más
            </a>
        </div>
    </div>
</div>