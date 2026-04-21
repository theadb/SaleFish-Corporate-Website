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
        <img class="hero_bg"
            src="<?php bloginfo('template_directory'); ?>/img/partners/partners.png"
            alt="Living Room">
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
                <a class="button" data-aos="fade-up" data-aos-delay="620" href="#contact_us">Let’s Partner Up</a>
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
                        <a class="button" href="#contact_us">Integrate & Dominate</a>
                    </div>
                    <div class="content_img">
                        <img
                            src="<?php bloginfo('template_directory'); ?>/img/partners/crm.png">
                    </div>
                </div>
                <div class="item_img" data-aos="fade-up">
                    <p>Tools You Already Trust. Now Working Together.</p>
                    <div class="logos">
                        <div class="col">
                            <img src="<?php bloginfo('template_directory'); ?>/img/partners/logo_1.png">
                        </div>
                        <div class="col">
                            <img src="<?php bloginfo('template_directory'); ?>/img/partners/logo_2.png">
                        </div>
                        <div class="col">
                            <img src="<?php bloginfo('template_directory'); ?>/img/partners/logo_3.png">
                        </div>
                        <div class="col">
                            <img src="<?php bloginfo('template_directory'); ?>/img/partners/logo_4.png">
                        </div>
                        <div class="col">
                            <img src="<?php bloginfo('template_directory'); ?>/img/partners/logo_5.png">
                        </div>
                        <div class="col">
                            <img src="<?php bloginfo('template_directory'); ?>/img/partners/logo_6.png">
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
                        <a class="button" href="#contact_us">Start Cashing In</a>
                    </div>
                    <div class="content_img">
                        <img
                            src="<?php bloginfo('template_directory'); ?>/img/partners/notify.png">
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
                        <a class="button" href="#contact_us">You Had Me at Commission</a>
                    </div>
                    <div class="content_img">
                        <img
                            src="<?php bloginfo('template_directory'); ?>/img/partners/bag.png">
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