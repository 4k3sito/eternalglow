<?php
    $animation = get_css_animation($animation);
    $class = generate_class($class, array('jss-separator', $animation));
    $color = $color ? "background-color: {$color};": '';
    $height = $height ? "height: {$height};": '';
    $width = $width ? "width: {$width};": '';
    $style = ($color || $height || $width) ? " style=\"{$color}{$height}{$width}\"": '';
    $margin = '';
    $margin = $align == 'left' ? 'mr-auto': $margin;
    $margin = $align == 'center' ? 'm-auto': $margin;
    $margin = $align == 'right' ? 'ml-auto': $margin;
    $class_div = $margin ? " class='{$margin}'": '';
    $div = "<div{$class_div}{$style}></div>";
?>
<div class='<?php _p($class); ?>'>
    <?php _p($div); ?>
</div>