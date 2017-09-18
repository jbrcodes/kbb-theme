<?php

require 'BaseTheme/BaseTheme.php';

class KbbTheme extends BaseTheme {
    
  private $enSlugs = [];
  private $lang = '';
  
  # --- constructor ---

  function __construct() {
    parent::__construct();

    $this->lang = apply_filters('wpml_current_language', 'en');
    
    # Required to work with menus on the admin site
    $menus = [
      'menu-primary' => 'Primary Menu',
      'menu-secondary' => 'Secondary Menu'
    ];
    $this->registerMenus($menus);
    
    $this->registerFilters();
    
    # Allow featured images (news only)
    # (Hmmm... this is only necessary for the admin)
    add_theme_support('post-thumbnails', ['post']);
    
    # Load language file
    add_action('after_setup_theme', [$this, 'loadLanguageFile']);
  }

  # ---------------------------------------------------------------------------
  # filters
  # ---------------------------------------------------------------------------
  
  public function registerFilters() {
    add_action('get_the_date', [$this, 'dateFilter'], 10, 3);
  }
  
  #
  # Convert date from "March 27, 2015" to "March 2015"
  #
  
  public function dateFilter($theDate, $d, $post) {
    $postId = is_int($post) ? $post : $post->ID;
    if (get_post_type($postId) === 'post') {
      $newDate = preg_replace('!\d+,\s!', '', $theDate);
    } else {
      $newDate = $theDate;
    }
    
    return $newDate;
  }
  
  # ---------------------------------------------------------------------------
  # action/filter callbacks
  # ---------------------------------------------------------------------------
  
  public function loadLanguageFile() {
    $foo = load_theme_textdomain('kbb', get_template_directory() . '/languages');

    # __() only works once the lang file has been loaded...
    $this->setHeadTitlePattern('%s | ' . __("Kids' Books Bolivia", 'kbb'));
  }
  
  # ---------------------------------------------------------------------------
  # custom post types
  # ---------------------------------------------------------------------------
  
  #
  # Return CPT info for $url. Ack.
  #

  function getUrlCptInfo($url) {
    $type = '';
    if ( preg_match("!/(authors|autores)/?(.*)$!", $url, $toks) ) {
      $type = 'kbb_author';
    } elseif ( preg_match("!/(books|libros)/?(.*)$!", $url, $toks) ) {
      $type = 'kbb_book';
    } elseif ( preg_match("!/(collaborators|colaboradores)/?(.*)$!", $url, $toks) ) {
      $type = 'kbb_collaborator';
    } 
    
    $info = [
      'isCptType'   => ($type !== ''),
      'cptType'     => $type,
      'isCptIndex'  => ($type !== '') && ($toks[2] === ''),
      'slug'        => ''
    ];
    
    if (count($toks) == 3) {
      preg_match('!/([^/]+)/?$!', $url, $toks);
      $info['slug'] = $toks[1];
    }
    
    return $info;
  }
  
  public function getCptImgUrl($cptype, $slug) {
    if ($this->lang === 'es') {
      if ($this->enSlugs) {
        $enSlug = $this->enSlugs[$slug];
      } else {
        $enSlug = $this->getEnSlug($cptype, $slug);
      }
    } else {
      $enSlug = $slug;
    }
    $prefix = ($cptype === 'kbb_book') ? 'bo' : 'au';
    $imgUrl = sprintf("%s/%s_%s.jpg", $this->uploadUrl, $prefix, $enSlug);
    
    # Check for possible missing person picture
    if ($cptype !== 'kbb_book') {
      $imgPath = sprintf("%s/au_%s.jpg", $this->uploadPath, $enSlug);
      if ( !file_exists($imgPath) )
        $imgUrl = sprintf("%s/images/author_thumb_anon.jpg", $this->getThemeUri());
    }
    
    return $imgUrl;
  }
  
  public function renderBookMatrix() {
    if ($this->lang === 'es')
      $this->enSlugs = $this->_loadEnSlugs('kbb_book');
    
    echo '<ol id="cpt-list" class="w3-row">', "\n";
    
    $wpResult = $this->queryBooks();
    while ( $wpResult->have_posts() ) {
      $wpResult->the_post();
      $poge = $this->convertPoge(null, true);
      
      # Make the $misc dict for stuff that isn't really part of $poge
      $misc = [];
      $misc['imgUrl'] = $this->getCptImgUrl('kbb_book', $poge['slug']);
      $misc['semester'] = $this->_makeSemester($poge);
      $misc['personInfo'] = $this->_extractPersonInfo($poge['content']);
      
      $this->_loadView('bookIndexItem', ['poge' => $poge, 'misc' => $misc]);
    }

    echo "</ol> <!-- #cpt-list -->\n";
  }
  
