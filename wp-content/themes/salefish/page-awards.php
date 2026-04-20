<?php

/**
 * Template Name: Awards Page
 *  Page
 * The template for displaying the App page
 */
get_header();

$awards = get_field('awards');

?>

<main class="awards">
    <section class="hero">
        <div class="hero_bg"></div>
        <div class="top_msg">
            <div class="left" data-aos="fade-right">
                <h1>
                    If You're the Best in the Business, You Don't Need to Tell People
                </h1>
                <h3>
                    They'll Tell You.
                </h3>
            </div>
            <div class="right" data-aos="fade-in">
                <img src="<?php bloginfo('template_directory'); ?>/img/trophy.png"
                    class="trophy">
            </div>
        </div>
    </section>
    <div class="timeline-container">
        <h2 class="timeline-title">Our Trophy Case</h2>
        <div class="timeline">
            <?php foreach($awards as $row):
                $year = $row['year'];
                $award = $row['award'];
                ?>
            <div class="timeline-year">
                <div class="year-marker"><?php echo esc_html( $year ); ?></div>
                <div class="content">
                    <?php foreach($award as $award_row):
                        $award_name = $award_row['award_name'];
                        $organization = $award_row['organization'];
                        $location = $award_row['location'];
                        $banner_colour = $award_row['banner_colour'];

                        $class = ($counter % 2 == 0) ? "right" : "left";
                        $counter++;
                        ?>
                    <div
                        class="award-container <?php echo esc_attr( $class ); ?>">
                        <div class="award-box">
                            <h3
                                style="background-color: <?php echo esc_attr( $banner_colour ); ?>">
                                <?php echo esc_html( $award_name ); ?>
                            </h3>
                            <div class="wrap">
                                <p><?php echo esc_html( $organization ); ?></p>
                                <span><?php echo esc_html( $location ); ?></span>
                            </div>
                        </div>
                        <div class="line_<?php echo esc_attr( $class ); ?>">
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </div>

<!-- CONTACT -->
	<section class="contact">
		<div class="top_overlay"></div>
		<div class="middle_overlay"></div>
		<div class="bottom_overlay"></div>
		<div class="top">
			<div class="top_content_center" data-aos="fade-up">
				<h2>
					“Builders, developers, and sales teams don’t want to be sold
					to. But the SaleFish experience speaks for itself. Their job
					has never been easier.“
				</h2>
				<p>
					RICK HAWS <br />
					PRESIDENT & CO-FOUNDER
				</p>
			</div>
			<a class="button" target="_blank" rel="noopener noreferrer" href="https://meetings.hubspot.com/cindy-lloyd?uuid=f03a4178-d44c-48de-9a97-6795425bd38c">BOOK A FREE DEMO</a>
		</div>
		<?php get_template_part('/partials/contact-general'); ?>

	</section>
	<!-- END CONTACT -->
</main>

<?php
get_footer();

?>