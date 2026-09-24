<?php
    $animation = get_css_animation($animation);
    $class = generate_class($class, array('jss-contact-data', $animation));
?>
<div class='<?php _p($class); ?>'>
    <?php get_template_part('contact-data'); ?>
</div>
