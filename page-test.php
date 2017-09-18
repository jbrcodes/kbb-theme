<?php
  $poge = $T->getPage();
  $T->renderView('layoutBeforeContent');
?>

<!-- page-test.php -->
<article class="w3-container">
  <h1><?= $poge['title'] ?></h1>
  <?= $poge['content'] ?>
</article>


<div class="w3-container w3-border w3-margin">
  <h4>Testing Zone</h4>

  <table border="1" cellspacing="0">
  <?php
    $rules = $wp_rewrite->rewrite_rules();
    $pat = '(authors|autores|books|libros|collaborators|colaboradores|news|noticias)';
    foreach ($rules as $key => $val) {
      #if ( preg_match("!^$pat!", $key) )
      if ( preg_match("!kbb_!", $val) || preg_match("!(noticias)!", $key) )
        printf("<tr><td>%s</td><td>%s</td></tr>\n", $key, $val);
    }
  ?>
  </table>

</div>

<?php $T->renderView('layoutAfterContent') ?>