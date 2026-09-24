<?php
	$logos = get_theme_logos();
?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Logo 1</th>
		<td>
			<?php custom_input_thumbnail('theme_logos[logo1]', $logos->logo1); ?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Logo 2</th>
		<td>
			<?php custom_input_thumbnail('theme_logos[logo2]', $logos->logo2); ?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Favicon</th>
		<td>
			<?php custom_input_thumbnail('theme_logos[favicon]', $logos->favicon); ?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Touch Icon</th>
		<td>
			<?php custom_input_thumbnail('theme_logos[touch_icon]', $logos->touch_icon); ?>
		</td>
	</tr>
</table>