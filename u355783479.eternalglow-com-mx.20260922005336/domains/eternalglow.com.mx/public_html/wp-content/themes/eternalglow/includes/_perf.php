<?php
// Front-end performance tweaks that touch third-party scripts.

// reCAPTCHA v3 (Contact Form 7) cost ~700 KB of JS and ~1.6 s of main thread on every page, but only the form
// needs it. CF7's module (contact-form-7/modules/recaptcha/index.js) loads Google at page load; this replaces
// it with the same logic, except Google's api.js loads when someone focuses a form field. As in CF7, a token
// is requested right before each submit and written to the form's _wpcf7_recaptcha_response field.
// The handle stays enqueued without a file, so CF7's inline wpcf7_recaptcha config (sitekey, actions) still prints.
function eg_lazy_recaptcha(){
	$scripts = wp_scripts();
	if(!wp_script_is('wpcf7-recaptcha', 'enqueued') || !isset($scripts->registered['wpcf7-recaptcha'])){
		return;
	}
	$module = $scripts->registered['wpcf7-recaptcha'];
	$module->src = false;
	$module->deps = array_diff($module->deps, array('google-recaptcha'));
	wp_dequeue_script('google-recaptcha');
	wp_add_inline_script('wpcf7-recaptcha', <<<'JS'
document.addEventListener('DOMContentLoaded', function () {
	var cfg = window.wpcf7_recaptcha || {}, key = cfg.sitekey, action = (cfg.actions || {}).contactform || 'contactform', loading = null;
	if (!key) {
		return;
	}
	function load() {
		return loading || (loading = new Promise(function (resolve, reject) {
			var s = document.createElement('script');
			s.src = 'https://www.google.com/recaptcha/api.js?render=' + encodeURIComponent(key);
			s.async = true;
			s.onload = function () { window.grecaptcha.ready(resolve); };
			s.onerror = reject;
			document.head.appendChild(s);
		}));
	}
	function token() {
		return load().then(function () {
			return window.grecaptcha.execute(key, { action: action });
		}).then(function (t) {
			document.querySelectorAll('form.wpcf7-form input[name="_wpcf7_recaptcha_response"]').forEach(function (input) {
				input.value = t;
			});
		});
	}
	// Start loading while the visitor fills the form, so the submit does not wait for Google.
	document.addEventListener('focusin', function (e) {
		if (e.target.closest && e.target.closest('form.wpcf7-form')) {
			load();
		}
	});
	if (window.wpcf7 && typeof window.wpcf7.submit === 'function') {
		var submit = window.wpcf7.submit;
		// If Google cannot be reached the form is still sent; CF7 then judges it without a token, as before.
		window.wpcf7.submit = function (form, options) {
			var send = function () { submit(form, options || {}); };
			token().then(send, send);
		};
	}
});
JS
	);
}

// CF7 enqueues its reCAPTCHA scripts at priority 20.
add_action('wp_enqueue_scripts', 'eg_lazy_recaptcha', 21);

// CLS: `.lp-cutout { aspect-ratio: auto }` (_editorial.scss) cancels the ratio browsers derive from width/height,
// so a cut-out took no room until it loaded and pushed the text down (0.16 on /botox-cdmx/ mobile). It also kept
// the original upload's width/height, which differ from some cut-outs. Size and ratio now come from the file shown.
// ponytail: inline style because the SCSS fix needs a dist rebuild; drop it once _editorial.scss stops setting `auto`.
function eg_cutout_ratio($attr){
	if(strpos(isset($attr['class']) ? $attr['class'] : '', 'lp-cutout') === false){
		return $attr;
	}
	$file = get_template_directory().'/assets/images/cutouts/'.basename((string)parse_url($attr['src'], PHP_URL_PATH));
	$size = is_file($file) ? getimagesize($file) : false;
	if($size){
		$attr['width'] = $size[0];
		$attr['height'] = $size[1];
		$attr['style'] = trim((isset($attr['style']) ? $attr['style'] : '').' aspect-ratio: '.$size[0].' / '.$size[1].';');
	}
	return $attr;
}

// After eg_cutout_attrs() (priority 10), which swaps in the cut-out file.
add_filter('wp_get_attachment_image_attributes', 'eg_cutout_ratio', 11);

// CLS: Instrument Serif is condensed, so headings drawn in the fallback (Georgia, or Noto Serif on Android) took
// one more line and everything below jumped when the web font arrived (0.16 on /botox-cdmx/ mobile).
// Fallback faces scaled to Instrument Serif's metrics keep the same line breaks. Values from @capsizecss/core
// createFontStack(); Liberation Serif shares Times New Roman's metrics.
// ponytail: inline because the SCSS (--lp-serif in _editorial.scss) needs a dist rebuild; move it there next build.
function eg_serif_fallback(){
	?>
<style id="eg-serif-fallback">
@font-face{font-family:"Instrument Serif Fallback: Georgia";src:local('Georgia');ascent-override:129.426%;descent-override:40.5273%;size-adjust:76.4916%}
@font-face{font-family:"Instrument Serif Fallback: Noto Serif";src:local('Noto Serif Regular'),local('NotoSerif-Regular');ascent-override:139.6452%;descent-override:43.7273%;size-adjust:70.894%}
@font-face{font-family:"Instrument Serif Fallback: Times New Roman";src:local('Times New Roman'),local('TimesNewRomanPSMT'),local('Liberation Serif');ascent-override:117.9435%;descent-override:36.9318%;line-gap-override:0%;size-adjust:83.9385%}
body.lp-editorial{--lp-serif:"Instrument Serif","Instrument Serif Fallback: Georgia","Instrument Serif Fallback: Noto Serif","Instrument Serif Fallback: Times New Roman",Georgia,serif}
</style>
	<?php
}

add_action('wp_head', 'eg_serif_fallback', 99);
