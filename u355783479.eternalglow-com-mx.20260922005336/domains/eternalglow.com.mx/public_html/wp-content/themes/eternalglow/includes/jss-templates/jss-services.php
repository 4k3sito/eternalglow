<?php
    $animation = get_css_animation($animation);
    $class = generate_class($class, array('jss-services', $responsive, $animation));
    $texts = json_decode(urldecode($texts));
?>
<div class='<?php _p($class); ?>'>
 
    <div class="row g-md-40 g-24">
        <?php foreach($texts as $k => $text){ ?>
            <?php
                $image = get_theme_image(isset($text->image) ? $text->image: 0);
                $btn_text = isset($text->btn_text) ? $text->btn_text: '';
                $url = vc_build_link(isset($text->url) ? $text->url: '');

                $url_target = $url['target'];
                $url_rel = $url['rel'];
                $url_title = $url['title'];
                $url = $url['url'];

                $url_target = $url_target ? " target='{$url_target}'": '';
                $url_title = $url_title ? " title='{$url_title}'": '';
                $url_rel = $url_rel ? " rel='{$url_rel}'": '';
                $url = $url ? " href='{$url}'": '';

                $even = $k % 2 == 0;
            ?>
            <div class='col-md-4'>
                <div class="px-24 py-48 d-grid gap-24 shadow-sm rounded-24 h-100 <?php _p($even ? 'bg-white': 'bg-primary-gradient'); ?>">
                    <div class="d-grid gap-8">
                        <div>
                            <img src="<?php images_url('star.svg'); ?>" alt="" />
                        </div>
                        <div>
                            <img src="<?php _p($image->url); ?>" alt="" class="height-80" />
                        </div>
                        <h3 class="font-base mb-0">
                            <?php _p($text->title); ?>
                        </h3>
                    </div>
                    <div>
                        <?php _p(nl2br($text->text)); ?>
                    </div>
                    <?php if($btn_text){ ?>
                        <div>
                            <a class='btn <?php _p($even ? 'btn-outline-primary': 'btn-primary'); ?>'<?php _p($url.$url_target.$url_title.$url_rel); ?>>
                                <?php _p($btn_text); ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
       
            </div>
        <?php } ?>
    </div>

</div>