<?php
$register_sub_title = get_field('register_sub_title');
$register_title = get_field('register_title');
?>

<div class="bottom" id="contact_us">
	<div class="form">
		<div data-aos="fade-right">
			<h3>
				<?php echo esc_html( $register_sub_title ); ?>
			</h3>
			<h1>
				<?php echo esc_html( $register_title ); ?>
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
					<input type="tel" placeholder="555-912-0088" name="phone" id="phone" required
						data-parsley-minlength="12"
						data-parsley-minlength-message="This value should be a valid phone number.">
				</div>
			</div>
			<div class="sf-hp-field" aria-hidden="true">
				<label for="sf_hp_reg">Leave this field blank</label>
				<input type="text" name="sf_hp" id="sf_hp_reg" tabindex="-1" autocomplete="off" value="">
			</div>
			<div class="row">
				<input class="submit" type="submit" value="REGISTER">
			</div>
		</form>
	</div>
</div>