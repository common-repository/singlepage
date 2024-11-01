<!DOCTYPE html>
<html lang="en-US" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width minimum-scale=1.0 maximum-scale=1.0 user-scalable=no" />

    <title>Singlepage Website</title>
    <?php wp_head();?>
    <?php global $wpdb;?>
    
    <?php  $options = get_option( 'ps_settings' ); ?>
</head>
<!-- Fetch css -->
<style>
<?php echo $options["additionalcss_url"];?>
</style>
<body>
    <main class="main onepagescroll_outer_wrapper" id="top">
      <a id="button"></a>
        <nav class="navbar navbar-expand-lg navbar-light sticky-top" data-navbar-on-scroll="data-navbar-on-scroll">
            <!-- Fetch logo -->
            <div class="container"><a class="navbar-brand" href="#">
                    <h4><?php if(!empty(get_option('logo'))){ ?><img src="<?php echo get_option('logo'); ?>"> <?php } ?></h4></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <!-- Fetch title -->
                        <?php 
                            global $wpdb; 
                            $table = $wpdb->prefix.'onepagedata';
                            $i = 0;
                            $result = $wpdb->get_results("SELECT title FROM $table Where checkmenu !=0 ORDER BY ordering ASC");
                            
                            foreach($result as $res)
                            { 
                                $i++;  ?>

                        <li class="nav-item"><a class="nav-link" aria-current="page"
                                href="#<?php echo $res->title;?>-section"><?php echo $res->title;?></a></li>
                        <?php
                        if($i != count($result))
                        { ?>
                        <li class="nav-item"> <a class="nav-link line">|</a></li>
                        <?php } }?>
                    </ul>
                    <div class="d-flex ms-lg-4">
                        <?php $checked = '';
                            $options = get_option('ps_settings'); 
                            if(!empty($options["chk7"])){
                        ?>
                        <a class="btn btn-warning me-3 btn-sm" href="<?php echo esc_url($options["rightbtn_url"]); ?>"
                            role="button"><?php echo wp_kses_post($options["ps_text_rightbtn"]); ?></a>
                        <?php }?>
                    </div>
                </div>
            </div>
        </nav>

        <div class="clearfix"></div>
        <!-- Fetch description -->
        <?php
            global $wpdb; 
            $table = $wpdb->prefix.'onepagedata';
            $result = $wpdb->get_results('SELECT title,desc_editor,	ordering FROM '.$table.' ORDER BY ordering ASC');
    
            for($i=0;$i<count($result);$i++)
            {
            echo wp_kses_post('<section id="'.$result[$i]->title.'-section" class="pt-5">');
            echo wp_kses_post(stripslashes(html_entity_decode($result[$i]->desc_editor)));
            echo wp_kses_post('</section>');
            }
		?>
        </section>
            <!-- End content -->
        <section class="footer">
            <div class="bg-holder z-index--1 bottom-0 d-lg-block background-position-top"
                style="background-image:url(<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/superhero/oval.png'; ?>);opacity:.5; background-position: top !important ;">
            </div>
            <div class="container">
                <div class="row">
                    <!-- Fetch footer logo and url of icons -->
                    <?php $options = get_option( 'ps_settings' ); ?>
                    <div class="social-links">
                        <?php 
                            $checked = '';
                            $options = get_option( 'ps_settings' ); 
                            if(!empty($options["chk"])){
                        ?>
                        <a href=" <?php echo esc_url($options["facebook_url"]); ?>" class="social-box"> 
                        <i class="fa-brands fa-facebook-f"></i></a>
                        <?php }?>

                        <?php   
                            $checked = '';
                            $options = get_option( 'ps_settings' ); 
                            if(!empty($options["chk1"])){
                        ?>
                        <a href="<?php echo esc_url($options["linkedin_url"]); ?>" class="social-box"> 
                        <i class="fa-brands fa-linkedin-in"></i></a>
                        <?php }?>

                        <?php 
                            $checked = '';
                            $options = get_option( 'ps_settings' ); 
                            if(!empty($options["chk2"])){
                        ?>
                        <a href="<?php echo esc_url($options["twitter_url"]); ?>" class="social-box"> 
                        <i class="fa-brands fa-twitter"></i></a>
                        <?php }?>

                        <?php 
                            $checked = '';
                            $options = get_option( 'ps_settings' ); 
                            if(!empty($options["chk3"])){
                        ?>
                        <a href="<?php echo esc_url($options["github_url"]); ?>" class="social-box"> 
                        <i class="fa-brands fa-github"></i></a>
                        <?php }?>

                        <?php 
                            $checked = '';
                            $options = get_option( 'ps_settings' ); 
                            if(!empty($options["chk4"])){
                         ?>
                        <a href="<?php echo esc_url($options["pinterest_url"]); ?>" class="social-box"> 
                        <i class="fa-brands fa-pinterest"></i></a>
                        <?php }?>
                        <?php 
                            $checked = '';
                            $options = get_option( 'ps_settings' ); 
                            if(!empty($options["chk5"])){
                         ?>
                        <a href="<?php echo esc_url($options["instagram_url"]); ?>" class="social-box"> 
                        <i class="fa-brands fa-instagram"></i></a>
                        <?php }?>
                    </div>
                        <?php 
                            $checked = '';
                            $options = get_option( 'ps_settings' ); 
                            if(!empty($options["chk6"])){
                         ?>
                    <p class="text-center mt-3"><a href="<?php echo esc_url($options["copytext_url"]); ?>"><?php echo esc_html($options["ps_text_btn"]); ?></a></p>
                    <?php }?>               
                    <?php 
                     if(!empty(get_option('logo'))){    
                     ?>
                    <img src="<?php echo wp_kses_post(get_option('logo')); ?>" class="footer-logo">
                    <?php } ?>                                                         
                                
                </div>
        </section>
    </main>
   <!-- Scroll functionality -->
    <script>
    var btn = $('#button');
    $(window).scroll(function() {
        if ($(window).scrollTop() > 300) {
            btn.addClass('show');
        } else {
            btn.removeClass('show');
        }
    });
    btn.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, '300');
    });
    var title = $('.nav-link');
    title.on('click', function(e) {
        var value = $( this ).attr("href");
        if (window.matchMedia('(max-width: 991px)').matches)
            {
                $('html,body').animate({scrollTop: $(value).offset().top-10}, 50);
            }        
     });
    </script>
    
   <?php wp_footer();?>
</body>

</html>