  public function renderPersonMatrix($cptype) {
    echo '<ol id="cpt-list" class="w3-row">', "\n";

    $wpResult = $this->queryPersons($cptype);
    while ( $wpResult->have_posts() ) {
      $wpResult->the_post();
      $poge = $this->convertPoge(null, true);
      
      # Make the $misc dict for stuff that isn't really part of $poge
      $misc = [];
      $misc['imgUrl'] = $this->getCptImgUrl($cptype, $poge['slug']);
      $misc['bookLinks'] = $this->_extractBookLinks($poge['content']);
      
      $this->_loadView('personIndexItem', ['poge' => $poge, 'misc' => $misc]);
    }
    
    echo "</ol> <!-- #cpt-list -->\n";
  }
  
  private function _extractPersonInfo($content) {
    preg_match('!^(.*)</p>!', $content, $toks);				# get first para
    preg_match_all("!(<a .*?</a>)!", $toks[1], $toks1);		# find links
    $links = $toks1[0];
    $linkstr = __('By', 'kbb') . ' ' . $links[0];
    if (count($links) == 2)
        $linkstr .= ' ' . __('and', 'kbb') . ' ' . $links[1];

    return $linkstr;
  }
  
  private function _makeSemester($poge) {
    preg_match('!(\d{4})-(\d{2})!', $poge['customFields']['kbb_sort_semester'][0], $toks);
    $seasonEN = ($toks[2] === '03') ? 'Spring' : 'Fall';
    
    return __($seasonEN, 'kbb') . ' ' . $toks[1];
  }
  
  private function _extractBookLinks($content) {
      preg_match('!^(.*)</p>!', $content, $toks);				# get first para
      preg_match_all("!(<a .*?</a>)!", $toks[1], $toks1);		# find links
      $links = $toks1[0];
      $linkstr = $links[0];
      for ($i=1; $i<count($links); $i++)
          $linkstr .= ', ' . $links[$i];

      return $linkstr;
  }
  
  public function queryBooks($limit = 0) { 
    $args = [
      'post_type' => 'kbb_book',
      'post_status' => 'publish',
      'posts_per_page' => ($limit > 0) ? $limit : -1
    ];
      
    return $this->queryPosts($args);
  }
  
  public function queryPersons($cptype, $limit = 0) {
    $args = [
      'post_type' => $cptype,
      'post_status' => 'publish',
      'posts_per_page' => ($limit > 0) ? $limit : -1
    ];
      
    return $this->queryPosts($args);
  }
  
  #
  # Intercept BaseTheme::queryPosts() and add sort info for CPTs.
  # Returns $wpResult.
  #
  
  public function queryPosts($args) {
    if ( isset($args['post_type']) && $this->isaCpt($args['post_type']) ) {
      $saArgs = Jbr_CptSortAssistant::GetSortArgs($args['post_type']);
      $args = array_merge($args, $saArgs);
    }
    
    return parent::queryPosts($args);
  }
  
  # ---------------------------------------------------------------------------
  # primary menu stuff...
  # ---------------------------------------------------------------------------
  
  #
  # Build and return the HTML for a menu. This is specific to W3.CSS.
  # Accept these optional (CSS) args: divId, divClass, aClass
  #

  public function makeMenuHtml($jbrItems, $args = []) {
    $html = '<div';
    if ( isset($args['divId']) )
      $html .= sprintf(' id="%s"', $args['divId']);
    if ( isset($args['divClass']) )
      $html .= sprintf(' class="%s"', $args['divClass']);
    $html .= ">\n";

    if ( isset($args['aClass']) )
      $aCl = sprintf(' class="%s"', $args['aClass']);
    else
      $aCl = '';

    foreach ($jbrItems as $item) {
      $html .= sprintf('  <a href="%s"%s>%s</a>', 
        $item['url'], $aCl, $item['label']);
      $html .= "\n";
    }
    $html .= "</div>";

    return $html;
  }
  
  #
  # Be really clever and insert the 'selected' class into the appropriate <a>
  #
  
  public function selectMenuItem($menuHtml) {
    $reqUri = $_SERVER['REQUEST_URI'];
    if ($reqUri === '/') {
      $siteUrl = get_bloginfo('url');
      $menuHtml = preg_replace(
        "!(<a href=\"$siteUrl/?\" class=\")!",
        '\1selected ', 
        $menuHtml);
    } else {
      $reqUri = preg_replace('!(/\w+/).*!', '\1', $reqUri);
      $menuHtml = preg_replace(
        "!(href=\".*?$reqUri\" class=\")!",
        '\1selected ',
        $menuHtml
      );
    }

    return $menuHtml;
  }
  
