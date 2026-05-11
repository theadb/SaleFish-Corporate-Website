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
					<input type="tel" placeholder="555-912-0088" name="phone" id="phone" required>
				</div>
				<div class="col">
					<label for="email">Email</label>
					<input type="email" placeholder="name@developeremail.com" name="email" id="email" required>
				</div>
			</div>
			<div class="sf-hp-field" aria-hidden="true">
				<label for="sf_hp_reg">Leave this field blank</label>
				<input type="text" name="sf_hp" id="sf_hp_reg" tabindex="-1" autocomplete="off" value="">
			</div>
			<div class="row submit_row">
				<input class="button" type="submit" value="Register">
			</div>
		</form>
	</div>
</div>