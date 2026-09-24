<?php
    $animation = get_css_animation($animation);
    $text_align = vc_devices_class($text_align, 'text');
    $fs = vc_devices_class($fs, 'fs');

    $_class1[] = 'jss-button';
	$_class1[] = $text_align;
    $_class1[] = $animation;
    $_class1[] = $responsive;

    $btn_type = $type ? 'btn-'.$type: '';
    $_class2[] = 'btn';
    $_class2[] = $btn_type;
    $_class2[] = $size ? 'btn-'.$size: '';
    $_class2[] = $block ? $block: '';
    $_class2[] = $round ? $round: '';
        
    $padding = vc_dimension($padding, 'padding');
    $border_radius = vc_dimension($border_radius, 'border-radius');

    $url = vc_build_link($url);
    $target = $url['target'];
    $rel = $url['rel'];
    $title = $url['title'];
    $url = $url['url'];
    
    if(strpos($url, '#') !== false && strpos($url, 'Modal') === false){
        $_class2[] = 'scroll-to';
    }
    
    $_class2[] = $fs;
    $_class2[] = $font_family;
    $_class2[] = $fw;

    $width = vc_devices($width);

    $tag_open = $url ? 'a': "button type='button'";
    $tag_close = $url ? 'a': "button";
    $url = $url ? " href='{$url}'": '';
    $bg_color = $bg_color ? "background-color: {$bg_color} !important;": '';
    $color = $color ? "color: {$color} !important;": '';
    $style = ($bg_color || $color || $padding || $border_radius) ? " style=\"{$bg_color}{$color}{$padding}{$border_radius}\"": '';
    $target = $target ? " target='{$target}'": '';
    $title = $title ? " title='{$title}'": '';
    $rel = $rel ? " rel='{$rel}'": '';

    $class = generate_class($class, $_class1);
	$class_tag = generate_class($padding, $_class2);

    $btn_id = 'btn-'.random_string();
    
    $button = "
        <{$tag_open} id='{$btn_id}' class='{$class_tag}'{$style}{$url}{$target}{$title}{$rel}>
            {$text}
        </{$tag_close}>
	";
?>
<div class='<?php _p($class); ?>'>
    <?php _p($button); ?>
</div>
<?php if($width->has_values){ ?>
    <style>
        <?php if($width->desktop){ ?>
            #<?php _p($btn_id); ?>{
                width: <?php _p(rem($width->desktop)); ?> !important;
            }
        <?php } ?>
        <?php if($width->tablet){ ?>
            @media (max-width: 767.98px) {
                #<?php _p($btn_id); ?>{
                    width: <?php _p(rem($width->tablet)); ?> !important;
                }
            }
        <?php } ?>
        <?php if($width->mobile){ ?>
            @media (max-width: 575.98px) {
                #<?php _p($btn_id); ?>{
                    width: <?php _p(rem($width->mobile)); ?> !important;
                }
            }
        <?php } ?>
    </style>
<?php } ?>