<?php
    $animation = get_css_animation($animation);
    $text_align = vc_devices_class($text_align, 'text');
    $fs = vc_devices_class($fs, 'fs');
    $class = generate_class($class, array('jss-text nlm', 
        $fs, $responsive, $text_align, $font_family, $fw, $line_height, $letter_spacing, $animation, $text_shadow
    ));
    $id = $id ? "id='{$id}' ": '';
    $color = $color ? "color: {$color};": '';
    $style = $color ? " style=\"{$color}\"": '';

    $content = format_jss_text($content);
?>
<div <?php _p($id); ?>class='<?php _p($class); ?>'<?php _p($style); ?>>
    <?php _p($content); ?>
</div>