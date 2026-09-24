<?php
    $contact = get_theme_contact();
?>
<div class="text-body">
    <div class='row g-md-40 g-24'>
        <div class='col-md-6'>
            <div class="p-md-40 p-16 bg-primary-gradient rounded-24 h-100">
                <div class="ratio ratio-1x1 rounded-24 overflow-hidden mb-md-40 mb-32 minh-460 minh-md-630">
                    <?php _p($contact->map); ?>
                </div>
                <div class="text-center text-md-left">
                    <?php get_template_part('contact-data'); ?>
                    <?php get_template_part('whatsapp-button', null, array('text' => 'Escríbenos por WhatsApp', 'class' => 'btn-primary mt-32')); ?>
                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="p-md-40 p-16 bg-white rounded-24 shadow-sm h-100">
                <?php if($contact->form){ ?>
                    <?php
                        $form = do_shortcode("[contact-form-7 id='{$contact->form}']");
                        $form = format_jss_text($form);
                    ?>
                    <?php _p($form); ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
