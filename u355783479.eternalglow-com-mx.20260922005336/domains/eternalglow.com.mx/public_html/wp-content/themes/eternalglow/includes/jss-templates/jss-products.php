<?php
    $animation = get_css_animation($animation);
    $class = generate_class($class, array('jss-services', $responsive, $animation));
    $texts = json_decode(urldecode($texts));
?>
<div class='<?php _p($class); ?>'>
 
    <div class="row g-24">
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
                $n = $k + 1;
            ?>
            <div class='col-md-4'>
                <div>
                    <div class="ratio ratio-1x1 overflow-hidden rounded-24">
                        <?php if($url){ ?>
                            <a class='image-hover-scale'<?php _p($url.$url_target.$url_title.$url_rel); ?>>
                        <?php } ?>
                        <img src="<?php _p($image->url); ?>" alt="" class="object-fit-cover d-block h-100 w-100" />
                        <?php if($url){ ?>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="py-24 px-md-17 text-center text-md-left">
                        <div class='h3 text-primary'>
                            <?php _p($n < 10 ? 0: ''); ?><?php _p($n); ?>
                        </div>
                        <div class="d-grid gap-24">
                            <h4 class="font-base mb-0">
                                <?php _p($text->title); ?>
                            </h4>
                            <?php if($btn_text){ ?>
                                <div>
                                    <a class='btn btn-outline-primary'<?php _p($url.$url_target.$url_title.$url_rel); ?>>
                                        <?php _p($btn_text); ?>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
       
            </div>
        <?php } ?>
    </div>

</div>