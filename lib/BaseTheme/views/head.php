<?php $turi = $this->getThemeUri() ?>
<head>
  <meta charset="<?php bloginfo( 'charset' ) ?>">
  <title><?= htmlentities($this->getHeadTitle()) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head() ?>

</head>
