<?php
$features = get_field('tr_features', 'option');
$service_support = get_field('tr_service_support', 'option');
$service_support_button = $service_support['button'];
$service_support_button_text = $service_support_button['text'];
$service_support_button_link = $service_support_button['link'];
$counter = 0;
?>

<div class="salefish_features" id="features"> 
    <div class="container">
        <div class="header" data-aos="fade-up">
            <h1>SaleFish Özellikleri</h1>
            <p>Günümüz pazarında satmanız gereken tüm araçlar.</p>
        </div>
        <div class="features">
            <?php foreach($features as $row):
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
                    <img src="<?php echo $image; ?>">
                </div>
                <div class="content context_info" data-aos="fade-left">
                    <h3><?php echo $sub_title; ?></h3>
                    <h2><?php echo $title; ?></h2>
                    <p><?php echo $content; ?></p>
                    <!-- <a class="button" href="<?php echo $button_link; ?>" target="_blank" rel="noopener noreferrer"><?php echo $button_text; ?></a> -->
                </div>
                <?php else: ?>
                <div class="content context_info" data-aos="fade-right">
                    <h3><?php echo $sub_title; ?></h3>
                    <h2><?php echo $title; ?></h2>
                    <p><?php echo $content; ?></p>
                    <!-- <a class="button" href="<?php echo $button_link; ?>" target="_blank" rel="noopener noreferrer"><?php echo $button_text; ?></a> -->
                </div>
                <div class="content content_image" data-aos="fade-left">
                    <img src="<?php echo $image; ?>">
                </div>
                <?php endif; ?>
            </div>
            <?php
                $counter++;
            endforeach;
?>
            <div class="service_support" data-aos="fade-up">
                <h3><?php echo $service_support['sub_title']; ?></h3>
                <h2><?php echo $service_support['title']; ?></h2>
                <p>
                    <?php echo $service_support['content']; ?>
                </p>
                <!-- <a class="button" href="<?php echo $service_support_button_link; ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo $service_support_button_text; ?>
                </a> -->
            </div>
        </div>
    </div>
</div>