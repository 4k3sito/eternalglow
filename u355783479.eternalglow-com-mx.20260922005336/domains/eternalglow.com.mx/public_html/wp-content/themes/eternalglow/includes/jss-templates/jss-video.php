<?php
    $animation = get_css_animation($animation);
    $class = generate_class('jss-video', array($class, $animation, 'text-center'));
    $image = get_theme_image($image);
?>
<div class='<?php _p($class); ?>'>
    <div class="ratio ratio-16x9">
        <a href="<?php _p($video); ?>" data-fancybox>
            <img src='<?php _p($image->url); ?>' alt='' >
        </a>
    </div>
</div>