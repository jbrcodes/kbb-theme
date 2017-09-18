<div class="w3-panel">
  <!-- (Kindof a hack to get the right top border, but what ya gonna do?) -->
  <div id="post-adj-post-links" class="prev-next-links w3-row w3-border-top" style="padding-top:8px">
  <?php

    # Build links "my way"!
    
    # Output 'prev' link/button
    $myfmt = '<a href="%url" class="w3-btn w3-round w3-teal">&larr;</a> %title';
    $prevLink = $this->getPreviousPostLink($myfmt);
    if ($prevLink === '')
      $prevLink = '&nbsp;';
    echo '<span class="w3-col m5">', $prevLink, '</span>';

    # Output a button to return to the index. News is a special case.
    # (This is a bit of a hack...)
    if (get_post_type() === 'post')
      $url = '../../..';
    else
      $url = '..';
    echo '<span class="w3-col m2 w3-center">';
    printf('<a class="w3-btn w3-round w3-teal" href="%s">%s</a>', $url, __('index', 'kbb'));
    echo '</span>';

    # Output 'next' link/button
    $myfmt = '%title <a href="%url" class="w3-btn w3-round w3-teal">&rarr;</a>';
    $nextLink = $this->getNextPostLink($myfmt);
    if ($nextLink === '')
      $nextLink = '&nbsp;';
    echo '<span class="w3-col m5 w3-right-align">', $nextLink, '</span>';

  ?>

  </div><!-- #post-adj-post-links -->
</div>
