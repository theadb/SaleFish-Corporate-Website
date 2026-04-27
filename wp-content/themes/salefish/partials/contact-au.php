<div class="bottom" id="contact_us">
	<div class="form">
		<div data-aos="fade-right">
			<h3>
				REGISTER FOR PRODUCT UPDATES & DEMOS


			</h3>
			<h1>
				Upgrade your
				new home and apartment sales
			</h1>
		</div>

		<form id="reg_form" data-aos="fade-up">
			<div class="row">

				<div class="col">
					<label for="name">NAME</label>
					<input type="text" placeholder="First Last" name="name" id="name" required>
				</div>
				<div class="col">
					<label for="demo">Would you like a demo?</label>
					<select name="demo" id="demo" required>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<label for="company">COMPANY</label>
					<input type="text" placeholder="Acme Ltd." name="company" id="company" required>
				</div>
				<div class="col">
					<label for="title">Title</label>
					<input type="text" placeholder="Sales Manager" name="title" id="title" required>
				</div>

			</div>
			<div class="row">
				<div class="col">
					<label for="email">EMAIL</label>
					<input type="email" placeholder="name@developeremail.com" name="email" id="email" required>
				</div>
				<div class="col">
					<label for="phone">Phone number</label>
					<input type="tel" placeholder="555-912-0088" name="phone" id="phone" required data-parsley-minlength="12"
						data-parsley-minlength-message="This value should be a valid phone number.">
				</div>
			</div>
			<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
			<div class="row row-turnstile">
				<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>" data-theme="dark"></div>
			</div>
			<?php endif; ?>
			<div class="row">
				<input class="submit" type="submit" value="REGISTER">
			</div>
		</form>
	</div>
</div>