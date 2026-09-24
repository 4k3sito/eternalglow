<?php
	$contact = get_theme_contact();
	$cf7 = get_posts(array(
		'posts_per_page' => -1,
		'post_type' => 'wpcf7_contact_form',
	));
?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Teléfono 1</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($contact->phone1); ?>" name="theme_contact[phone1]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Teléfono 2</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($contact->phone2); ?>" name="theme_contact[phone2]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Email 1</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($contact->email1); ?>" name="theme_contact[email1]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Email 2</th>
		<td>
			<input type="text" class="regular-text" value="<?php _p($contact->email2); ?>" name="theme_contact[email2]">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Dirección</th>
		<td>
			<textarea class="regular-text minh-70" name="theme_contact[address]"><?php _p($contact->address); ?></textarea>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Mapa</th>
		<td>
			<textarea class="regular-text minh-70" name="theme_contact[map]"><?php _p($contact->map); ?></textarea>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Horarios</th>
		<td>
			<textarea class="regular-text minh-70" name="theme_contact[schedules]"><?php _p($contact->schedules); ?></textarea>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Formulario</th>
		<td>
			<select class="regular-text" name="theme_contact[form]">
				<option value=''>Seleccione</option>
				<?php foreach($cf7 as $_cf7){ ?>
					<option value='<?php _p($_cf7->ID); ?>' <?php if($contact->form == $_cf7->ID){ ?>selected<?php } ?>>
						<?php _p($_cf7->post_title); ?>
					</option>
				<?php } ?>
			</select>
		</td>
	</tr>
</table>