<?php 
    $t = eg_home_strings();
    $en = eg_lang() == 'en';
    $path = trailingslashit(strtok($_SERVER['REQUEST_URI'] ?? '/', '?'));
?>
<header>
    <div class="lp-wrap">
        <nav class="navbar navbar-expand-lg p-0" aria-label="<?php _p($en ? 'Main' : 'Principal'); ?>">
            <a class="lp-wordmark" href="<?php _p(esc_url(home_url($en ? '/en/' : '/'))); ?>">Eternal <em>Glow</em></a>
            <?php // One button opens and closes the menu; its two lines turn into a cross (see .lp-menu-open in _home.scss). ?>
            <button type="button" class="lp-burger d-lg-none" aria-label="<?php _p($en ? 'Menu' : 'Menú'); ?>" aria-controls="offcanvasNavbar" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                <span></span><span></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
                <div class="offcanvas-body align-items-center">
                    <ul class="nav-main-menu navbar-nav justify-content-end flex-grow-1">
                        <?php foreach($t['nav'] as $href => $label){ ?>
                            <?php $url = $href[0] == '#' ? $href : home_url($href); ?>
                            <li class="nav-item"><a class="nav-link" href="<?php _p(esc_url($url)); ?>"<?php _p($href == $path ? ' aria-current="page"' : ''); ?>><?php _p(esc_html($label)); ?></a></li>
                        <?php } ?>
                    </ul>
                    <div class="lp-header-actions">
                        <?php _p(eg_cta_book('header', $t['cta_short'])); ?>
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
