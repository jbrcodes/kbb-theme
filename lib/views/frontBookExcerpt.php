<!-- views/frontBookExcerpt.php -->
<?php
  $imgUrl = $this->getCptImgUrl($poge['type'], $poge['slug']);
?>
<div class="front-book-excerpt w3-clear w3-border-top w3-margin-bottom">
  <h3 class="w3-text-teal"><a href="<?= $poge['permalink'] ?>"><?= $poge['title'] ?></a></h3>
  <img class="w3-left" src="<?= $imgUrl ?>" alt="<?= $poge['title'] ?>">
  <div class="content">
    <?= $this->addMoreButton($poge['excerpt'], $poge['permalink']) ?>
  </div> <!-- .content -->
</div> <!-- .front-book-excerpt -->