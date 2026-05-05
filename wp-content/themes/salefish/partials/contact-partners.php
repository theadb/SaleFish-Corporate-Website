<div class="bottom" id="contact_us">
	<div class="form">
		<div data-aos="fade-right">
			<h3>
            Got Clients, Code, or Just Great Contacts? SaleFish Makes It Easy to Earn and Integrate.
			</h3>
			<h1>
            Want In? Pick Your Lane. <br/>
            We'll Handle the Rest.
			</h1>
		</div>

		<form id="partner_form" data-aos="fade-up">
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
					<input type="email" placeholder="name@company.com" name="email" id="email" required>
				</div>
			</div>
            <div class="row">
				<div class="col">
                <label for="want_to_do">What do you want to do?</label>
                <select name="want_to_do" id="want_to_do" required>
                    <option value="Refer builders, brokers, or developers">Refer builders, brokers, or developers</option>
                    <option value="Resell the SaleFish platform">Resell the SaleFish platform</option>
                    <option value="Integrate my app/tool">Integrate my app/tool</option>
                    <option value="Something else">Something else</option>
                </select>
				</div>
				<div class="col">
					<label for="clients">How Many Clients Could This Help?</label>
					<select name="clients" id="clients" required>
						<option value="1–3">1–3</option>
						<option value="4–10">4–10</option>
						<option value="10+">10+</option>
					</select>
				</div>
			</div>
			<div class="sf-hp-field" aria-hidden="true">
				<label for="sf_hp_partner">Leave this field blank</label>
				<input type="text" name="sf_hp" id="sf_hp_partner" tabindex="-1" autocomplete="off" value="">
			</div>
			<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
			<div class="row">
				<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>" data-theme="dark"></div>
			</div>
			<?php endif; ?>
			<div class="row submit_row">
				<input class="button" type="submit" value="Register">
			</div>
		</form>
	</div>
</div>
