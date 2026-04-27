<div class="bottom" id="contact_us">
	<div class="form">
		<div data-aos="fade-right">
			<h3>SATIŞLARI KOLAYLAŞTIRIN, HATALARI ORTADAN KALDIRIN VE KARINIZI ARTIRIN.</h3>
			<h1>Satişlari kolaylaştirin, hatalari ortadan kaldirin ve karinizi artirin.</h1>
		</div>

		<form id="reg_form" data-aos="fade-up">
			<div class="row">

				<div class="col">
					<label for="name">AD-SOYAD</label>
					<input type="text" placeholder="İlk son" name="name" id="name" required>
				</div>
				<div class="col">
					<label for="company">FİRMA</label>
					<input type="text" placeholder="Acme Ltd." name="company" id="company" required>
				</div>
			</div>
			<div class="row">
			
				<div class="col">
					<label for="phone">CEP TEL. NO.</label>
					<input type="tel" placeholder="555-912-0088" name="phone" id="phone" data-parsley-minlength="12"
						data-parsley-minlength-message="This value should be a valid phone number.">
				</div>
				<div class="col">
					<label for="email">E-POSTA</label>
					<input type="email" placeholder="name@developeremail.com" name="email" id="email" required>
				</div>

			</div>
			<?php if ( defined( 'SALEFISH_CF_TURNSTILE_SITEKEY' ) && SALEFISH_CF_TURNSTILE_SITEKEY ) : ?>
			<div class="row row-turnstile">
				<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( SALEFISH_CF_TURNSTILE_SITEKEY ); ?>" data-theme="dark"></div>
			</div>
			<?php endif; ?>
			<div class="row">
				<input class="submit" type="submit" value="KAYIT OLMAK'">
			</div>
		</form>
	</div>
</div>