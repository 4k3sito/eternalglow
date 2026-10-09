<?php
    $url = get_whatsapp_url();
    $text = $args['text'] ?? 'WhatsApp';
    $class = $args['class'] ?? 'btn-outline-primary';
?>
<?php if($url){ ?>
    <a href="<?php _p(esc_url($url)); ?>" class="btn d-inline-flex align-items-center justify-content-center gap-8 <?php _p(esc_attr($class)); ?>" target="_blank" rel="noopener" data-cta="<?php _p(esc_attr($args['cta'] ?? 'whatsapp')); ?>">
        <?php _p(eg_icon('whatsapp-logo')); ?><?php _p(esc_html($text)); ?>
    </a>
<?php } ?>
