<?php
    $animation = get_css_animation($animation);
    $text_align = vc_devices_class($text_align, 'text');
    $class = generate_class($class, array('jss-bg-image', $full_height, $text_align, $responsive, $animation));
   
    $width = vc_devices($width);

    $image = get_theme_image($image);

    $url = vc_build_link($url);
    $target = $url['target'];
    $rel = $url['rel'];
    $title = $url['title'];
    $url = $url['url'];

    $url = $url ? " href='{$url}'": '';
    $target = $target ? " target='{$target}'": '';
    $title = $title ? " title='{$title}'": '';
    $rel = $rel ? " rel='{$rel}'": '';

    $class_tag = generate_class('d-inline-block bg-cover bg-center ratio ratio-16x9 mw-100', $full_height);

    $a1 = $a2 = '';
    if($url){
        $a1 = "<a {$url}{$target}{$title}{$rel}>";
        $a2 = "</a>";
    }
    $img_id = 'image-'.random_string();
?>
<div class='<?php _p($class); ?>'>
    
    <div id='<?php _p($img_id); ?>' class='<?php _p($class_tag); ?>'>
        <?php _p($a1); ?>
            <img src='<?php _p($image->url); ?>' alt='' class='w-100 h-100 object-fit-cover' />
        <?php _p($a2); ?>
    </div>

</div>
<?php if($width->has_values){ ?>
    <style>
        <?php if($width->desktop){ ?>
            #<?php _p($img_id); ?>{
                width: <?php _p(rem($width->desktop)); ?> !important;
            }
        <?php } ?>
        <?php if($width->tablet){ ?>
            @media (max-width: 767.98px) {
                #<?php _p($img_id); ?>{
                    width: <?php _p(rem($width->tablet)); ?> !important;
                }
            }
        <?php } ?>
        <?php if($width->mobile){ ?>
            @media (max-width: 575.98px) {
                #<?php _p($img_id); ?>{
                    width: <?php _p(rem($width->mobile)); ?> !important;
                }
            }
        <?php } ?>
    </style>
<?php } ?>