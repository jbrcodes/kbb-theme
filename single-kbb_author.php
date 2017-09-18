<?php
  $poge = $T->getNextPost();
  $imgUrl = $T->getCptImgUrl($poge['type'], $poge['slug']);
  $T->renderView('layoutBeforeContent');    
?>

<!-- single-kbb_author.php -->
<main>

  <article class="cpt-detail w3-container">
    <h1><?= $poge['title'] ?></h1>
    <img src="<?= $imgUrl ?>" alt="<?= $poge['title'] ?>" class="w3-image w3-left w3-card-4">
    <?= $poge['content'] ?>
  </article>

</main>

<?php
  $T->renderView('postAdjPostLinks');
  $T->renderView('layoutAfterContent');
?>