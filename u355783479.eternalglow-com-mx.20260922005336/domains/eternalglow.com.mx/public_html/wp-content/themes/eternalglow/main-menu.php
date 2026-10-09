<?php 
    $logo = get_theme_logo(1); 
    $links = get_theme_links();
    $en = eg_lang() == 'en';
    $t = $en ? eg_home_strings() : array();
?>
<header>
    <div class="container py-16 py-lg-24">
        <nav class="navbar navbar-expand-lg p-0" aria-label="<?php _p($en ? 'Main' : 'Principal'); ?>">
            <a href="<?php _p(esc_url(home_url($en ? '/en/' : '/'))); ?>" aria-label="<?php _p(esc_attr(get_bloginfo('name'))); ?>">
                <img src='<?php _p($logo->url); ?>' alt='<?php _p($logo->alt); ?>' width="141" height="48" class='d-block height-48' data-no-lazy="1" />
            </a>
            <?php // One button opens and closes the menu; its two lines turn into a cross (see .lp-menu-open in _home.scss). ?>
            <button type="button" class="lp-burger d-lg-none" aria-label="<?php _p($en ? 'Menu' : 'Menú'); ?>" aria-controls="offcanvasNavbar" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                <span></span><span></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
                <div class="offcanvas-body align-items-center">
                    <?php if($en){ ?>
                        <ul class="nav-main-menu navbar-nav justify-content-center gap-lg-26 flex-grow-1">
                            <?php foreach($t['nav'] as $href => $label){ ?>
                                <li class="nav-item"><a class="nav-link nav-main-menu-link" href="<?php _p($href); ?>"><?php _p(esc_html($label)); ?></a></li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <?php 
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'theme_main_menu', 
                                    'menu_class' => 'nav-main-menu navbar-nav justify-content-center gap-lg-26 flex-grow-1',
                                    'container' => false
                                )
                            );
                        ?>
                    <?php } ?>
                    <div class="mt-24 mt-lg-0 d-flex flex-wrap align-items-center gap-16">
                        <?php if($links->header_btn_link){ ?>
                            <a class="btn btn-primary" href="<?php _p(esc_url($links->header_btn_link)); ?>" target="_blank" rel="noopener" data-cta="header"><?php _p(esc_html($en ? $t['cta'] : 'Agenda tu valoración')); ?></a>
                        <?php } ?>
                        <div class="lp-lang">
                            <a href="<?php _p(esc_url(home_url('/'))); ?>" hreflang="es-MX" lang="es" <?php _p($en ? '' : 'aria-current="true"'); ?>>ES</a>
                            <a href="<?php _p(esc_url(home_url('/en/'))); ?>" hreflang="en" lang="en" <?php _p($en ? 'aria-current="true"' : ''); ?>>EN</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
