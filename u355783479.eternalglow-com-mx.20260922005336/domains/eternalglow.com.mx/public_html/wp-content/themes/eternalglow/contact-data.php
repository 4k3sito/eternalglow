<?php
    $contact = get_theme_contact();
	$has_emails = $contact->email1 || $contact->email2;
	$has_phones = $contact->phone1 || $contact->phone2;
?>
<div class="contact-data d-grid gap-32">
	<?php if($has_emails){ ?>
		<div class="row g-8 align-items-center">
			<div class="col-md-auto">
				<img src="<?php images_url('sms.svg'); ?>" alt="" />
			</div>
			<div class="col d-grid gap-8">
				<?php for($i = 1; $i <= 2; $i++){ ?>
					<?php if($contact->{"email{$i}"}){ ?>
						<div>
							<a href='mailto:<?php _p($contact->{"email{$i}"}); ?>'><?php _p($contact->{"email{$i}"}); ?></a>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
	<?php if($contact->address){ ?>
		<div class="row g-8 align-items-center">
			<div class="col-md-auto">
				<img src="<?php images_url('location.svg'); ?>" alt="" />
			</div>
			<div class="col">
				<?php _p(nl2br($contact->address)); ?>
			</div>
		</div>
	<?php } ?>
	<?php if($has_phones){ ?>
		<div class="row g-8 align-items-center">
			<div class="col-md-auto">
				<img src="<?php images_url('call.svg'); ?>" alt="" />
			</div>
			<div class="col d-grid gap-8">
				<?php for($i = 1; $i <= 2; $i++){ ?>
					<?php if($contact->{"phone{$i}_plain"}){ ?>
						<div>
							<a href='tel:<?php _p($contact->{"phone{$i}_plain"}); ?>'><?php _p(nl2br($contact->{"phone{$i}"})); ?></a>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
	<?php if($contact->schedules){ ?>
		<div class="row g-8 align-items-center">
			<div class="col-md-auto">
				<img src="<?php images_url('clock.svg'); ?>" alt="" />
			</div>
			<div class="col">
				<?php _p(nl2br($contact->schedules)); ?>
			</div>
		</div>
	<?php } ?>
</div>