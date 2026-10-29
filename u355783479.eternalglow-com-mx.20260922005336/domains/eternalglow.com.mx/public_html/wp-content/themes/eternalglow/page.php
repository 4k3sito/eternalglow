<?php get_header(); ?>
	
    <?php the_post(); ?>

    <?php
        // Hub pages get prices and questions from includes/_landings.php. The ones with a hero have no content of their own.
        $landing = eg_landing(get_post()->post_name);
    ?>

    <?php if(!$landing || empty($landing['hero'])){ ?>
        <div <?php post_class('section'); ?>>
            <div class='entry'>
                <div class='container'>
                    <div class='entry-content'>

                        <?php
                            // Every page needs one h1; pages whose content has none (Nosotros, Contacto) get their title.
                            $content = apply_filters('the_content', get_the_content());
                            if(stripos($content, '<h1') === false){
                                echo '<h1 class="mb-40">'.esc_html(get_the_title()).'</h1>';
                            }
                            _p($content);
                        ?>

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php
        // Hub pages also show the Google reviews about their treatment: inside the landing, before the doctor, or
        // right after the content on hubs that are only WordPress content.
        $hub = eg_hub_for_slug(get_post()->post_name);
        if($landing){
            get_template_part('template-parts/landing', null, array('landing' => $landing, 'hub' => $hub));
        }elseif($hub){
            $t = eg_home_strings();
            get_template_part('template-parts/patient-reviews', null, array('items' => eg_patient_reviews($hub), 'id' => 'resenas', 'band' => true, 'eyebrow' => $t['reviews']['eyebrow'], 'title' => $t['reviews']['hub_title']));
        }
        // Sculptra's hub is WordPress content, not a landing, but it gets the same doctor block.
        if(is_page('sculptra-cdmx')){
            get_template_part('template-parts/doctor', null, array('byline' => true));
        }
    ?>
    
<?php get_footer(); ?>