<?php
    $animation = get_css_animation($animation);
    $text_align = vc_devices_class($text_align, 'text');
    $class = generate_class($class, array('jss-heading', $text_align, $responsive, $animation));

    $fs = vc_devices_class($fs, 'fs');

    $tag = $tag ? $tag: 'h1';
    $url = vc_build_link($url);
    $url_target = $url['target'];
    $url_rel = $url['rel'];
    $url_title = $url['title'];
    $url = $url['url'];

    $url = $url ? " href='{$url}'": '';
    $url_target = $url_target ? " target='{$url_target}'": '';
    $url_title = $url_title ? " title='{$url_title}'": '';
    $url_rel = $url_rel ? " rel='{$url_rel}'": '';

    $tag_class = generate_class(array($tag_class, $fs, $font_family, $fw, $line_height, $letter_spacing, $font_style, $text_shadow));

    $text = format_jss_text($text);

    if($url){
        $text = "<a {$url}{$url_target}{$url_title}{$url_rel}>{$text}</a>";
    }

    $color = $color ? "color: {$color};": '';
    $style = $color ? " style=\"{$color}\"": '';
    $tag_class = $tag_class ? " class='{$tag_class}'": '';
    $text = "<{$tag}{$style}{$tag_class}>{$text}</{$tag}>";
    $id = $id ? "id='{$id}' ": '';
?>
<div <?php _p($id); ?>class='<?php _p($class); ?>'>
    <?php _p($text); ?>
</div>