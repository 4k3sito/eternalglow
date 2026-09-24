<?php
    $animation = get_css_animation($animation);
    $class = generate_class($class, array('jss-carousel', $animation));
    $slides = json_decode(urldecode($slides));
    foreach($slides as $slide){
        $slide->image = get_theme_image($slide->image);
    }
    $uniqid = random_string();
    $id = "carousel-{$uniqid}";
    $interval = !$autoplay ? 5000: $interval * 1000;
    $ratio = $ratio ? $ratio: '16x9';
?>
<div class='<?php _p($class); ?>'>
    <?php if($slides){ ?>
        <div id="<?php _p($id); ?>" class="carousel slide<?php _p($is_fade ? ' carousel-fade': ''); ?>" data-bs-ride="<?php _p(!$autoplay ? 'false': 'carousel'); ?>" data-bs-interval="<?php _p($interval); ?>">
            <div class="carousel-inner">
                <?php foreach($slides as $k => $slide){ ?>
                    <?php 
                        $url = vc_build_link(isset($slide->url) ? $slide->url: '');
                        $target = $url['target'];
                        $rel = $url['rel'];
                        $title = $url['title'];
                        $url = $url['url'];
                        $target = $target ? " target='{$target}'": '';
                        $title = $title ? " title='{$title}'": '';
                        $rel = $rel ? " rel='{$rel}'": '';
                    ?>
                    <div class="carousel-item<?php if(!$k){ ?> active<?php } ?>">
                        <div class='ratio ratio-<?php _p($ratio); ?>'>
                            <?php if($url){ ?>
                                <a href="<?php _p($url); ?>"<?php _p($target.$title.$rel); ?>>
                            <?php } ?>
                            <img src='<?php _p($slide->image->url); ?>' alt='<?php _p($slide->image->alt); ?>' class='w-100 h-100 object-fit-cover' />
                            <?php if($url){ ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php if(!$hide_indicators){ ?>
                <ol class="carousel-indicators">
                    <?php foreach($slides as $k => $slide){ ?>
                        <li data-bs-target="#<?php _p($id); ?>" data-bs-slide-to="<?php _p($k); ?>" <?php if(!$k){ ?>class='active'<?php } ?>></li>
                    <?php } ?>
                </ol>
            <?php } ?>
            <?php if(!$hide_controls){ ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#<?php _p($id); ?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#<?php _p($id); ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            <?php } ?>
        </div>
    <?php } ?>
</div>