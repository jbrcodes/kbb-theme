<?php
  $poge = $T->getNextPost();
  $T->renderView('layoutBeforeContent'); 
  $img = $T->getFeaturedImage('full');
?>

<!-- single.php -->
<main>

  <article class="news w3-container">
    <h1><?= $poge['title'] ?></h1>
    <?= $img ?>
    <div class="date"><?= $poge['date'] ?></div>
    <?= $poge['content'] ?>
  </article>
  
</main>

<?php
  $T->renderView('postAdjPostLinks');
  $T->renderView('layoutAfterContent');
?>