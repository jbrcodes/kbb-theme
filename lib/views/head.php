<?php $turi = $this->getThemeUri() ?>
<head>
  <meta charset="<?php bloginfo( 'charset' ) ?>">
  <title><?= $this->getHeadTitle() ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="<?= $turi ?>/images/favicon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= $turi ?>/css/w3.css">
  <link rel="stylesheet" href="<?= $turi ?>/css/w3-theme-teal.css">
  <link rel="stylesheet" href="<?= $turi ?>/css/kbb.css">
  <?php wp_head() ?>
</head>