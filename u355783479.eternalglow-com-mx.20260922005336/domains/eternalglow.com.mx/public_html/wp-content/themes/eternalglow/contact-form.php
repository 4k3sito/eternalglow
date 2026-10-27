<?php
    $contact = eg_contact();
    $t = eg_home_strings()['footer'];
?>
<div class="text-body">
    <div class='row g-md-40 g-24'>
        <div class='col-md-6'>
            <div class="p-md-40 p-16 bg-primary-gradient rounded-24 h-100">
                <div class="ratio ratio-1x1 rounded-24 overflow-hidden mb-md-40 mb-32 minh-460 minh-md-630">
                    <?php _p(strpos($contact->map, 'title=') === false ? str_replace('<iframe ', '<iframe title="'.esc_attr($t['map']).'" ', $contact->map) : $contact->map); ?>
                </div>
                <div class="text-center text-md-left">
                    <?php get_template_part('contact-data'); ?>
                    <?php get_template_part('whatsapp-button', null, array('text' => $t['whatsapp'], 'class' => 'btn-primary mt-32')); ?>
                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="p-md-40 p-16 bg-white rounded-24 shadow-sm h-100">
                <?php if($contact->form){ ?>
                    <?php
                        $form = do_shortcode("[contact-form-7 id='{$contact->form}']");
                        $form = format_jss_text($form);
                        // The form's title is an h3 in the CF7 markup, but the block follows the page's h1 or last h2.
                        // .h3 keeps the old size.
                        $form = preg_replace("#<h3 class='([^']*)'>(.*?)</h3>#s", "<h2 class='h3 $1'>$2</h2>", $form, 1);
                    ?>
                    <?php _p($form); ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
