<div id="lang-toggle">
  <?php

    # File: themes/kbb/views/langToggle.php

    # Get the language menu stuff
    $langs = apply_filters('wpml_active_languages', NULL);
  
    # No WPML found? Abort!
    if (!$langs) {
      echo "</div>\n";
      return;
    }

    $cl = 'w3-btn w3-round w3-theme-d2 w3-small w3-padding-small';
    
    #
    # Create enabled/selected/disabled 'Español' button
    #
  
    if ( array_key_exists('es', $langs) ) {
      if ($langs['es']['active']) {
        $esBut = sprintf('<span class="%s selected">%s</span>', 
          $cl, $langs['es']['native_name']);
      } else {
        $t = 'Libros Infantiles Bolivia en español';
        $u = $langs['es']['url'];
        $esBut = sprintf('<a href="%s" class="%s" title="%s">Español</a>',
          $u, $cl, $t);
      }
    } else {
      # No translation
      $esBut = sprintf('<span class="%s w3-disabled">Español</span>', $cl);
    }
    
    #
    # Create enabled/selected/disabled 'English' button
    #
  
    if ( array_key_exists('en', $langs) ) {
      if ($langs['en']['active']) {
        $enBut = sprintf('<span class="%s selected">%s</span>', 
          $cl, $langs['en']['native_name']);
      } else {
        $t = "Kids' Books Bolivia in English";
        $u = $langs['en']['url'];
        $enBut = sprintf('<a href="%s" class="%s" title="%s">English</a>',
          $u, $cl, $t);
      }
    } else {
      # No translation
      $enBut = sprintf('<span class="%s w3-disabled">English</span>', $cl);
    }
  
    # Spit 'em out
    echo $esBut, $enBut, "\n";
  
  ?>
</div>
