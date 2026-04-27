<?php
$register_header = get_field('register_header');
$sub_header = get_field('sub_header');
?>

<div class="bottom" id="contact_us">
	<div class="form">
		<div data-aos="fade-right">
			<h3>
				<?php echo esc_html( $register_header['register_title'] ); ?>
			</h3>
			<h1>
				<?php echo esc_html( $register_header['sub_header'] ); ?>

			</h1>
		</div>

		<form id="reg_form" data-aos="fade-up">
			<div class="row">

				<div class="col">
					<label for="name">NAME</label>
					<input type="text" placeholder="First Last" name="name" id="name" required>
				</div>
				<div class="col">
					<label for="company">COMPANY</label>
					<input type="text" placeholder="Acme Ltd." name="company" id="company">
				</div>
			</div>
			<div class="row">
				<div class="col">
					<label for="phone">PHONE NUMBER</label>
					<input type="tel" placeholder="555-912-0088" name="phone" id="phone" required>
				</div>
				<div class="col">
					<label for="email">EMAIL</label>
					<input type="email" placeholder="name@developeremail.com" name="email" id="email" required>
				</div>
			</div>
			<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
			<div class="row row-turnstile">
				<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>" data-theme="dark"></div>
			</div>
			<?php endif; ?>
			<div class="row submit_row">
				<input class="submit" type="submit" value="REGISTER">
			</div>
		</form>
	</div>
</div>