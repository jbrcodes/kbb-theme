<div class="w3-panel">
  <div id="post-paging-links" class="prev-next-links w3-border-top" style="padding-top:8px">
    <?php

      $classes = 'w3-btn w3-round w3-teal';
    
      $prevLink = get_next_posts_link('&larr;');
      if ($prevLink) {
        $prevLink = preg_replace('!href!', "class=\"$classes\" href", $prevLink);
        printf('<span>%s %s</span>', $prevLink, __('Older news', 'kbb'));
      }

      $nextLink = get_previous_posts_link('&rarr;');
      if ($nextLink) {
        $nextLink = preg_replace('!href!', "class=\"$classes\" href", $nextLink);
        printf('<span class="w3-right">%s %s</span> ', __('More recent news', 'kbb'), $nextLink); 
      }

    ?>

  </div><!-- #post-paging-links -->
</div>
