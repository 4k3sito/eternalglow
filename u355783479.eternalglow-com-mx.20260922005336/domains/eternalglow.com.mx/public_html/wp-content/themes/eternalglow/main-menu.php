<?php 
    $logo = get_theme_logo(1); 
    $contact = get_theme_contact();
    $links = get_theme_links();
    $texts = get_theme_texts();
?>
<header>
    <div class="container py-lg-32 py-24">
        <nav class="bg-white navbar navbar-expand-lg p-16 px-lg-24 rounded-lg-24 rounded-16 shadow-sm">
            <a href="<?php base_url(); ?>">
                <img src='<?php _p($logo->url); ?>' alt='<?php _p($logo->alt); ?>' class='d-block height-60' />
            </a>
            <a href='#' class='d-lg-none' data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                <img src='<?php images_url('menu.svg'); ?>' alt='' class='height-48' />
            </a>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
                <div class="offcanvas-header p-24">
                    <div class="d-flex py-16">
                        <a href="<?php base_url(); ?>">
                            <img src='<?php _p($logo->url); ?>' alt='<?php _p($logo->alt); ?>' class='d-block height-60' />
                        </a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body p-24 p-lg-0 align-items-center">
                    <?php 
                        wp_nav_menu(
                            array(
                                'theme_location' => 'theme_main_menu', 
                                'menu_class' => 'nav-main-menu navbar-nav justify-content-center gap-lg-26 flex-grow-1',
                                'container' => false
                            )
                        );
                    ?>
                    <?php if($links->header_btn_text){ ?>
                        <div class="mt-16 mt-lg-0 d-flex flex-wrap gap-8">
                            <?php get_template_part('whatsapp-button'); ?>
                            <a class="btn btn-primary" href="<?php _p($links->header_btn_link); ?>"><?php _p($links->header_btn_text); ?></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </nav>
    </div>
</header>