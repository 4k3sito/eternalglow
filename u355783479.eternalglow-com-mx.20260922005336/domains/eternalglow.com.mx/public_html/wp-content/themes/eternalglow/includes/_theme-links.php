<?php
	$links = get_theme_links();
?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Header Botón Texto</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($links->header_btn_text); ?>" name="theme_links[header_btn_text]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Header Botón Link</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($links->header_btn_link); ?>" name="theme_links[header_btn_link]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Aviso de privacidad</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($links->privacy); ?>" name="theme_links[privacy]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Términos y condiciones</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($links->terms); ?>" name="theme_links[terms]">
		</td>
	</tr>
</table>