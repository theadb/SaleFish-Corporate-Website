<?php
/**
 * Lazy-injected modals.
 *
 * Rendered into a <template id="sf-modals-template"> tag in header.php so the
 * markup is parsed but NOT instantiated as live DOM. The footer's
 * sfEnsureModals() function clones the template content into <body> on the
 * first user interaction with a [data-sf-modal] trigger — saving ~140 lines
 * of HTML parsing, plus Parsley validation + Turnstile init costs, on every
 * page where the modal is never opened.
 */
?>
<!-- Registration Modal -->
<div class="sf-reg-modal" id="sf-reg-modal" role="dialog" aria-modal="true" aria-label="Register for Access">
	<div class="sf-reg-modal__backdrop"></div>
	<div class="sf-reg-modal__panel">
		<button class="sf-reg-modal__close" aria-label="Close dialog">
			<i data-lucide="x"></i>
		</button>
		<div class="sf-reg-modal__scroll">
			<div class="sf-reg-modal__inner">
				<h3>Streamline Sales, Eliminate Mistakes &amp; Increase Your Bottom Line.</h3>
				<h1>Don't Wait — You're Already Losing Ground.</h1>
				<form id="sf_reg_form">
					<input type="text" name="sf_hp" style="display:none" tabindex="-1" autocomplete="off">
					<input type="hidden" name="sf_section" id="sf_reg_section" value="">
					<div class="row">
						<div class="col">
							<label for="sf_reg_name">Name</label>
							<input type="text" placeholder="First Last" name="name" id="sf_reg_name" required>
						</div>
						<div class="col">
							<label for="sf_reg_demo">Would you like a demo?</label>
							<select name="demo" id="sf_reg_demo" required>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="sf_reg_company">Company</label>
							<input type="text" placeholder="Acme Ltd." name="company" id="sf_reg_company" required>
						</div>
						<div class="col">
							<label for="sf_reg_title">Title</label>
							<input type="text" placeholder="Sales Manager" name="title" id="sf_reg_title" required>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="sf_reg_email">Email</label>
							<input type="email" placeholder="name@developeremail.com" name="email" id="sf_reg_email" required>
						</div>
						<div class="col">
							<label for="sf_reg_phone">Phone Number</label>
							<input type="tel" placeholder="555-912-0088" name="phone" id="sf_reg_phone" required
								data-parsley-minlength="12"
								data-parsley-minlength-message="This value should be a valid phone number.">
						</div>
					</div>
					<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
					<div class="row row-turnstile">
						<div class="cf-turnstile"
							data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>"
							data-theme="dark"></div>
					</div>
					<?php endif; ?>
					<div class="row">
						<input class="button" type="submit" value="Register">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Partner Registration Modal -->
<div class="sf-partner-modal" id="sf-partner-modal" role="dialog" aria-modal="true" aria-label="Partner with SaleFish">
	<div class="sf-partner-modal__backdrop"></div>
	<div class="sf-partner-modal__panel">
		<button class="sf-partner-modal__close" aria-label="Close dialog">
			<i data-lucide="x"></i>
		</button>
		<div class="sf-partner-modal__scroll">
			<div class="sf-partner-modal__inner">
				<h3>Got Clients, Code, or Just Great Contacts? SaleFish Makes It Easy to Earn and Integrate.</h3>
				<h1>Want In? Pick Your Lane. <span>We'll Handle the Rest.</span></h1>
				<form id="sf_partner_form">
					<input type="text" name="sf_hp" style="display:none" tabindex="-1" autocomplete="off">
					<div class="row">
						<div class="col">
							<label for="sf_partner_name">Name</label>
							<input type="text" placeholder="First Last" name="name" id="sf_partner_name" required>
						</div>
						<div class="col">
							<label for="sf_partner_company">Company</label>
							<input type="text" placeholder="Acme Ltd." name="company" id="sf_partner_company">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="sf_partner_phone">Phone Number</label>
							<input type="tel" placeholder="555-912-0088" name="phone" id="sf_partner_phone">
						</div>
						<div class="col">
							<label for="sf_partner_email">Email</label>
							<input type="email" placeholder="name@company.com" name="email" id="sf_partner_email" required>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="sf_partner_want_to_do">What Do You Want to Do?</label>
							<select name="want_to_do" id="sf_partner_want_to_do" required>
								<option value="Refer builders, brokers, or developers">Refer builders, brokers, or developers</option>
								<option value="Resell the SaleFish platform">Resell the SaleFish platform</option>
								<option value="Integrate my app/tool">Integrate my app/tool</option>
								<option value="Something else">Something else</option>
							</select>
						</div>
						<div class="col">
							<label for="sf_partner_clients">How Many Clients Could This Help?</label>
							<select name="clients" id="sf_partner_clients" required>
								<option value="1–3">1–3</option>
								<option value="4–10">4–10</option>
								<option value="10+">10+</option>
							</select>
						</div>
					</div>
					<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
					<div class="row row-turnstile">
						<div class="cf-turnstile"
							data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>"
							data-theme="dark"></div>
					</div>
					<?php endif; ?>
					<div class="row">
						<input class="button" type="submit" value="Register">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
