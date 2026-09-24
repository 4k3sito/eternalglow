<?php
    $animation = get_css_animation($animation);
    $class = generate_class($class, array('jss-subtitle', $responsive, $animation));
?>
<div class='<?php _p($class); ?>'>
    <div class="row g-8 align-items-center justify-content-center justify-content-md-start text-center">
        <div class="col-md-auto">
            <img src="<?php images_url('star.svg'); ?>" alt='' />
        </div>
        <div class="col-md-auto">
            <h4 class="mb-0"><?php _p($text); ?></h4>
        </div>
    </div>
</div>