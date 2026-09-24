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
                <a class="d-block ratio ratio-1x1 rounded-20 overflow-hidden image-hover-scale" data-fancybox='<?php _p($fancybox); ?>' href='<?php _p($image->url); ?>'>
                    <img src="<?php _p($image->url); ?>" alt="" class="object-fit-cover" />
                </a>
            </div>
        <?php } ?>
    </div>

</div>