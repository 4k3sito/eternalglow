# Graph Report - eternalglow  (2026-10-27)

## Corpus Check
- Large corpus: 15141 files · ~10,455,016 words. Semantic extraction will be expensive (many Claude tokens). Consider running on a subfolder.

## Summary
- 1034 nodes · 1808 edges · 115 communities (35 shown, 80 thin omitted)
- Extraction: 92% EXTRACTED · 8% INFERRED · 0% AMBIGUOUS · INFERRED: 146 edges (avg confidence: 0.86)
- Token cost: 106,897 input · 0 output

## Community Hubs (Navigation)
- Scripts SEO Google (GSC/GBP/GTM)
- Funciones base del tema
- Bootstrap compilado (núcleo)
- Bootstrap: modales y config
- Bootstrap: offcanvas y colapsables
- Bootstrap: tooltips y popovers
- Helpers de WPBakery (_vc)
- Bootstrap: carrusel
- Dependencias de build (dev)
- Bootstrap: scrollspy
- Fancybox compilado
- Bootstrap: dropdowns
- Pipeline de deploy
- Schema y hubs del home
- Config de Webpack
- Bootstrap: tabs
- Imágenes y galerías del tema
- Auditoría SEO (68/100)
- Strings y CTAs multilenguaje
- TypeScript del front (booking, catálogo)
- functions.php y usuario maestro
- Estado y resultados GSC
- Conversión Divi a HTML
- Carga de assets (CSS/JS/manifest)
- Config de TypeScript
- Rutas, colores y fuentes del tema
- Directorios y NAP (schema clínica)
- Tareas, E-E-A-T y medición
- Helpers de URL y roles
- Logos, favicon y schema clínica
- Tokens y post types
- Landings y precios
- Dependencias de runtime
- Título, descripción y plugins SEO
- Tratamientos y respuestas rápidas
- Recolector de tokens CSS
- Archivos y campos de términos
- Reescritura de textos
- AJAX y guardado de metaboxes
- Limpieza schema SASWP
- Scripts npm
- Caché, cron y TTFB
- Validador de schema
- Script deploy.sh
- Plugin manifest Webpack
- Tipos globales (Window)
- Archivos intocables
- get_pages_deep()
- get_terms_deep()

## God Nodes (most connected - your core abstractions)
1. `gn` - 40 edges
2. `re()` - 32 edges
3. `GoogleClient` - 31 edges
4. `we` - 23 edges
5. `pi` - 23 edges
6. `jss_template()` - 22 edges
7. `Wi` - 21 edges
8. `bn` - 19 edges
9. `Pn` - 19 edges
10. `ue()` - 18 edges

## Surprising Connections (you probably didn't know these)
- `Content scripts never auto-deployed` --semantically_similar_to--> `Theme and content deployed separately`  [INFERRED] [semantically similar]
  CLAUDE.md → docs/specs/deploy.md
- `WP cron via cPanel + WP Super Cache preload` --semantically_similar_to--> `WP cron reactivated via cPanel (2026-10-21)`  [INFERRED] [semantically similar]
  CLAUDE.md → docs/STATUS.md
- `Post-purge cache warming from sitemap` --semantically_similar_to--> `WP cron via cPanel + WP Super Cache preload`  [INFERRED] [semantically similar]
  docs/specs/deploy.md → CLAUDE.md
- `eg_privacy_template()` --calls--> `get_theme_contact()`  [INFERRED]
  seo/privacidad.php → u355783479.eternalglow-com-mx.20260922005336/domains/eternalglow.com.mx/public_html/wp-content/themes/eternalglow/includes/_theme.php
- `SEO audit 2026-10-20 (68/100)` --references--> `English /en/ pages indexing`  [EXTRACTED]
  docs/reports/2026-10-20-auditoria-seo.md → tareas-eternalglow.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Deploy pipeline safeguards (backup, approval, separation, cache warm, rollback)** — claude_deploy_recipe, claude_deploy_backup, docs_specs_deploy_production_approval, docs_specs_deploy_theme_content_separation, docs_specs_deploy_cache_warming, claude_rollback_procedure [INFERRED 0.85]
