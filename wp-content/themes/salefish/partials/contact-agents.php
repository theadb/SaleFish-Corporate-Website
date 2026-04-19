<?php
$register_header = get_field('register_header');
?>

<div class="bottom" id="contact_us">
	<div class="form">
		<div data-aos="fade-right">
			<h3>
				<?php echo esc_html( $register_header['register_title'] ); ?>
			</h3>
		</div>

		<form id="agent_form" data-aos="fade-up">
			<div class="row">
				<div class="col">
					<label for="name">NAME *</label>
					<input type="text" placeholder="First Last" name="name" id="name" required>
				</div>
				<div class="col">
					<label for="email">EMAIL *</label>
					<input type="email" placeholder="name@developeremail.com" name="email" id="email" required>
				</div>

			</div>
			<div class="row">
				<div class="col">
					<label for="phone">Phone number</label>
					<input type="tel" placeholder="555-912-0088" name="phone" id="phone"
						data-parsley-minlength="12"
						data-parsley-minlength-message="This value should be a valid phone number.">
				</div>
				<div class="col">
					<label for="brokerage">Brokerage Name</label>
					<input type="text" placeholder="" name="brokerage" id="brokerage">
				</div>
			</div>
			<div class="row">
				<div class="col">
					<label for="website_url">Brokerage Website URL</label>
					<input type="text" placeholder="" name="website_url" id="website_url">
				</div>
			</div>
			<div class="sf-hp-field" aria-hidden="true">
				<label for="sf_hp_agent">Leave this field blank</label>
				<input type="text" name="sf_hp" id="sf_hp_agent" tabindex="-1" autocomplete="off" value="">
			</div>
			<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
			<div class="row">
				<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>" data-theme="dark"></div>
			</div>
			<?php endif; ?>
			<div class="row">
				<input class="submit" type="submit" value="REGISTER">
			</div>
		</form>
	</div>
</div>