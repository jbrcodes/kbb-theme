<?php
    $T->setDocTitle( __('News', 'kbb') );
    $T->renderView('layoutBeforeContent');
?>

<!-- index.php; the news/posts page -->
<main>

  <section id="news-index" class="w3-container">
    <h1><?= __('News', 'kbb') ?></h1>
    <?php
      $postCount = $T->getPostCount();
      for ($i=0; $i<$postCount; $i++) {
          $poge = $T->getNextPost();
          $T->renderView('postExcerpt', ['poge' => $poge]);
      }
    ?>
  </section>

</main>

<?php $T->renderView('postPagingLinks') ?>
<?php $T->renderView('layoutAfterContent') ?>