<?php

class BaseTheme {

    # -------------------------------------------------------------------------
    # properties (instance variables)
    # -------------------------------------------------------------------------
    
    private $viewDirs = [];
    protected $uploadUrl = '';
    protected $uploadPath = '';
    private $headTitlePattern = '';
    private $docTitle = '';
    private $posttypeObjs = null;
    private $widgetAreas = null;
    private $widgetPaths = null;

    # -------------------------------------------------------------------------
    # constructor
    # -------------------------------------------------------------------------
    
    function __construct() {
      $this->menuDict = [];
      $this->posttypeObjs = [];
      $this->widgetAreas = [];
      $this->widgetClasses = [];

      $dict = wp_upload_dir();
      $this->uploadUrl = $dict['url'];
      $this->uploadPath = $dict['path'];
      
      # Where to look for view files?
      $td = get_template_directory();
      $this->viewDirs = [
        "$td/lib/views",
        "$td/lib/BaseTheme/views"
      ];
    }
    
    # -------------------------------------------------------------------------
    # rendering
    # -------------------------------------------------------------------------
   
    public function renderContent() {
      the_content();
    }
    
    public function renderView($viewName, $args = []) {
      $this->_loadView($viewName, $args);
    }
    
    # -------------------------------------------------------------------------
    # menus
    # -------------------------------------------------------------------------
  
    #
    # Menus must be registered in order to be visible on the admin site
    #
  
    public function registerMenus($menuDict) {
      $this->menuDict = $menuDict;
      add_action('init', [$this, '_registerMenus']);
    }
  
    public function _registerMenus() {
      add_theme_support('nav-menus');
      register_nav_menus($this->menuDict);
    }
  
    #
    # Return a "jbr" (i.e. neutral) array of menu item objs
    #
  
    public function getMenuItems($menuSlug) {
      $wpItems = wp_get_nav_menu_items($menuSlug);
      $jbrItems = [];
      foreach ($wpItems as $key => $item) {
        $i = [
          'label' => $item->title,
          'url' => $item->url
        ];
        array_push($jbrItems, $i);
      }
      
      return $jbrItems;
    }
  
    #
    # Build and return the <ul> element for a menu. Accept these
    # optional (CSS) args: ulId, ulClass, liClass, aClass
    #
  
    public function makeMenuHtml($jbrItems, $args = []) {
      $html = '<ul';
      if ( isset($args['ulId']) )
        $html .= sprintf(' id="%s"', $args['ulId']);
      if ( isset($args['ulClass']) )
        $html .= sprintf(' class="%s"', $args['ulClass']);
      $html .= ">\n";
      
      if ( isset($args['liClass']) )
        $liCl = sprintf(' class="%s"', $args['liClass']);
      else
        $liCl = '';
      if ( isset($args['aClass']) )
        $aCl = sprintf(' class="%s"', $args['aClass']);
      else
        $aCl = '';

      foreach ($jbrItems as $item) {
        $html .= sprintf('  <li%s><a href="%s"%s>%s</a></li>', 
          $liCl, $item['url'], $aCl, $item['label']);
        $html .= "\n";
      }
      $html .= "</ul>";
      
      return $html;
    }
  
    # -------------------------------------------------------------------------
    # widget areas
    # -------------------------------------------------------------------------
    
    public function addWidgetArea($label, $cssId, $cssClass) {
        if ( count($this->widgetAreas) == 0 )
            add_action('widgets_init', [$this, '_registerWidgetAreas']);
        $wa = [
            'name'          => $label,
            'id'            => $cssId,
            'class'         => $cssClass,
            'before_widget' => '',
            'after_widget'  => '',
        ];
        $this->widgetAreas[] = $wa;
    }
    
    public function _registerWidgetAreas() {
        foreach ($this->widgetAreas as $wa)
            register_sidebar($wa);
    }
    
    public function renderWidgetArea($cssId) {
        if ( is_active_sidebar($cssId) ) {
            printf('<div id="%s">' . "\n", $cssId);
            dynamic_sidebar($cssId);
            printf('</div> <!-- #%s -->' . "\n", $cssId);
        }
    }
    
    # -------------------------------------------------------------------------
    # widgets
    # -------------------------------------------------------------------------
    
    public function useWidget($className) {
        if ( count($this->widgetClasses) == 0 )
            add_action('widgets_init', [$this, '_registerWidgets']);
        $this->widgetClasses[] = $className;
    }
    
    public function _registerWidgets() {
        $widDir = get_template_directory() . '/widgets';  # hAck?
        foreach ($this->widgetClasses as $wcls) {
            $wpath = sprintf('%s/%s.php', $widDir, $wcls);
            include $wpath;
            register_widget($wcls);
        }
    }
    
    # -------------------------------------------------------------------------
    # other goodies
    # -------------------------------------------------------------------------
    
    public function setHeadTitlePattern($pat) {
        $this->headTitlePattern = $pat;
    }
    
    public function getHeadTitle() {
        return sprintf($this->headTitlePattern, $this->getDocTitle());
    }
    
    public function getPostNavigation() {
        $postNav = get_the_post_navigation();
        
        return $postNav;
    }
    
    public function getNextPost() {
      the_post();
      
      return $this->convertPoge();
    }
    
    public function getPage() {
      the_post();  # (Shhh!!! Getting the post returns a page!!!)
      
      return $this->convertPoge();
    }
    
    # (This same code would work for posts, non?)
  
