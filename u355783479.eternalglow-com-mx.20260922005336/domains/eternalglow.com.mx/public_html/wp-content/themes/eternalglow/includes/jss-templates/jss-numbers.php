<?php
    $animation = get_css_animation($animation);
    $class = generate_class($class, array('jss-numbers', $responsive, $animation));
    $texts = json_decode(urldecode($texts));
?>
<div class='<?php _p($class); ?>'>
 
        <div class='row g-md-24 g-0 justify-content-center justify-content-md-start text-center'>
            <div class="col-md-auto pt-24">
                <img src="<?php images_url('quote.svg'); ?>" alt="" />
            </div>
            <div class="col-md">
                <div class="row g-md-48 g-16">
                    <?php foreach($texts as $text){ ?>
                        <div class='col-md-auto'>
                            <h4 class="fs-56 mb-20">
                                <?php _p($text->value); ?>
                            </h4>
                            <div class="maxw-md-150">
                                <?php _p(nl2br($text->text)); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        
</div>