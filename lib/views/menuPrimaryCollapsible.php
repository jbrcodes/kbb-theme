<nav id="menu-primary" class="w3-theme-d1">
  <?php

    #
    # Step I: Menu for tablets and laptops
    #

    $items = $this->getMenuItems('menu-primary');

    # Get the (W3.CSS-specific) HTML for the menu, with appropriate classes added
    $args = [
      'divClass' => 'w3-bar',
      'aClass' => 'w3-bar-item w3-button w3-hide-small'
    ];
    $html = $this->makeMenuHtml($items, $args);
    # Remove 'w3-hide-small' from first <a> (Home) so it'll show on phones too
    $html = preg_replace('! w3-hide-small!', '', $html, 1);
    # Select the proper item
    $html = $this->selectMenuItem($html);
    
    # Append the "bars/burger" icon to show on phones
    $aClass = 'w3-bar-item w3-button w3-right w3-hide-medium w3-hide-large';
    $bars = '<i class="fa fa-bars"></i>';
    $a = sprintf('<a href="javascript:void(0)" class="%s" onclick="kbb_toggleSmallMenu()">%s</a>', $aClass, $bars);
    $html = preg_replace('!(</div>)!', "$a$1", $html);

    echo $html, "\n";

    #
    # Step II: Menu for phones
    #

    # Remove Home from small menu; it's still there in other one
    array_shift($items);
  
    $args = [
      'divId' => 'menu-primary-small',
      'divClass' => 'w3-bar-block w3-hide w3-hide-medium w3-hide-large',
      'aClass' => 'w3-bar-item w3-button'
    ];
    $html = $this->makeMenuHtml($items, $args);
    # Select the proper item
    $html = $this->selectMenuItem($html);
  
    echo $html;

  ?>
  
</nav>
