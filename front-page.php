<?php
  
  $booksUrl = get_permalink( get_page_by_path('books') );
  $newsUrl = get_permalink( get_page_by_path('news') );

  $T->setDocTitle( __('Home', 'kbb') );
  $T->renderView('layoutBeforeContent');

?>

<main>

  <div id="front-banner"></div>

  <section id="front-blurb" class="w3-container w3-teal">
    <?php
      $page = $T->getPageBySlug('_front-page-blurb-' . $T->getCurrentLang());
      $T->renderView('frontPageBlurb', ['page' => $page]);
    ?>
  </section>

  <div id="front-latest" class="w3-row w3-section">
    <section class="w3-container w3-half">
      <h2><?= __('Our Latest News', 'kbb') ?></h2>
      <?php $T->renderRecentPosts('post', 3, 'postExcerpt') ?>
      <a class="w3-btn w3-round w3-right w3-teal" href="<?= $newsUrl ?>"><?= __('More news', 'kbb') ?></a>
    </section>
    <section class="w3-container w3-half">
      <h2><?= __('Our Latest Books', 'kbb') ?></h2>
      <?php $T->renderRecentPosts('kbb_book', 3, 'frontBookExcerpt') ?>
      <a class="w3-btn w3-round w3-right w3-teal" href="<?= $booksUrl ?>"><?= __('More books', 'kbb') ?></a>
    </section>
  </div>
  
</main>

<?php $T->renderView('layoutAfterContent') ?>