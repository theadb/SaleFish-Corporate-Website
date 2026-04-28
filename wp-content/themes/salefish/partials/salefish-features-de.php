<?php
$features = get_field('de_features', 'option');
$service_support = get_field('de_service_support', 'option') ?: [];
$service_support_button = $service_support['button'] ?? [];
$service_support_button_text = $service_support_button['text'] ?? '';
$service_support_button_link = $service_support_button['link'] ?? '';
$counter = 0;
?>

<div class="salefish_features" id="features">
    <div class="container">
        <div class="header" data-aos="fade-up">
            <h1>SaleFish-Funktionen</h1>
            <p>Alle Werkzeuge, die Sie brauchen, um auf dem heutigen Markt zu verkaufen.</p>
        </div>
        <div class="features">
            <?php foreach((is_array($features) ? $features : []) as $row):
                $is_even = $counter % 2 == 0;
                $image = $row['image'];
                $sub_title = $row['sub_title'];
                $title = $row['title'];
                $content = $row['content'];
                $button = $row['button'];
                $button_text = $button['text'];
                $button_link= $button['link'];
                ?>
            <div class="feature">
                <?php if ($is_even): ?>
                <div class="content content_image" data-aos="fade-right">
                    <?php sf_picture( $image, [ 'alt' => $title ] ); ?>
                </div>
                <div class="content context_info" data-aos="fade-left">
                    <h3><?php echo esc_html( $sub_title ); ?></h3>
                    <h2><?php echo esc_html( $title ); ?></h2>
                    <p><?php echo wp_kses_post( $content ); ?></p>
                    <!-- <a class="button" href="<?php echo esc_url( $button_link ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $button_text ); ?></a> -->
                </div>
                <?php else: ?>
                <div class="content context_info" data-aos="fade-right">
                    <h3><?php echo esc_html( $sub_title ); ?></h3>
                    <h2><?php echo esc_html( $title ); ?></h2>
                    <p><?php echo wp_kses_post( $content ); ?></p>
                    <!-- <a class="button" href="<?php echo esc_url( $button_link ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $button_text ); ?></a> -->
                </div>
                <div class="content content_image" data-aos="fade-left">
                    <?php sf_picture( $image, [ 'alt' => $title ] ); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php
                $counter++;
            endforeach;
?>
            <div class="service_support" data-aos="fade-up">
                <h3><?php echo esc_html( $service_support['sub_title'] ); ?></h3>
                <h2><?php echo esc_html( $service_support['title'] ); ?></h2>
                <p>
                    <?php echo wp_kses_post( $service_support['content'] ); ?>
                </p>
                <!-- <a class="button" href="<?php echo esc_url( $service_support_button_link ); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo esc_html( $service_support_button_text ); ?>
                </a> -->
            </div>
        </div>
    </div>
</div>
