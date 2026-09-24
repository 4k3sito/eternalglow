<?php
    $animation = get_css_animation($animation);
    $class = generate_class($class, array('jss-images-grid', $responsive, $animation));
    $images = explode(',', $images);
    $images = array_map(function($v){
        return get_theme_image($v);
    }, $images);
    $images = array_chunk($images, 6);
    $fancybox = 'images-'.random_string();
?>
<div class='<?php _p($class); ?>'>
    <div class="d-grid gap-md-20 g-7">
        <?php foreach($images as $_images){ ?>
            <?php
                $_images = array_chunk($_images, 2);
            ?>
            <div class="row g-md-20 g-7">
                <?php foreach($_images as $k => $items){ ?>
                    <div class="col-4">
                        <div class="d-grid gap-md-20 gap-7">
                            <?php foreach($items as $j => $image){ ?>
                                <?php
                                    $class = ['ratio rounded-md-20 rounded-7 overflow-hidden image-hover-scale'];
                                    $class[] = !$j ? 'height-md-600 height-195': 'height-md-365 height-115';
                                    if($k == 1 && !$j){
                                        $class[] = 'order-last';
                                    }
                                    $class = implode(' ', $class);
                                ?>
                                <a class="<?php _p($class); ?>" data-fancybox='<?php _p($fancybox); ?>' href='<?php _p($image->url); ?>'>
                                    <img src="<?php _p($image->url); ?>" alt="" class="object-fit-cover" />
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>