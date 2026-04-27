<div class="bottom" id="contact_us">
	<div class="form">
		<div data-aos="fade-right">
			<h3>
            Streamline Sales, Eliminate Mistakes & Increase Your Bottom Line.
			</h3>
			<h1>
            Don't Wait - You're Already
            Losing Ground.
			</h1>
		</div>

		<form id="reg_form" data-aos="fade-up">
			<div class="row">

				<div class="col">
					<label for="name">Name</label>
					<input type="text" placeholder="First Last" name="name" id="name" required>
				</div>
				<div class="col">
					<label for="company">Company</label>
					<input type="text" placeholder="Acme Ltd." name="company" id="company">
				</div>
			</div>
			<div class="row">
				<div class="col">
					<label for="phone">Phone Number</label>
					<input type="tel" placeholder="555-912-0088" name="phone" id="phone">
				</div>
				<div class="col">
					<label for="email">Email</label>
					<input type="email" placeholder="name@developeremail.com" name="email" id="email" required>
				</div>
			</div>
			<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
			<div class="row row-turnstile">
				<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>" data-theme="dark"></div>
			</div>
			<?php endif; ?>
			<div class="row submit_row">
				<input class="submit" type="submit" value="Register">
			</div>
		</form>
	</div>
</div>
