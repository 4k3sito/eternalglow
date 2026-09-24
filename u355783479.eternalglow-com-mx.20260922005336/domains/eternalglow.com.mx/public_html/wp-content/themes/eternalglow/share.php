<?php

	$title = get_the_title();
	$url = get_permalink();
	$image = get_thumbnail()->url;

	$title = rawurlencode($title);
	$url = rawurlencode($url);
	$image = rawurlencode($image);

	$email = "mailto:?subject={$title}&body={$url}";
	$facebook = "https://www.facebook.com/sharer/sharer.php?u={$url}";
	$twitter = "https://twitter.com/share?url={$url}&text={$title}";
	$whatsapp = "https://wa.me/?text={$url} {$title}";

    $share = [
        'fa-regular fa-envelope' => $email,
        'fa-brands fa-facebook-f' => $facebook,
        'fa-brands fa-x-twitter' => $twitter,
        'fa-brands fa-whatsapp' => $whatsapp,
    ];
?>
<div class='share overflow-hidden'>
    <div class='row g-16 align-items-center'>
        <div class='col-auto fw-semibold'>
            Compartir:
        </div>
        <?php foreach($share as $k => $v){ ?>
            <div class='col-auto'>
                <a href='<?php _p($v); ?>' class='btn btn-body ratio ratio-1x1 width-32 rounded-circle p-0 minh-0' target='_blank'>
                    <span class='d-flex align-items-center justify-content-center'>
                        <i class="<?php _p($k); ?> fs-14"></i>
                    </span>
                </a>
            </div>
        <?php } ?>
    </div>
</div>