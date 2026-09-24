<?php
	$social = get_theme_social();
?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Facebook</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($social->facebook); ?>" name="theme_social[facebook]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Twitter</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($social->twitter); ?>" name="theme_social[twitter]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Instagram</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($social->instagram); ?>" name="theme_social[instagram]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">LinkedIn</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($social->linkedin); ?>" name="theme_social[linkedin]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Youtube</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($social->youtube); ?>" name="theme_social[youtube]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Pinterest</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($social->pinterest); ?>" name="theme_social[pinterest]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">TikTok</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($social->tiktok); ?>" name="theme_social[tiktok]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Spotify</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($social->spotify); ?>" name="theme_social[spotify]">
		</td>
	</tr>
</table>