<?php
    $animation = get_css_animation($animation);
    $text_align = vc_devices_class($text_align, 'text');

    $_class1[] = 'jss-button-arrow';
	$_class1[] = $text_align;
    $_class1[] = $animation;
    $_class1[] = $responsive;

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
    
    $button = "<{$tag_open} id='{$btn_id}' class='btn btn-primary'{$url}{$target}{$title}{$rel}>{$text}</{$tag_close}>";
?>
<div class='<?php _p($class); ?>'>
    <?php if($whatsapp){ ?>
        <div class="d-inline-flex flex-column flex-md-row align-items-center gap-16">
            <?php _p($button); ?>
            <?php get_template_part('whatsapp-button'); ?>
        </div>
    <?php } else { ?>
        <?php _p($button); ?>
    <?php } ?>
</div>