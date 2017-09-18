<?php $logoUrl = sprintf('%s/images/logo_%s.png', get_template_directory_uri(), $this->getCurrentLang()) ?>
<header class="w3-teal">
  <div class="w3-container w3-row w3-padding-16" >
    <div id="kbb-logo" class="w3-half"><img src="<?= $logoUrl ?>" alt="logo"></div>
    <div class="w3-half">
      <?php $this->renderView('langToggle') ?>
    </div>  
  </div>
  <?php $this->renderView('menuPrimaryCollapsible') ?>
</header>