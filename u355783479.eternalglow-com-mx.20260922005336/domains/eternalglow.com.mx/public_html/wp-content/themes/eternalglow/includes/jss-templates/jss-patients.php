<?php
    $animation = get_css_animation($animation);
    $class = generate_class($class, array('jss-patients', $responsive, $animation));
    $images = explode(',', $images);
    $images = array_map('get_theme_image', $images);
    $fancybox = 'images-'.random_string();
?>
<div class='<?php _p($class); ?>'>

    <div class="row g-md-24 g-8">
        <?php foreach($images as $image){ ?>
            <div class="col-md-4">
                <a class="d-block ratio ratio-1x1 rounded-20 overflow-hidden image-hover-scale" data-fancybox='<?php _p($fancybox); ?>' href='<?php _p($image->url); ?>' aria-label='<?php _p(esc_attr($image->alt ? $image->alt : 'Ver imagen')); ?>'>
                    <?php _p(get_theme_img($image, array('class' => 'object-fit-cover', 'loading' => 'lazy', 'sizes' => '(max-width: 767.98px) calc(100vw - 64px), 400px'))); ?>
                </a>
            </div>
        <?php } ?>
    </div>

</div>