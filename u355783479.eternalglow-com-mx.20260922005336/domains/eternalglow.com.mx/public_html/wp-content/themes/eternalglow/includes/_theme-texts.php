<?php
	$texts = get_theme_texts();
?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Copyright</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p(htmlspecialchars($texts->copyright)); ?>" name="theme_texts[copyright]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Powered by</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p(htmlspecialchars($texts->powered_by)); ?>" name="theme_texts[powered_by]">
		</td>
	</tr>
</table>