<!-- views/postExcerpt.php -->
<div class="w3-clear w3-border-top w3-margin-bottom">
  <h3 class="w3-text-teal"><a href="<?= $poge['permalink'] ?>"><?= $poge['title'] ?></a></h3>
  <?php
    $imgTag = $this->getFeaturedImage('thumbnail');
    if ($imgTag) {
      echo '<div class="thumb-wrapper">', $imgTag, '</div>';
    }
  ?>
  <div class="date"><?= $poge['date'] ?></div>
  <div class="content">
    <?= $this->addMoreButton($poge['excerpt'], $poge['permalink']) ?>
  </div> <!-- .content -->
</div> <!-- .post-excerpt -->