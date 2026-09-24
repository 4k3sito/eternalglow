<?php
    $animation = get_css_animation($animation);
    $text_align = vc_devices_class($text_align, 'text');

    $_class1[] = 'jss-button-arrow';
	$_class1[] = $text_align;
    $_class1[] = $animation;
    $_class1[] = $responsive;

    $svg = get_images_url('arrow-right-up.svg');
        
    $url = vc_build_link($url);
    $target = $url['target'];
    $rel = $url['rel'];
    $title = $url['title'];
    $url = $url['url'];
    
    $tag_open = $url ? 'a': "button type='button'";
    $tag_close = $url ? 'a': "button";

    $url = $url ? " href='{$url}'": '';
    $target = $target ? " target='{$target}'": '';
    $title = $title ? " title='{$title}'": '';
    $rel = $rel ? " rel='{$rel}'": '';

    $class = generate_class($class, $_class1);

    $btn_id = 'btn-'.random_string();
    
    $button = "
        <{$tag_open} id='{$btn_id}' class='btn-arrow btn-arrow-primary'{$url}{$target}{$title}{$rel}>
            <span class='btn-arrow-text'>
                <span>{$text}</span>
            </span>
            <span class='btn-arrow-icon'>
                <img src='{$svg}' alt='' />
            </span>
        </{$tag_close}>
	";
?>
<div class='<?php _p($class); ?>'>
    <?php _p($button); ?>
</div>