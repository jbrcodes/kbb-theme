<footer class="w3-orange">
  
  <div id="footer-widgets" class="w3-row-padding w3-padding-8">

    <div id="footer-widget-1" class="w3-quarter w3-hide-small">
      <img src="/wp-content/themes/kbb/images/kids.jpg" alt="" class="w3-image">
    </div>

    <div id="footer-widget-2" class="w3-quarter">
      <nav id="menu-secondary">
        <?php wp_nav_menu( ['menu' => 'menu-secondary'] ) ?>
      </nav>
    </div>
  
    <div id="footer-widget-3" class="w3-quarter w3-hide-small">
      <img src="/wp-content/themes/kbb/images/hill_purple.jpg" alt="" class="w3-image">
    </div>
    
    <div id="footer-widget-4" class="w3-quarter">
      <div id="social-media">
        <a href="http://www.facebook.com/pages/Kids-Books-Bolivia/185541438186758" target="_blank" title="Facebook page"><i class="fa fa-facebook-square"></i></a>
        <a href="<?php bloginfo('rss2_url') ?>" target="_blank" title="RSS feed"><i class="fa fa-rss-square"></i></a>
      </div>
    </div>
    
  </div>
  
  <div id="footer-credits" class="w3-center w3-padding">
    <?php
      printf('2009&ndash;%d &copy; %s', date("Y"), __("Kids' Books Bolivia", 'kbb'));
      echo '<span>|</span>';
      $wp_link = '<a href="http://wordpress.org">WordPress</a>';
      $wpml_link = '<a href="http://wpml.org">WPML</a>';
      printf('%s %s %s %s', 
        __('Powered by', 'kbb'), $wp_link, __('and', 'kbb'), $wpml_link);
      echo '<span>|</span>';
      wp_loginout();
    ?>
  </div>
  
</footer>