<?php
// The schema plugin's per-post "Custom Schema" field (33 posts) holds hand-written JSON-LD with its own business
// node: a wrong phone, other coordinates and self-served ratings Google rejects. The theme prints the only graph,
// so it keeps the page-level nodes from that field (Service, MedicalProcedure, ImageObject, BreadcrumbList,
// FAQPage) and points them at the theme's #clinic node. Kept in its own file so seo/check-schema.php can load it.

const EG_SASWP_DROP_TYPES = array('WebSite', 'MedicalBusiness', 'MedicalClinic', 'LocalBusiness', 'Organization', 'HealthAndBeautyBusiness', 'BeautySalon', 'DaySpa');

function eg_saswp_nodes($post_id){
	$raw = (string)get_post_meta($post_id, 'saswp_custom_schema_field', true);
	if($raw === ''){
		return array();
	}
	$blocks = preg_match_all('#<script[^>]*>(.*?)</script>#s', $raw, $m) ? $m[1] : array($raw);
	$nodes = array();
	foreach($blocks as $json){
		$data = json_decode($json, true);
		if(!is_array($data)){
			continue;
		}
		$list = isset($data['@graph']) ? $data['@graph'] : (isset($data[0]) ? $data : array($data));
		foreach($list as $node){
			if(is_array($node) && !array_intersect((array)($node['@type'] ?? ''), EG_SASWP_DROP_TYPES)){
				unset($node['@context']);
				$nodes[] = eg_saswp_clean($node);
			}
		}
	}
	return $nodes;
}

// Only the clinic itself becomes a #clinic reference: a manufacturer or brand Organization (Galderma, Allergan)
// must stay what it is, or the graph would claim the clinic makes the product.
function eg_saswp_is_clinic($node){
	$id = isset($node['@id']) && is_string($node['@id']) ? $node['@id'] : '';
	if($id !== '' && preg_match('#^'.preg_quote(home_url(), '#').'/?\#(medicalbusiness|organization|clinic)$#i', $id)){
		return true;
	}
	return array_intersect((array)($node['@type'] ?? ''), EG_SASWP_DROP_TYPES)
		&& (($node['name'] ?? '') === 'Eternal Glow' || strpos((string)($node['url'] ?? ''), home_url()) === 0);
}

function eg_saswp_clean($node){
	if(eg_saswp_is_clinic($node)){
		return array('@id' => home_url('/#clinic'));
	}
	unset($node['aggregateRating'], $node['review'], $node['recognizingAuthority']);
	foreach($node as $key => $value){
		if(is_array($value)){
			$node[$key] = eg_saswp_clean($value);
		}
	}
	return $node;
}

function eg_schema_types($nodes){
	$types = array();
	foreach($nodes as $node){
		$types = array_merge($types, (array)($node['@type'] ?? ''));
	}
	return $types;
}
