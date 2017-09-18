<?php
  $title = get_the_archive_title();
  # By default WP uses the title of the first article. Yech.
  $T->setDocTitle($title);
  $T->renderView('layoutBeforeContent');
?>

<!-- archive.php -->
<section class="w3-container">
  <h1><?= $title ?></h1>
  <?php
    $postCount = $T->getPostCount();
    for ($i=0; $i<$postCount; $i++) {
        $poge = $T->getNextPost();
        $T->renderView('postComplete', ['poge' => $poge]);
    }
  ?>
  
</section>

<?php $T->renderView('newsPagingLinks') ?>
<?php $T->renderView('layoutAfterContent') ?>