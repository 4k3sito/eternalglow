<?php
    $class = generate_class($class, array('jss-empty-space', $responsive));

    $height = vc_devices($height);
    
    $id = 'empty-space-'.random_string();
?>
<div id='<?php _p($id); ?>' class='<?php _p($class); ?>'></div>
<?php if($height->has_values){ ?>
    <style>
        <?php if($height->desktop){ ?>
            #<?php _p($id); ?>{
                height: <?php _p(rem($height->desktop)); ?> !important;
            }
        <?php } ?>
        <?php if($height->tablet){ ?>
            @media (max-width: 767.98px) {
                #<?php _p($id); ?>{
                    height: <?php _p(rem($height->tablet)); ?> !important;
                }
            }
        <?php } ?>
        <?php if($height->mobile){ ?>
            @media (max-width: 575.98px) {
                #<?php _p($id); ?>{
                    height: <?php _p(rem($height->mobile)); ?> !important;
                }
            }
        <?php } ?>
    </style>
<?php } ?>
