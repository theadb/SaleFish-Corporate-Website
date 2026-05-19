<div class="bottom" id="contact_us">
	<div class="form">
		<div data-aos="fade-right">
			<p class="eyebrow">
            Streamline Sales, Eliminate Mistakes &amp; Increase Your Bottom Line.
			</p>
			<h2>
            Don't Wait - You're Already
            Losing Ground.
			</h2>
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
			<input type="text" name="sf_hp" style="display:none" tabindex="-1" autocomplete="off">
			<input type="hidden" name="sf_page_ts" class="sf-page-ts">
			<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
			<div class="row">
				<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>" data-theme="auto"></div>
			</div>
			<?php endif; ?>
			<div class="row submit_row">
				<input class="button" type="submit" value="Register">
			</div>
		</form>
	</div>
</div>
