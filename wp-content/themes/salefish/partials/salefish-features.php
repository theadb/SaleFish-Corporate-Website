<?php
$features = get_field('features', 'option');
$service_support = get_field('service_support', 'option') ?: [];
$service_support_button = $service_support['button'] ?? [];
$service_support_button_text = mb_convert_case($service_support_button['text'] ?? '', MB_CASE_TITLE, 'UTF-8');
$service_support_button_link = 'https://chatting.page/salefish';
$counter = 0;
?>

<div class="salefish_features" id="features">
    <div class="container">
        <div class="header" data-aos="fade-up">
            <h1>SaleFish Features</h1>
            <p>Every Tool You Need to Close Deals—No Matter Where You Are.</p>
        </div>
        <div class="features">
            <?php foreach((is_array($features) ? $features : []) as $row):
                $is_even = $counter % 2 == 0;
                $image = $row['image'];
                $sub_title = mb_convert_case($row['sub_title'], MB_CASE_TITLE, 'UTF-8');
                $title = $row['title'];
                $content = $row['content'];
                $button = $row['button'];
                $button_text = mb_convert_case($button['text'], MB_CASE_TITLE, 'UTF-8');
                ?>
            <div class="feature">
                <?php if ($is_even): ?>
                <div class="content content_image" data-aos="fade-right">
                    <img src="<?php echo esc_url( $image ); ?>" loading="lazy" decoding="async">
                </div>
                <div class="content context_info" data-aos="fade-left">
                    <h3><?php echo esc_html( $sub_title ); ?></h3>
                    <h2><?php echo esc_html( $title ); ?></h2>
                    <p><?php echo wp_kses_post( $content ); ?></p>
                    <a class="button" href="javascript:void(0)" data-sf-modal="register" data-sf-section="Features — Button">See It in Action</a>
                </div>
                <?php else: ?>
                <div class="content context_info" data-aos="fade-right">
                    <h3><?php echo esc_html( $sub_title ); ?></h3>
                    <h2><?php echo esc_html( $title ); ?></h2>
                    <p><?php echo wp_kses_post( $content ); ?></p>
                    <a class="button" href="javascript:void(0)" data-sf-modal="register" data-sf-section="Features — Button">See It in Action</a>
                </div>
                <div class="content content_image" data-aos="fade-left">
                    <img src="<?php echo esc_url( $image ); ?>" loading="lazy" decoding="async">
                </div>
                <?php endif; ?>
            </div>
            <?php
                $counter++;
            endforeach;
?>
            <div class="service_support" data-aos="fade-up">
                <h3><?php echo esc_html( mb_convert_case($service_support['sub_title'], MB_CASE_TITLE, 'UTF-8') ); ?></h3>
                <h2><?php echo esc_html( $service_support['title'] ); ?></h2>
                <p>
                    <?php echo wp_kses_post( $service_support['content'] ); ?>
                </p>
                <a class="button" href="<?php echo esc_url( $service_support_button_link ); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo esc_html( $service_support_button_text ); ?>
                </a>
            </div>
        </div>
    </div>
</div>
