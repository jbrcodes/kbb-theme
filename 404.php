<?php
  $title = __('Page Not Found', 'kbb');
  $T->setDocTitle($title);
  $T->renderView('layoutBeforeContent');
?>

<!-- 404.php -->
<main>
  
  <div class="w3-container">
    <h1><?= $title ?></h1>
    <p><?= __("Sorry, but that page doesn't seem to exist.", 'kbb') ?></p>
  </div>

</main>

<?php $T->renderView('layoutAfterContent') ?>