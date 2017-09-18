<?php
  $poge = $T->getPage();
  $T->renderView('layoutBeforeContent');
?>

<!-- page.php -->
<main>

  <article class="w3-container">
    
    <h1><?= htmlentities($poge['title']) ?></h1>
    <?= $poge['content'] ?>

    <?php

      #
      # If this is a CPT index page, print the corresponding items
      #

      $info = $T->getUrlCptInfo( get_permalink() );
      if ( $info['isCptIndex'] ) {
        if ($info['cptType'] === 'kbb_book')
          $T->renderBookMatrix();
        else
          $T->renderPersonMatrix($info['cptType']);      
      }

    ?>
  
  </article>
  
</main>

<?php $T->renderView('layoutAfterContent') ?>