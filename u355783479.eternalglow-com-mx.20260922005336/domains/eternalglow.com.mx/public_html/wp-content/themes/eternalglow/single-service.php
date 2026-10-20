<?php get_header(); ?>
	
    <?php the_post(); ?>

    <?php
        $thumb = get_thumbnail();
        $terms = get_the_terms(get_the_ID(), 'tax_category');
        $related = [];
        if($terms){
            $related = get_related_services(get_the_ID(), $terms[0]->term_id);
        }
        $hub = eg_service_hub(get_post());
        $priced = eg_show_prices() ? eg_treatment_for(get_post()->post_name) : null;
    ?>

    <div <?php post_class('section'); ?>>
        <div class='entry'>
            <div class='container'>
                <div class='entry-content'>

                    <?php // Every service links up to the page that owns its main keyword (see eg_service_hub()). ?>
                    <nav class="lp-breadcrumb" aria-label="Ruta de navegación">
                        <a href="<?php _p(esc_url(home_url('/'))); ?>">Inicio</a>
                        <a href="<?php _p(esc_url($hub->url)); ?>"><?php _p(esc_html($hub->title)); ?></a>
                        <span aria-current="page"><?php the_title(); ?></span>
                    </nav>

                    <?php // Official price from the clinic's list (includes/_treatments.php). ?>
                    <?php if($priced){ ?>
                        <p class="lp-service-price">
                            <span class="lp-eyebrow">Precio</span>
                            <strong><?php _p(esc_html($priced->price)); ?> MXN</strong>
                            <?php if($priced->slug != get_post()->post_name){ ?>
                                <span>Se cobra como <a href="<?php _p(esc_url($priced->url)); ?>"><?php _p(esc_html(lcfirst($priced->name))); ?></a>; las unidades se definen en tu valoración.</span>
                            <?php }else{ ?>
                                <span>El plan y la cantidad se definen en tu valoración médica.</span>
                            <?php } ?>
                        </p>
                    <?php } ?>

                    <div class='row g-md-40 g-48'>
                        <div class='col-md'>

                            <div class='nlm'>
                                <?php the_content(); ?>
                            </div>

                            <aside class="lp-service-cta">
                                <p>
                                    Atención médica: <a href="<?php _p(esc_url(home_url('/nosotros/'))); ?>">Dr. Bruno Ocampo</a>, Eternal Glow, Roma Norte, CDMX.
                                    Actualizado el <time datetime="<?php _p(get_the_modified_date('Y-m-d')); ?>"><?php _p(get_the_modified_date('j \\d\\e F \\d\\e Y')); ?></time>.
                                </p>
                                <div class="lp-actions">
                                    <a class="btn btn-primary lp-btn-arrow" href="<?php _p(esc_url(eg_booking_url())); ?>" target="_blank" rel="noopener" data-cta="servicio">Agenda tu valoración</a>
                                    <?php get_template_part('whatsapp-button', null, array('text' => 'Hablar por WhatsApp')); ?>
                                </div>
                            </aside>
                            
                        </div>
                        <?php if($terms && $related){ ?>
                            <div class='col-md-auto'>
                                <div class="border p-16 rounded-8 width-md-305 maxw-305">
                                    <div class="bg-primary ml--16 mb-24 rounded-end-8 px-16 py-8 text-white">
                                        <?php _p($terms[0]->name); ?>
                                    </div>
                                    <div class="overflow-auto maxh-270">
                                        <div class="d-grid gap-12 overflow-hidden">
                                            <?php foreach($related as $_post){ ?>

                                                <div>

                                                    <div class="row g-10 align-items-center">
                                                        <div class="col">
                                                            <a href='<?php the_permalink($_post); ?>'>
                                                                <?php _p($_post->post_title); ?>
                                                            </a>
                                                        </div>
                                                        <div class="col-auto">
                                                            <a href='<?php the_permalink($_post); ?>'>
                                                                <img src="<?php images_url('arrow-right.svg'); ?>" data-no-lazy="1" alt="" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                    
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
<?php get_footer(); ?>