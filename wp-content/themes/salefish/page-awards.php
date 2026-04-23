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
            <div class="left">
                <span class="hero-eyebrow" data-aos="fade-up" data-aos-delay="100">Recognition &amp; Excellence</span>
                <h1 data-aos="fade-up" data-aos-delay="250">
                    If You're the Best in the Business, You Don't Need to Tell People
                </h1>
                <h3 data-aos="fade-up" data-aos-delay="400">
                    They'll Tell You.
                </h3>
            </div>
            <div class="right" data-aos="fade-left" data-aos-delay="200">
                <img src="<?php echo get_template_directory_uri(); ?>/img/trophy.png"
                    class="trophy" loading="lazy" decoding="async" alt="SaleFish awards trophy">
            </div>
        </div>
    </section>

    <div class="timeline-container">
        <h2 class="timeline-title">Our Trophy Case</h2>
        <div class="timeline">
            <?php
            $globalDelay = 0;
            foreach ( (is_array($awards) ? $awards : []) as $row ) :
                $year  = $row['year'];
                $award = $row['award'];
            ?>
            <div class="timeline-year" data-aos="fade-up">
                <div class="year-marker"><?php echo esc_html( $year ); ?></div>
                <div class="content">
                    <?php foreach ( $award as $award_row ) :
                        $award_name    = $award_row['award_name'];
                        $organization  = $award_row['organization'];
                        $location      = $award_row['location'];
                        $banner_colour = $award_row['banner_colour'];
                    ?>
                    <div class="award-card card-animate" style="animation-delay: <?php echo $globalDelay * 0.07; ?>s">
                        <div class="award-card__accent" style="background: <?php echo esc_attr( $banner_colour ); ?>"></div>
                        <div class="award-card__body">
                            <h3 class="award-card__name"><?php echo esc_html( $award_name ); ?></h3>
                            <p class="award-card__org"><?php echo esc_html( $organization ); ?></p>
                            <span class="award-card__loc"><?php echo esc_html( $location ); ?></span>
                        </div>
                    </div>
                    <?php $globalDelay++; endforeach; ?>
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
                    "Builders, developers, and sales teams don't want to be sold
                    to. But the SaleFish experience speaks for itself. Their job
                    has never been easier."
                </h2>
                <p>
                    RICK HAWS <br />
                    PRESIDENT &amp; CO-FOUNDER
                </p>
            </div>
            <a class="button" href="javascript:void(0)" data-sf-modal="register" data-sf-section="Awards — CTA">Book a Free Demo</a>
        </div>
        <?php get_template_part('/partials/contact-general'); ?>

    </section>
    <!-- END CONTACT -->
</main>

<?php
get_footer();
?>
