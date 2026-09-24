<?php
    $animation = get_css_animation($animation);
    $class = generate_class($class, array('jss-procedure', $responsive, $animation));
    $texts = json_decode(urldecode($texts));
?>
<div class='<?php _p($class); ?>'>
 
    <div class="row g-md-56">
        <?php foreach($texts as $k => $text){ ?>
            <?php
                $image = get_theme_image(isset($text->image) ? $text->image: 0);
            ?>
            <div class='col-md-4'>
                <div class="bg-white px-24 py-48 d-grid gap-24 shadow-sm rounded-24 h-100">           
                    <div class="row g-16 align-items-center">
                        <div class="col-auto">
                            <img src="<?php _p($image->url); ?>" alt="" class="height-60 p-10 border border-primary rounded-circle" />
                        </div>
                        <div class="col">
                            <h4 class="mb-0">
                                <?php _p($text->title); ?>
                            </h4>
                        </div>
                    </div>
                    <div>
                        <?php _p(nl2br($text->text)); ?>
                    </div>
                </div>
       
            </div>
        <?php } ?>
    </div>

</div>