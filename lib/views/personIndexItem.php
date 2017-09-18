<?php
  # Dicts passed: $poge, $misc
?>

<!-- views/personIndexItem.php -->
<li class="w3-col m6 l4 w3-padding">
  <div class="cpt-image">
    <a href="<?= $poge['permalink'] ?>">
      <img src="<?= $misc['imgUrl'] ?>" alt="<?= $poge['title'] ?>" class="w3-image w3-card-4">
    </a>
  </div>
  <div class="cpt-text">
    <a href="<?= $poge['permalink'] ?>"><h2><?= $poge['title'] ?></h2></a>
    <div><?= $misc['bookLinks'] ?></div>
  </div>
</li>