  # ---------------------------------------------------------------------------
  # WPML
  # ---------------------------------------------------------------------------
  
  private function _loadEnSlugs($cptype) {
    global $wpdb;
    
    # (Is there a prettier way to do this?)
    $sql = <<<EOD
      SELECT p_en.post_name AS slug_en, p_es.post_name AS slug_es
      FROM wp_posts p_en, wp_posts p_es, 
        wp_icl_translations t_en, wp_icl_translations t_es
      WHERE p_en.post_type = '$cptype' AND p_en.post_status = 'publish'
          AND p_en.ID = t_en.element_id AND t_en.language_code = 'en'
          AND t_en.trid = t_es.trid AND t_es.language_code = 'es'
          AND t_es.element_id = p_es.ID
EOD;
    
    $results = $wpdb->get_results($sql, ARRAY_A);
    $slugDict = [];
    foreach ($results as $row)
      $slugDict[ $row['slug_es'] ] = $row['slug_en'];
    
    return $slugDict;
  }
  
  public function getEnSlug($type, $esSlug) {
    global $wpdb;
    
    # (Is there a prettier way to do this?)
    $sql = <<<EOD
      SELECT p_en.post_name
      FROM wp_posts p_en, wp_posts p_es, 
        wp_icl_translations t_en, wp_icl_translations t_es
      WHERE p_es.post_name = '$esSlug' AND p_es.post_type = '$type'
          AND p_es.ID = t_es.element_id AND t_es.language_code = 'es'
          AND t_es.trid = t_en.trid AND t_en.language_code = 'en'
          AND t_en.element_id = p_en.ID
EOD;
    
    $row = $wpdb->get_row($sql, ARRAY_A);
    
    return $row['post_name'];
  }
  
  # ---------------------------------------------------------------------------
  # adjacent post links for CPTs
  # ---------------------------------------------------------------------------
  
  protected function _getAdjacentPostLink($direction, $myfmt) {
    # Handle non-CPT (i.e. News) case first
    $ptype = get_post_type();
    if ( !$this->isaCpt($ptype) ) {
      $a = parent::_getAdjacentPostLink($direction, $myfmt);
      
      return $a;
    }

    # OK, it's a CPT; use my sorting assistant to find the post
    if ($direction === 'next')
      $postId = Jbr_CptSortAssistant::GetNextPostId($this->lang);
    else
      $postId = Jbr_CptSortAssistant::GetPreviousPostId($this->lang);
    
    # If found, "design" the link
    $a = '';
    if ($postId) {
      $post = get_post($postId);
      $url = apply_filters('the_permalink', get_permalink($post));
      $title = apply_filters('the_title', $post->post_title);
      $a = parent::_buildBetterAdjacentPostLink($myfmt, $url, $title);
    }

    return $a;  
  }
  
  # ---------------------------------------------------------------------------
  # featured image (for news/posts only)
  # ---------------------------------------------------------------------------
  
  public function getFeaturedImage($size='thumbnail') {
    if ( !has_post_thumbnail() )
      return '';
    
    # Find the URL; we'll discard the rest
    $tag = get_the_post_thumbnail(null, $size);
    preg_match('!src="(.*?)"!', $tag, $toks);
    $url = $toks[1];
    
    # Create our own tag
    $tag = sprintf('<img class="featured" src="%s" alt="">', $url);
    
    return $tag;   
  }
  
  # ---------------------------------------------------------------------------
  # Multilingual
  # ---------------------------------------------------------------------------
  
  public function getCurrentLang() {
    return $this->lang;
  }
  
  # ---------------------------------------------------------------------------
  # helpers
  # ---------------------------------------------------------------------------
  
  #
  # Replace (optional) "[...]" at end of excerpt with "more..." button
  #
  
  public function addMoreButton($excerpt, $perma) {
    $aClass = 'kbb-more w3-btn w3-small w3-round w3-teal';
    $but = sprintf('<a class="%s" href="%s">%s</a>', $aClass, $perma, __('more', 'kbb'));
    $newEx = preg_replace('!\s*\[&hellip;]\s*$!', "&hellip; $but", $excerpt);
    
    return $newEx;
  }
  
  public function isaCpt($ptype) {
    return (strncmp('kbb_', $ptype, 4) == 0);
  }

}

#?>
