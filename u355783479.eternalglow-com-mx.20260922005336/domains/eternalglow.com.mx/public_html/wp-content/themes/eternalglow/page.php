<?php get_header(); ?>
	
    <?php the_post(); ?>

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
    
<?php get_footer(); ?>