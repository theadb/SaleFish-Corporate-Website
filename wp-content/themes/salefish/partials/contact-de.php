<div class="bottom" id="contact_us">
	<div class="form">
		<div data-aos="fade-right">
			<h3>Optimieren Sie den Verkauf, beseitigen Sie Fehler und steigern Sie Ihr Geschäftsergebnis.</h3>
			<h1 class="de_contact_title">Warten Sie nicht – Sie verlieren bereits an Boden.</h1>
		</div>
		<form id="reg_form" data-aos="fade-up">
			<div class="row">

				<div class="col">
					<label for="name">Vollständiger Name</label>
					<input type="text" placeholder="Erster Letzter" name="name" id="name" required>
				</div>
				<div class="col">
					<label for="company">Unternehmen</label>
					<input type="text" placeholder="Acme Ltd." name="company" id="company" required>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<label for="title">Titel</label>
					<input type="text" placeholder="Verkaufsleiter" name="title" id="title">
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
			<div class="row">
				<input class="button" type="submit" value="Zur Anmeldung">
			</div>
		</form>
	</div>
</div>