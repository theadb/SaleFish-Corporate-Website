<?php

/**
 * Template Name: Partners Page
 *  Page
 * The template for displaying the App page
 */
get_header();

// HERO
$fade_msg = get_field('fade_messages');
$fade = array();
if (is_array($fade_msg)) {
    foreach ($fade_msg as $msg) {
        $text = $msg['text'];
        array_push($fade, $text);
    }
}

?>

<script>
    let textArray = <?php echo wp_json_encode( $fade, JSON_HEX_TAG | JSON_HEX_AMP ); ?>;
</script>

<main class="partners">
    <!-- HERO -->
    <section class="hero">
        <div class="hero__slideshow" aria-hidden="true">
            <div class="hero__slide is-active">
                <img src="<?php echo get_template_directory_uri(); ?>/img/hero-slides/slide-1.jpg" alt="" loading="eager">
            </div>
            <div class="hero__slide">
                <img src="<?php echo get_template_directory_uri(); ?>/img/hero-slides/slide-2.jpg" alt="" loading="lazy">
            </div>
            <div class="hero__slide">
                <img src="<?php echo get_template_directory_uri(); ?>/img/hero-slides/slide-3.jpg" alt="" loading="lazy">
            </div>
        </div>
        <div class="hero__overlay" aria-hidden="true"></div>
        <div class="wrapper">
            <div class="wrap">
                <h3 data-aos="fade-up" data-aos-delay="100">Make Money. Look Good Doing It.</h3>
                <h1 data-aos="fade-up" data-aos-delay="250">
                    Partner With <br />
                    SaleFish. <br />
                    Own the Future.
                </h1>
                <p data-aos="fade-up" data-aos-delay="400">You’ve got the network. We’ve got the tech.</p>
                <p data-aos="fade-up" data-aos-delay="500"> Let’s stop selling the old way—and start building what’s next.</p>
                <a class="button" data-aos="fade-up" data-aos-delay="620" href="javascript:void(0)" data-sf-modal="partner" data-sf-section="Partners — Hero">Let’s Partner Up</a>
            </div>
        </div>
        <div class="wrapper wrapper_bottom" data-aos="fade-up">
            <h2>
                Join the Platform <br />
                That’s Actually Selling Homes, <br />
                Not Just Looking Good Doing It.
            </h2>
            <p>
                Plug into the SaleFish ecosystem and connect with the people who matter; <br />
                builders, developers, brokerages, and decision-makers who move real inventory. <br />
                Be the one who brings the solution, not just another opinion. <br />
                We’ll handle the tech. You take the credit.</p>
        </div>
    </section>
    <!-- END HERO -->


    <!-- AGENTS -->
    <section class="agents">
        <div class="container">
            <div class="header" data-aos="fade-up" data-aos-delay="300">
                <h1>Got Code, Connections or Clients?
                    Let’s Turn That Into Revenue.</h1>
                <p>Whether you build apps, resell software, advise developers, or just know people who need better
                    tools—SaleFish gives you a way to profit.</p>
                <p>Integrate it. Recommend it. White-label it. Sell it.</p>
                <p>We’ll handle the heavy lifting. You keep the upside.</p>
                <p>Let’s change how new real estate gets sold—and make you a key part of it.</p>
            </div>
            <div class="items">
                <div class="item" data-aos="fade-up">
                    <div class="info">
                        <h4>Plug In. Level Up.</h4>
                        <h3>Technology Partners</h3>
                        <p>Got a killer app, platform, or product? Integrate with SaleFish
                            and become part of the new home sales tech stack. We’ll
                            help you slot in, sync up, and scale faster.
                        </p>
                        <a class="button" href="javascript:void(0)" data-sf-modal="partner" data-sf-partner-type="Integrate my app/tool" data-sf-section="Partners — Technology Card">Integrate & Dominate</a>
                    </div>
                    <div class="content_img">
                        <img
                            src="<?php echo get_template_directory_uri(); ?>/img/partners/crm.png"
                            alt="CRM integration dashboard screenshot">
                    </div>
                </div>
                <div class="item_img" data-aos="fade-up">
                    <p>Tools You Already Trust. Now Working Together.</p>
                    <div class="logos">
                        <div class="col">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/partners/logo_1.png" loading="lazy" decoding="async" alt="" aria-hidden="true">
                        </div>
                        <div class="col">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/partners/logo_2.png" loading="lazy" decoding="async" alt="" aria-hidden="true">
                        </div>
                        <div class="col">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/partners/logo_3.png" loading="lazy" decoding="async" alt="" aria-hidden="true">
                        </div>
                        <div class="col">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/partners/logo_4.png" loading="lazy" decoding="async" alt="" aria-hidden="true">
                        </div>
                        <div class="col">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/partners/logo_5.png" loading="lazy" decoding="async" alt="" aria-hidden="true">
                        </div>
                        <div class="col">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/partners/logo_6.png" loading="lazy" decoding="async" alt="" aria-hidden="true">
                        </div>

                    </div>
                </div>
                <div class="item" data-aos="fade-up">
                    <div class="info">
                        <h4>Know People? Get Paid.</h4>
                        <h3>Referral Partners</h3>
                        <p>You’ve got the network—builders, brokers, developers. We’ve
                            got the software that actually works. Send them our way. We’ll
                            demo. We’ll close. You’ll get cash every time we book a meeting.
                        </p>
                        <a class="button" href="javascript:void(0)" data-sf-modal="partner" data-sf-partner-type="Refer builders, brokers, or developers" data-sf-section="Partners — Referral Card">Start Cashing In</a>
                    </div>
                    <div class="content_img">
                        <img
                            src="<?php echo get_template_directory_uri(); ?>/img/partners/notify.png"
                            alt="SaleFish referral notification illustration">
                    </div>
                </div>
                <div class="item" data-aos="fade-up">
                    <div class="info">
                        <h4>Sell the Platform That Sells.</h4>
                        <h3>Reseller Partners</h3>
                        <p>Offer SaleFish to your clients and unlock serious recurring revenue.
                            Earn commission while helping sales teams move more real estate,
                            more easily. Everyone wins. Especially you.
                        </p>
                        <a class="button" href="javascript:void(0)" data-sf-modal="partner" data-sf-partner-type="Resell the SaleFish platform" data-sf-section="Partners — Reseller Card">You Had Me at Commission</a>
                    </div>
                    <div class="content_img">
                        <img
                            src="<?php echo get_template_directory_uri(); ?>/img/partners/bag.png"
                            alt="SaleFish reseller partner illustration">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END AGENTS -->


    <!-- CONTACT -->
    <section class="contact">
        <?php get_template_part('/partials/contact-partners'); ?>
    </section>
    <!-- END CONTACT -->





    </div>


</main>

<?php
get_footer();

?>