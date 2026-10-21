<?php
// English copy of the contact form (CF7 #54) for the /en/ pages. Run with: wp eval-file seo/contact-form-en.php  (safe to run twice)
// Same fields, recipients and mail body as #54, so the clinic receives leads in the same format; the subject says
// "(EN)" so they know to answer in English. The theme picks it up through the option eg_contact_form_en (eg_contact()).

const EG_FORM_ES = 54;
const EG_FORM_EN_OPTION = 'eg_contact_form_en';

$source = wpcf7_contact_form(EG_FORM_ES);
if (!$source) { echo "ERROR: no existe el formulario " . EG_FORM_ES . "\n"; exit(1); }

$form = <<<'FORM'
<h3 class='mb-24'>Get your free digital facial analysis* here:</h3>
<div class='mb-40'>
	Fill in this short guided form and we will contact you shortly to book your appointment.
</div>
<div class='d-grid gap-24'>
	<div class='form-group'>
		<label class="form-label">How does your skin feel?</label>
		[text* your-feel class:form-control]
	</div>
	<div class='form-group'>
		<label class="form-label">Do you follow a skincare routine?</label>
		[text* your-routine class:form-control]
	</div>
	<div class='form-group'>
		<label class="form-label">What would you like to improve?</label>
		[text* your-improve class:form-control]
	</div>
	<div class='form-group'>
		<label class="form-label">Have you had any aesthetic treatment before?</label>
		[text* your-treatment class:form-control]
	</div>
	<div class='form-group'>
		<label class="form-label">Full name</label>
		[text* your-name class:form-control]
	</div>
	<div class="row g-24">
		<div class="col-md-6">
			<div class='form-group'>
				<label class="form-label">Email</label>
				[email* your-email class:form-control]
			</div>
		</div>
		<div class="col-md-6">
			<div class='form-group'>
				<label class="form-label">Mobile phone</label>
				[tel* your-phone class:form-control]
			</div>
		</div>
	</div>
	<div class='form-group'>
		<label class="form-label">Comments</label>
		[textarea* your-comments class:form-control]
	</div>
</div>
<div class='mt-40 text-center text-md-left'>
	<div class="form-group acceptance fs-14 mb-24">
		[acceptance your-acceptance] I have read and accept the <a href="#" class='text-decoration-underline text-reset'>Privacy notice</a>
	</div>
	<div class="jss-button-arrow">
		<button type="submit" class="btn btn-primary wpcf7-submit">Send</button>
	</div>
</div>
FORM;

// Keys CF7 uses on the page; anything not listed keeps #54's wording.
$messages = array_merge($source->prop('messages'), array(
	'mail_sent_ok' => 'Thank you for your message. It has been sent.',
	'mail_sent_ng' => 'There was an error sending your message. Please try again later.',
	'validation_error' => 'One or more fields have an error. Please check and try again.',
	'spam' => 'There was an error sending your message. Please try again later.',
	'accept_terms' => 'You must accept the terms and conditions before sending your message.',
	'invalid_required' => 'Please fill out this field.',
	'invalid_too_long' => 'This field has a too long input.',
	'invalid_too_short' => 'This field has a too short input.',
	'upload_failed' => 'There was an unknown error uploading the file.',
	'invalid_email' => 'Please enter an email address.',
	'invalid_tel' => 'Please enter a telephone number.',
));

$mail = $source->prop('mail');
$mail['subject'] = '[Eternal Glow] Nueva solicitud de valoración (EN) - [your-name]';

$existing = (int)get_option(EG_FORM_EN_OPTION);
$target = $existing ? wpcf7_contact_form($existing) : null;
if (!$target) {
	$target = $source->copy();
	$target->set_title('Contacto (EN)');
}
$target->set_locale('en_US');
$target->set_properties(array('form' => $form, 'messages' => $messages, 'mail' => $mail));
$id = $target->save();
if (!$id) { echo "ERROR: no se guardó el formulario en inglés\n"; exit(1); }
update_option(EG_FORM_EN_OPTION, (int)$id, false);
echo "OK: formulario en inglés #$id" . ($existing ? " (actualizado)" : " (nuevo)") . "\n";
