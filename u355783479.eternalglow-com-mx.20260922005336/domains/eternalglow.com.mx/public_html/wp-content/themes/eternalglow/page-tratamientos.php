<?php
	// Catalogue of every treatment, grouped by category, each row linking to its own page.
	// WordPress picks this template from the page slug; the data lives in includes/_treatments.php.
	get_header();
	the_post();
	$treatments = eg_treatments();
	$cats = eg_treatment_categories();
	$counts = array_count_values(wp_list_pluck($treatments, 'cat'));
?>

<section class="lp-section lp-catalog" aria-labelledby="catalog-title">
	<div class="lp-wrap">
		<div class="lp-catalog-head">
			<p class="lp-eyebrow"><?php _p(count($treatments)); ?> tratamientos + hilos tensores</p>
			<h1 id="catalog-title">Tratamientos</h1>
			<p>Medicina estética en Roma Norte, CDMX. Todos empiezan con una valoración médica; elige uno para conocer el procedimiento, los cuidados y los resultados.</p>
		</div>

		<label class="lp-search">
			<?php _p(eg_icon('magnifying-glass')); ?>
			<span class="visually-hidden">Buscar tratamiento</span>
			<input type="search" id="lp-search" placeholder="Busca: ojeras, bruxismo, labios…" autocomplete="off" />
		</label>

		<div class="lp-pills" role="group" aria-label="Categorías">
			<button type="button" data-filter="all" aria-pressed="true">Todos <span><?php _p(count($treatments)); ?></span></button>
			<?php foreach($cats as $key => $cat){ ?>
				<button type="button" data-filter="<?php _p($key); ?>" aria-pressed="false"><?php _p(esc_html($cat[0])); ?> <span><?php _p($counts[$key]); ?></span></button>
			<?php } ?>
		</div>

		<?php foreach($cats as $key => $cat){ ?>
			<div class="lp-group" id="<?php _p($key); ?>" data-category="<?php _p($key); ?>">
				<div class="lp-group-head">
					<h2><?php _p(esc_html($cat[0])); ?></h2>
					<span><?php _p(esc_html($cat[1])); ?></span>
				</div>
				<ul>
					<?php foreach($treatments as $item){ ?>
						<?php if($item->cat == $key){ ?>
							<li>
								<a href="<?php _p(esc_url($item->url)); ?>"<?php _p(eg_sheet_attrs($item)); ?>>
									<span>
										<span class="lp-row-name"><?php _p(esc_html($item->name)); ?></span>
										<span class="lp-row-text"><?php _p(esc_html($item->desc)); ?></span>
									</span>
									<?php if(eg_show_prices()){ ?><strong><?php _p(esc_html($item->price)); ?></strong><?php } ?>
									<?php _p(eg_icon('caret-right')); ?>
								</a>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>

		<p class="lp-empty" id="lp-empty" hidden>No encontramos ese tratamiento. Escríbenos por <a href="<?php _p(esc_url(get_whatsapp_url())); ?>" target="_blank" rel="noopener" data-cta="catalogo">WhatsApp</a> y te orientamos.</p>

		<a class="lp-hilos-row" href="<?php _p(esc_url(home_url('/hilos-tensores-cdmx/'))); ?>">
			<span>
				<span class="lp-eyebrow">Página especial</span>
				<span class="lp-row-name">Hilos tensores</span>
				<span>Lifting sin cirugía con hilos PDO</span>
			</span>
			<?php _p(eg_icon('arrow-right')); ?>
		</a>
	</div>
</section>

<?php get_footer(); ?>
