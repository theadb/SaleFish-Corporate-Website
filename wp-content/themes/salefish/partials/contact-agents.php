<?php
$register_header = get_field('register_header');
?>

<div class="bottom" id="contact_us">
	<div class="form">
		<div data-aos="fade-right">
			<h3>
				<?php echo $register_header['register_title'] ?>
			</h3>
		</div>

		<form id="agent_form" data-aos="fade-up">
			<div class="row">
				<div class="col">
					<label for="name">NAME *</label>
					<input type="text" placeholder="First Last" name="name" id="name" required>
				</div>
				<div class="col">
					<label for="email">EMAIL *</label>
					<input type="email" placeholder="name@developeremail.com" name="email" id="email" required>
				</div>

			</div>
			<div class="row">
				<div class="col">
					<label for="phone">Phone number</label>
					<input type="text" placeholder="555-912-0088" name="phone" id="phone" 
						data-parsley-minlength="12"
						data-parsley-minlength-message="This value should be a valid phone number.">
				</div>
				<div class="col">
					<label for="brokerage">Brokerage Name</label>
					<input type="text" placeholder="" name="brokerage" id="brokerage">
				</div>
			</div>
			<div class="row">
				<div class="col">
					<label for="website_url">Brokerage Website URL</label>
					<input type="text" placeholder="" name="website_url" id="website_url">
				</div>
				<div class="col">
					<label for="geo_expertise">Geographic Area of Expertise</label>
					<input type="text" placeholder="" name="geo_expertise" id="geo_expertise">
				</div>

			</div>
			<div class="row">
				<div class="col">
					<label for="property_expertise">Property Expertise</label>
					<select name="property_expertise" id="property_expertise">
						<option value="All">All</option>
						<option value="Single Family Homes">Single Family Homes</option>
						<option value="Townhomes">Townhomes</option>
						<option value="Condos">Condos</option>
					</select>
				</div>
				<div class="col">
					<label for="howhear">How Did You Hear About Us?</label>
					<input type="text" placeholder="" name="howhear" id="howhear">
				</div>

			</div>
			<div class="row">
				<div class="col">
					<label for="see_projects">Specific Projects You Want to See</label>
					<input type="text" placeholder="" name="see_projects" id="see_projects">
				</div>
				<div class="col">
					<label for="see_feature">Features Would You Like to See</label>
					<input type="text" placeholder="" name="see_feature" id="see_feature">
				</div>
			</div>
			<div class="row">
				<input class="submit" type="submit" value="REGISTER">
			</div>
		</form>
	</div>
</div>