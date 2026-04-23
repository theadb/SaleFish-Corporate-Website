<?php


get_header();

// HERO
$fade_msg = get_field('fade_messages');
$fade = array();
foreach ((is_array($fade_msg) ? $fade_msg : []) as $msg) {
    $text = $msg['text'];
    array_push($fade, $text);
}
$logo = get_field('logo');
$sub_title = get_field('sub_title');
$hero_header = get_field('hero_header');
$hero_description = get_field('hero_description');
$hero_image = get_field('hero_image');
$background_color = get_field('background_color');

// BUILDERS
$builders_developers = get_field('builders_developers');


// AGENTS
$agents = get_field('agents');
$agents_header = get_field('agents_header') ?: [];
$agents_header_title = $agents_header['title'] ?? '';
$agents_header_content = $agents_header['content'] ?? '';
$agents_header_button = $agents_header['button'] ?? [];
$agents_header_button_text = $agents_header_button['text'] ?? '';
$agents_header_button_link = $agents_header_button['link'] ?? '';
?>

<main class="single_marketplace">
    <!-- HERO -->
    <section class="hero" style="background: <?php echo esc_attr( $background_color ); ?>">
        <a href="/"><img class="salefish_logo" src="<?php echo get_template_directory_uri(); ?>/img/salefish_logo.png" alt="Salefish"></a>
        <div class="wrapper" style="background: <?php echo esc_attr( $background_color ); ?>">
            <div class="max_wrapper">
                <div class="left" data-aos="fade-right" data-aos-delay="300">
                    <img class="broker_logo"
                        src="<?php echo esc_url( $logo ); ?>">
                    <h3><?php echo esc_html( $sub_title ); ?></h3>
                    <h1>
                        <?php echo wp_kses_post( $hero_header ); ?>
                    </h1>
                    <?php echo wp_kses_post( $hero_description ); ?>
                    <a class="button" target="_blank" rel="noopener noreferrer" href="https://marketplace.salefish.app/marketplace-welcome">SIGN UP NOW</a>
                </div>
                <div class="right" data-aos="zoom-in" data-aos-delay="300">
                    <img class="salefish_demo"
                        src="<?php echo esc_url( $hero_image ); ?>"
                        alt="Salefish App Demo">
                </div>
            </div>
        </div>
    </section>
    <!-- END HERO -->

    <!-- BUILDER -->
    <div class="builders_overlay">
        <section class="builders" style="background: <?php echo esc_attr( $background_color ); ?>">
            <div class="max_wrapper">
                <div data-aos="fade-right">
                    <h3>SALEFISH NEW HOME & CONDO MARKETPLACE</h3>
                    <h3 class="bold">FEATURES PROJECTS BY:</h3>
                </div>

                <div data-aos="fade-zoom-in" class="builders_wrap">
                    <div class="swiper buildersSwiper">
                        <div class="swiper-wrapper">
                            <?php foreach((is_array($builders_developers) ? $builders_developers : []) as $builder): ?>
                            <div class="swiper-slide">
                                <img class="builder_logo builder_1"
                                    src="<?php echo esc_url( $builder ); ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mobile_builders">
                        <div class="row">
                            <?php foreach((is_array($builders_developers) ? $builders_developers : []) as $builder): ?>
                            <div class="col">
                                <img class="builder_logo builder_1"
                                    src="<?php echo esc_url( $builder ); ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- END BUILDERS -->

    <!-- AGENTS -->
    <section class="agents">
        <div class="container">
            <div class="header" data-aos="fade-up" data-aos-delay="300">
                <h1><?php echo esc_html( $agents_header_title ); ?></h1>
                <p><?php echo wp_kses_post( $agents_header_content ); ?></p>
                <?php if($agents_header_button_link):?>
                <a class="button"
                    href="<?php echo esc_url( $agents_header_button_link ); ?>">
                    <?php echo esc_html( $agents_header_button_text ); ?>
                </a>
                <?php endif; ?>
            </div>
            <div class="items">
                <?php foreach((is_array($agents) ? $agents : []) as $row):
                    $image = $row['image'];
                    $sub_title = $row['sub_title'];
                    $title = $row['title'];
                    $content = $row['content'];
                    ?>
                <div class="item">
                    <div class="content_img" data-aos="fade-up" data-aos-delay="300">
                        <img src="<?php echo esc_url( $image ); ?>">
                    </div>
                    <div class="info" data-aos="fade-right" data-aos-delay="300">
                        <h3><?php echo esc_html( $sub_title ); ?></h3>
                        <h2><?php echo esc_html( $title ); ?></h2>
                        <p><?php echo wp_kses_post( $content ); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- END AGENTS -->

    <section class="contact contact_bottom" >
		<div class="top_overlay"></div>
		<div class="middle_overlay"></div>
		<div class="bottom_overlay"></div>
		<div class="top"  style="background: <?php echo esc_attr( $background_color ); ?>" >
			<div class="top_content_center" style="background: <?php echo esc_attr( $background_color ); ?>" data-aos="fade-up" data-aos-delay="300">
				<h1>
					NEED SUPPORT?
				</h1>
				<p data-aos="fade-up">
                Are you a member of the <br/>
                SaleFish New Home & Condo Marketplace<br/>
                and need help? We’ve got you covered.
				</p>
                <a class="button" target="_blank" rel="noopener noreferrer" href="https://chatting.page/salefish">GET LIVE CHAT SUPPORT</a>

			</div>
		</div>
	</section>

</main>

<?php
get_footer();

?>