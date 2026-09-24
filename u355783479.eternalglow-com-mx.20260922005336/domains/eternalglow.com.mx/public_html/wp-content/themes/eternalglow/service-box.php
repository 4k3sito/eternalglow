<?php 
    $thumb = get_thumbnail(0, 'medium');
    
?>
<div>
    <div class='mb-24'>
        <a href='<?php the_permalink(); ?>' class="d-block ratio ratio-1x1 overflow-hidden rounded-20">
            <img src='<?php _p($thumb->url); ?>' alt='<?php the_title(); ?>' class='object-fit-cover'/>
        </a>
    </div>
    <div class='d-flex flex-column flex-grow-1'>
        <h4 class='mb-16 font-base'>
            <a href='<?php the_permalink(); ?>'><?php the_title(); ?></a>
        </h4>
        <div class='mt-auto'>
            <a href='<?php the_permalink(); ?>' class='btn btn-outline-primary w-auto'>
                Ver más
            </a>
        </div>
    </div>
</div>