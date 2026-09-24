<?php
    $animation = get_css_animation($animation);
    $class = generate_class($class, array('jss-wpcf7 bg-body text-secondary px-sm-40 px-16 py-sm-40 py-32 rounded-40 h-md-100', $animation));
?>
<div class='<?php _p($class); ?>'>
    <?php if($form){ ?>
        <?php _p(do_shortcode("[contact-form-7 id='{$form}']")); ?>
    <?php } ?>
</div>
