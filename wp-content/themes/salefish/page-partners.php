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
foreach ($fade_msg as $msg) {
    $text = $msg['text'];
    array_push($fade, $text);
}
$hero_header = get_field('hero_header');
$hero_description = get_field('hero_description');
$hero_image = get_field('hero_image');

// BUILDERS
$builders_developers = get_field('builders_developers');


// AGENTS
$agents = get_field('agents');
$agents_header = get_field('agents_header');
$agents_header_title = $agents_header['title'];
$agents_header_content = $agents_header['content'];
$agents_header_button = $agents_header['button'];
$agents_header_button_text = $agents_header_button['text'];
$agents_header_button_link = $agents_header_button['link'];

// BUILDERS
$builders = get_field('builders');
$builders_header = get_field('builders_header');
$builders_header_title = $builders_header['title'];
$builders_header_content = $builders_header['content'];
$builders_header_button = $builders_header['button'];
$builders_header_button_text = $builders_header_button['text'];
$builders_header_button_link = $builders_header_button['link'];

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
        <div class="wrapper" data-aos="fade-left">
            <div class="wrap">
                <h3>MAKE MONEY. LOOK GOOD DOING IT.</h3>
                <h1>
                    PARTNER WITH <br />
                    SALEFISH. <br />
                    OWN THE FUTURE.
                </h1>
                <p>You’ve got the network. We’ve got the tech.</p>
                <p> Let’s stop selling the old way—and start building what’s next.</p>
                <a class="button"  href="#contact_us">LET’S PARTNER UP</a>
            </div>
        </div>
        <div class="wrapper wrapper_bottom" data-aos="fade-left">
            <h2>
                JOIN THE PLATFORM <br />
                THAT’S ACTUALLY SELLING HOMES, <br />
                NOT JUST LOOKING GOOD DOING IT.
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
                <h1>GOT CODE, CONNECTIONS OR CLIENTS?
                    LET’S TURN THAT INTO REVENUE.</h1>
                <p>Whether you build apps, resell software, advise developers, or just know people who need better
                    tools—SaleFish gives you a way to profit.</p>
                <p>Integrate it. Recommend it. White-label it. Sell it.</p>
                <p>We’ll handle the heavy lifting. You keep the upside.</p>
                <p>Let’s change how new real estate gets sold—and make you a key part of it.</p>
            </div>
            <div class="items">
                <div class="item">
                    <div class="info" data-aos="fade-right" data-aos-delay="300">
                        <h4>PLUG IN. LEVEL UP.</h4>
                        <h3>Technology Partners</h3>
                        <p>Got a killer app, platform, or product? Integrate with SaleFish
                            and become part of the new home sales tech stack. We’ll
                            help you slot in, sync up, and scale faster.
                        </p>
                        <a class="button" href="#contact_us">INTEGRATE & DOMINATE</a>
                    </div>
                    <div class="content_img" data-aos="fade-left" data-aos-delay="300">
                        <img
                            src="<?php bloginfo('template_directory'); ?>/img/partners/crm.png">
                    </div>
                </div>
                <div class="item_img" data-aos="fade-up" data-aos-delay="300">
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
                <div class="item">
                    <div class="info" data-aos="fade-right" data-aos-delay="300">
                        <h4>KNOW PEOPLE? GET PAID.</h4>
                        <h3>Referral Partners</h3>
                        <p>You’ve got the network—builders, brokers, developers. We’ve
                            got the software that actually works. Send them our way. We’ll
                            demo. We’ll close. You’ll get cash every time we book a meeting.
                        </p>
                        <a class="button" href="#contact_us">START CASHING IN</a>
                    </div>
                    <div class="content_img" data-aos="fade-left" data-aos-delay="300">
                        <img
                            src="<?php bloginfo('template_directory'); ?>/img/partners/notify.png">
                    </div>
                </div>
                <div class="item">
                    <div class="info" data-aos="fade-right" data-aos-delay="300">
                        <h4>SELL THE PLATFORM THAT SELLS.</h4>
                        <h3>Reseller Partners</h3>
                        <p>Offer SaleFish to your clients and unlock serious recurring revenue.
                            Earn commission while helping sales teams move more real estate,
                            more easily. Everyone wins. Especially you.
                        </p>
                        <a class="button" href="#contact_us">YOU HAD ME AT COMMISSION</a>
                    </div>
                    <div class="content_img" data-aos="fade-left" data-aos-delay="300">
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