    public function getPageBySlug($slug) {
      $wpPage = get_page_by_path($slug);
      $page = [
        'slug'    => $slug,
        'title'   => apply_filters('the_title', $wpPage->post_title),
        'content' => apply_filters('the_content', $wpPage->post_content)
      ];
      
      return $page;
    }
    
  /*
    public function limitWords($content, $count = 30) {
        $arr = preg_split('!\s+!', $content);
        $chopped = false;
        if ( count($arr) > $count ) {
            $arr = array_slice($arr, 0, $count);
            $chopped = true;
        }
        $newcontent = implode(' ', $arr);
        if ($chopped)
            $newcontent .= '...';
        return $newcontent;
    }
    */
  
    /*
    public function renderNav() {
        wp_nav_menu();
    }
    */
    
    public function renderPosts($wpResult, $viewName /*, $args=[]*/) {
      while ( $wpResult->have_posts() ) {
        $wpResult->the_post();
        $poge = $this->convertPoge();
        $this->_loadView($viewName, ['poge' => $poge]);
      }
    }
  
    public function renderRecentPosts($type, $count, $viewName) {
      $args = ['post_type' => $type, 'posts_per_page' => $count];
      $wpResult = $this->queryPosts($args);
      $this->renderPosts($wpResult, $viewName);
    }
    
    # -------------------------------------------------------------------------
    # adjacent post links (done better)
    # -------------------------------------------------------------------------
  
    public function getNextPostLink($myfmt) {
      return $this->_getAdjacentPostLink('next', $myfmt);
    }
  
    public function getPreviousPostLink($myfmt) {
       return $this->_getAdjacentPostLink('previous', $myfmt);     
    }
  
    protected function _getAdjacentPostLink($direction, $myfmt) {
      # Get the default WP-produced link
      if ($direction === 'next')
        $wpA = get_next_post_link();
      else
        $wpA = get_previous_post_link();
      
      #error_log("_getAdjacentPostLink ($wpA)");  # DEBUG
      
      # Extract the URL and post title
      $url = $title = '';
      if ($wpA) {
        preg_match('!<a href="(.*?)".*?>(.*?)<!u', $wpA, $matches);
        list($all, $url, $title) = $matches;
      }
  
      # Now use my "improved" $myfmt to "design" the link
      $a = '';
      if ($url)
        $a = $this->_buildBetterAdjacentPostLink($myfmt, $url, $title);
      
      return $a;
    }
  
    protected function _buildBetterAdjacentPostLink($myfmt, $url, $title) {
      $a = str_replace('%url', $url, $myfmt);
      $a = str_replace('%title', $title, $a);
      
      return $a;
    }
  
    # -------------------------------------------------------------------------
    # DB stuff
    # -------------------------------------------------------------------------
  
    public function queryPosts($args) {
      $wpResult = new \WP_Query($args);
      
      return $wpResult;
    }
  
    #
    # After a WP the_post() has been called, collect the interesting
    # values into a "Jbr" post dictionary. Works for posts and pages
    # (thus the name 'poge').
    #
  
    public function convertPoge($wpPost = null, $getCustomFields = false) {
      if (!$wpPost)
          $wpPost = get_post();
      
      $poge = [
        'id'      => $wpPost->ID,
        'slug'    => $wpPost->post_name,
        'title'     => apply_filters('the_title', $wpPost->post_title),
        'content'   => apply_filters('the_content', $wpPost->post_content),
        'date'      => get_the_date('', $wpPost),
        'excerpt'   => get_the_excerpt($wpPost),
        'author'    => $wpPost->post_author,
        'permalink' => apply_filters('the_permalink', get_permalink($wpPost)),
        'type'      => $wpPost->post_type
      ];
      
      if ($getCustomFields)
        $poge['customFields'] = get_post_custom($wpPost->ID);
      
      return $poge;
    }
  
    # -------------------------------------------------------------------------
    # wrappers for WP functions
    # -------------------------------------------------------------------------
    
    public function getThemeDir() {
        return get_template_directory();
    }
    
    public function getThemeUri() {
        return get_template_directory_uri();
    }
    
    public function getSiteInfo() {
      $info = [
        'name'        => get_bloginfo('name'),
        'description' => get_bloginfo('description')
      ];
      
      return $info;
    }
  
    public function getSiteName() {
      return get_bloginfo('name');
    }
    
    public function getSiteUrl() {
        return get_bloginfo('url');
    }
    
    public function getPostCount() {
        global $wp_query;
        return $wp_query->post_count;
    }
    
    public function getDocTitle() {
        return ($this->docTitle) ? $this->docTitle : get_the_title();
    }
    
    public function setDocTitle($title) {
      $this->docTitle = $title;
    }
  
    public function getPostAuthor() {
        return get_the_author();
    }
    
    public function getPostDate($fmt = '') {
        return get_the_date($fmt);
    }

    public function getPostUrl() {
        return get_the_permalink();
    }

    public function getPostTitle() {
        return get_the_title();
    }
    
    public function isFrontPage() {
        return is_front_page();
    }
    
    # -------------------------------------------------------------------------
    # protected methods
    # -------------------------------------------------------------------------
    
    protected function _loadView($viewName, $args = []) {
      # Look for the view in the view dirs
      foreach ($this->viewDirs as $dir) {
        $path = sprintf('%s/%s.php', $dir, $viewName);
        if ( file_exists($path) )
          break;
        $path = '';
      }
      
      # If found, load it. Else die.
      if ($path) {
        if ($args)
          extract($args);
        include($path);       
      } else {
        die("error: view '$viewName' not found");
      }
    }
    
}

?>