- **SEO audit findings and their remediations in STATUS** — docs_reports_2026_10_20_auditoria_seo_seo_audit, docs_status_clinic_schema_entity, docs_status_security_headers, docs_status_wp_cron_reactivation, docs_status_divi_removal, docs_status_botox_duration_unification [INFERRED 0.85]
- **Local directory listings sharing NAP and UTM** — docs_directorios_doctoralia, docs_directorios_agendapro_marketplace, docs_directorios_yelp, docs_directorios_top_doctors, docs_directorios_nap_consistency, docs_directorios_utm_links [EXTRACTED 1.00]

## Communities (115 total, 80 thin omitted)

### Community 0 - "Scripts SEO Google (GSC/GBP/GTM)"
Cohesion: 0.05
Nodes (38): main(), date_params(), fetch_daily(), fetch_keywords(), main(), write_csv(), main(), service_key() (+30 more)

### Community 1 - "Funciones base del tema"
Cohesion: 0.03
Nodes (20): custom_admin_init(), custom_register_settings(), custom_trim_excerpt(), get_css_animation(), get_custom_trim_excerpt(), get_filter_content(), get_scripts(), get_sidebar_widgets() (+12 more)

### Community 2 - "Bootstrap compilado (núcleo)"
Cohesion: 0.08
Nodes (47): ae(), B(), be(), ce(), ct(), D(), dt(), _e() (+39 more)

### Community 3 - "Bootstrap: modales y config"
Cohesion: 0.05
Nodes (5): Ci, de(), Di, sn, ye

### Community 4 - "Bootstrap: offcanvas y colapsables"
Cohesion: 0.07
Nodes (4): ue(), vi, Wi, Xi

### Community 5 - "Bootstrap: tooltips y popovers"
Cohesion: 0.07
Nodes (3): bn, Ee, gn

### Community 6 - "Helpers de WPBakery (_vc)"
Cohesion: 0.11
Nodes (30): do_hash(), jss_template(), random_string(), rem(), jss_bg_image(), jss_button(), jss_button_arrow(), jss_carousel() (+22 more)

### Community 8 - "Dependencias de build (dev)"
Cohesion: 0.11
Nodes (26): autoprefixer, css-loader, @fullhuman/postcss-purgecss, postcss, postcss-loader, sass, sass-loader, ts-loader (+18 more)

### Community 10 - "Fancybox compilado"
Cohesion: 0.18
Nodes (22): yt(), a(), b(), c(), d(), e(), f(), g() (+14 more)

