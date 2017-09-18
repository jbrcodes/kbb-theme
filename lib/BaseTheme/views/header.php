<?php $info = $this->getSiteInfo() ?>
<header>
  <div style="font-size: 30px"><?= $info['name'] ?></div>
  <div><?= $info['description'] ?></div>
  <?php $this->renderView('menuPrimary') ?>
</header>