### Community 12 - "Pipeline de deploy"
Cohesion: 0.11
Nodes (14): Pre-deploy backup ~/backups-deploy (last 10), Eternal Glow WordPress Site (project), GoDaddy cPanel SSH target (godaddy-eternalglow), Python scripts for GSC/GA4/GTM/GBP (google_oauth.py), Hub 'Actualizado el' date from _landings.php 'updated' key, Notion task database, Rollback procedure (theme / theme+db), SEO content scripts seo/*.php (wp eval-file) (+6 more)

### Community 13 - "Schema y hubs del home"
Cohesion: 0.16
Nodes (11): eg_alternate_path(), eg_breadcrumb_schema(), eg_category_hub(), eg_hreflang(), eg_hub(), eg_hubs(), eg_offer_price(), eg_saswp_add_price() (+3 more)

### Community 14 - "Config de Webpack"
Cohesion: 0.11
Nodes (15): del, mini-css-extract-plugin, terser-webpack-plugin, webpack, del@6.1.1, mini-css-extract-plugin@^2.7.6, terser-webpack-plugin@^5.3.9, webpack@^5.84.1 (+7 more)

### Community 16 - "Imágenes y galerías del tema"
Cohesion: 0.12
Nodes (17): custom_input_thumbnail(), get_custom_input_thumbnail(), get_extra_image(), get_term_extra_image(), get_term_thumbnail(), get_the_gallery(), get_the_timthumb(), get_theme_image() (+9 more)

### Community 17 - "Auditoría SEO (68/100)"
Cohesion: 0.12
Nodes (15): /servicio/ pages as plain HTML (lp-sv-* classes), Botox hub /botox-cdmx/ issues, Divi Builder active alongside WPBakery, Keyword-stuffed GBP name, Missing security headers / CSP report-only, 'rellenos cdmx' food intent, Risks & contraindications H2 on hubs, Schema improvements (BreadcrumbList, ItemList, Physician) (+7 more)

### Community 18 - "Strings y CTAs multilenguaje"
Cohesion: 0.17
Nodes (15): eg_privacy_template(), get_pagination(), pagination(), {closure#1}(), {closure#5}(), eg_contact(), eg_cta_book(), eg_cta_whatsapp() (+7 more)

### Community 20 - "functions.php y usuario maestro"
Cohesion: 0.17
Nodes (9): css_variables_from_images(), extra_init(), extra_pre_get_users(), get_css_variables_from_images(), get_master_user_id(), get_related_services(), is_master_user(), register_post_types() (+1 more)

### Community 21 - "Estado y resultados GSC"
Cohesion: 0.22
Nodes (14): Home cannibalizes treatment queries, Keyword map and cannibalization report, Rinomodelacion content expansion candidate, GBP profile metrics (not useful for redesign), GSC results: +63% clicks, position 9.0 to 7.5, Redesign impact preliminary read (2026-10-23), Sculptra pages merge success, Design critique fixes (seo/design-fixes.php) (+6 more)

### Community 22 - "Conversión Divi a HTML"
Cohesion: 0.24
Nodes (12): {closure#2}(), eg_divi_attr(), eg_divi_clean(), eg_divi_hidden_on_desktop(), eg_divi_image(), eg_divi_inline(), eg_divi_module(), eg_divi_modules() (+4 more)

### Community 23 - "Carga de assets (CSS/JS/manifest)"
Cohesion: 0.15
Nodes (15): extra_scripts(), custom_admin_scripts(), get_images_url(), get_rev_manifest(), get_rv(), get_template_url(), get_the_css(), get_the_js() (+7 more)

### Community 24 - "Config de TypeScript"
Cohesion: 0.13
Nodes (14): compilerOptions, allowJs, allowSyntheticDefaultImports, baseUrl, jsx, module, moduleResolution, noImplicitAny (+6 more)

### Community 25 - "Rutas, colores y fuentes del tema"
Cohesion: 0.16
Nodes (14): get_images_path(), get_svg(), get_template_path(), get_theme_color(), get_theme_colors(), get_theme_fonts(), images_path(), load_view() (+6 more)

### Community 26 - "Directorios y NAP (schema clínica)"
Cohesion: 0.17
Nodes (12): AgendaPro marketplace, Directory listing kit, Reference service prices (includes/_treatments.php), sameAs in eg_clinic_schema(), Top Doctors, Per-directory UTM links, Yelp, Correct clinic phone +52 55 5404 9467 (+4 more)

### Community 27 - "Tareas, E-E-A-T y medición"
Cohesion: 0.15
Nodes (12): Doctoralia, Physician missing from hubs and /en/, Dr. Bruno Ocampo (Physician, cedula, E-E-A-T), English /en/ pages indexing, Third-party accounts/emails removed, Google reviews and GBP optimization, Own GTM (GTM-KQFHDQ2M) + GA4 + Clarity measurement, /en/pdo-threads-mexico-city/ page (PR #18) (+4 more)

### Community 28 - "Helpers de URL y roles"
Cohesion: 0.15
Nodes (13): base_url(), custom_after_setup_theme(), custom_current_screen(), get_base_url(), get_the_current_url(), is_demo(), is_localhost(), is_the_user() (+5 more)

### Community 29 - "Logos, favicon y schema clínica"
Cohesion: 0.21
Nodes (7): eg_clinic_schema(), get_theme_favicon(), get_theme_links(), get_theme_logo(), get_theme_logos(), get_theme_social(), get_theme_touch_icon()

### Community 30 - "Tokens y post types"
Cohesion: 0.23
Nodes (10): custom_init(), custom_register_post_types(), custom_wp(), init_vars(), buildToken(), get_jss_token(), getSessionToken(), isValidateToken() (+2 more)

### Community 31 - "Landings y precios"
Cohesion: 0.26
Nodes (7): eg_booking_url(), {closure#1}(), eg_landing(), eg_landings(), eg_page_modified(), eg_price_row(), eg_treatments()

### Community 32 - "Dependencias de runtime"
Cohesion: 0.18
Nodes (11): bootstrap, @fancyapps/ui, jquery, @popperjs/core, postcss-pxtorem, dependencies, bootstrap@^5.3.3, @fancyapps/ui@^5.0.19 (+3 more)

### Community 33 - "Título, descripción y plugins SEO"
Cohesion: 0.20
Nodes (10): get_site_description(), get_site_name(), get_the_site_title(), is_seo_by_rank_math_active(), is_the_home(), is_yoast_seo_active(), send_email(), site_description() (+2 more)

### Community 34 - "Tratamientos y respuestas rápidas"
Cohesion: 0.36
Nodes (6): eg_service_answers(), eg_service_answers_block(), eg_sheet_attrs(), eg_show_prices(), eg_treatment_categories(), eg_treatment_for()

### Community 35 - "Recolector de tokens CSS"
Cohesion: 0.29
Nodes (4): base, queue, seen, tokens

### Community 36 - "Archivos y campos de términos"
Cohesion: 0.29
Nodes (7): custom_input_file(), get_custom_input_file(), get_term_file(), get_the_file_gallery(), get_theme_file(), term_add_file(), term_edit_file()

### Community 38 - "AJAX y guardado de metaboxes"
Cohesion: 0.33
Nodes (6): ajax_email_exists(), ajax_username_exists(), is_request_method_post(), save_metabox_extra_image(), save_metabox_posts_gallery(), save_metabox_script()

### Community 39 - "Limpieza schema SASWP"
Cohesion: 0.60
Nodes (4): eg_saswp_clean(), eg_saswp_is_clinic(), eg_saswp_nodes(), eg_schema_types()

### Community 40 - "Scripts npm"
Cohesion: 0.40
Nodes (5): scripts, build, build-prod, collect-tokens, watch

## Knowledge Gaps
- **24 isolated node(s):** `Python scripts for GSC/GA4/GTM/GBP (google_oauth.py)`, `GoDaddy cPanel SSH target (godaddy-eternalglow)`, `Rollback procedure (theme / theme+db)`, `/servicio/ pages as plain HTML (lp-sv-* classes)`, `Hub 'Actualizado el' date from _landings.php 'updated' key` (+19 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 347 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **80 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `gn` connect `Bootstrap: tooltips y popovers` to `Bootstrap: scrollspy`, `Bootstrap compilado (núcleo)`, `Bootstrap: modales y config`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Are the 11 inferred relationships involving `GoogleClient` (e.g. with `fetch_daily()` and `fetch_keywords()`) actually correct?**
  _`GoogleClient` has 11 INFERRED edges - model-reasoned connections that need verification._
- **What connects `Python scripts for GSC/GA4/GTM/GBP (google_oauth.py)`, `GoDaddy cPanel SSH target (godaddy-eternalglow)`, `Rollback procedure (theme / theme+db)` to the rest of the system?**
  _24 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Scripts SEO Google (GSC/GBP/GTM)` be split into smaller, more focused modules?**
  _Cohesion score 0.05380333951762523 - nodes in this community are weakly interconnected._
- **Why does `re()` connect `Bootstrap: carrusel` to `Bootstrap: scrollspy`, `Bootstrap compilado (núcleo)`?**
  _High betweenness centrality (0.024) - this node is a cross-community bridge._
- **Should `Funciones base del tema` be split into smaller, more focused modules?**
  _Cohesion score 0.030729359496482783 - nodes in this community are weakly interconnected._
- **Why does `we` connect `Bootstrap: scrollspy` to `Bootstrap compilado (núcleo)`, `Bootstrap: modales y config`, `Bootstrap: offcanvas y colapsables`, `Bootstrap: tooltips y popovers`, `Bootstrap: carrusel`, `Bootstrap: dropdowns`, `Bootstrap: tabs`?**
  _High betweenness centrality (0.023) - this node is a cross-community bridge._