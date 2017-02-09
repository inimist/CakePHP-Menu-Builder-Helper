<?php
/* src/View/Helper/MenuHelper.php (using other helpers) */

namespace App\View\Helper;

use Cake\View\Helper;

class MenuHelper extends Helper
{
  public $helpers = ['Html'];

  public $area = 'main';

  /**
   * __construct
   *
   * @param \Cake\View\View $View View.
   * @param array $config configurations.
   */
  public function __construct(\Cake\View\View $View, array $config = [])
  {
      parent::__construct($View, $config);
  }

  /**
   * Default configuration.
   *
   * @var array
   */
  protected $_defaultConfig = [
    'options'=>[
      'class'=>'dropdown menu', 
      'id'=>'maindmenu', 
      'data-dropdown-menu'
    ]
  ];

  protected $_menuItems = [];

  /**
   * Adds a link to the breadcrumbs array.
   *
   * @param string $name Text for link
   * @param string|array|null $link URL for link (if empty it won't be a link)
   * @param string|array $options Link attributes e.g. ['id' => 'selected']
   * @return $this
   * @see \Cake\View\Helper\HtmlHelper::link() for details on $options that can be used.
   * @link http://book.cakephp.org/3.0/en/views/helpers/html.html#creating-breadcrumb-trails-with-htmlhelper
   * @deprecated 3.3.6 Use the BreadcrumbsHelper instead
   */
  public function addItem($name, $link = null, array $options = [], $children = [])
  {
      /*if(isset($options['children']) && sizeof($options['children'])) {
        $children = array_merge($children, $options['children']);
        unset($options['children']);
      }*/
      $this->_menuItems[$this->area][] = [$name, $link, $options, 'children'=>$children];
      return $this;
  }

 public function init($area)
  {
      $this->area = $area;
  }

  public function addSubItem($name, $link = null, array $options = [])
  {
      //debug($this->_menuItems);
      //debug($this->_menuItems[$this->area]);
      end($this->_menuItems[$this->area]);
      //debug($lastindex);
      $key = key($this->_menuItems[$this->area]);
      //debug($key);
      $this->_menuItems[$this->area][$key]['children'][] = [$name, $link, $options];
      return $this;
  }

  /**
   * Returns the menu as a fully functional menu structure.
   */
  public function get($area=  'main', array $options = [], $startText = false)
  {
    $options = array_merge($this->config('options'), $options);
    //debug($options);
    $this->area = $area;
    $defaults = ['firstClass' => 'first', 'lastClass' => 'last', 'separator' => '', 'escape' => true];
    $options += $defaults;
    $firstClass = $options['firstClass'];
    $lastClass = $options['lastClass'];
    $separator = $options['separator'];
    $escape = $options['escape'];
    unset($options['firstClass'], $options['lastClass'], $options['separator'], $options['escape']);

    $menuItems = $this->_prepareMenuItems($startText, $escape);
    if (empty($menuItems)) {
        return null;
    }
    $menuItems = $this->_prepareMenuItems($startText);
    //debug($menuItems);
    $menuHTML = $this->_buildMenuHTML($menuItems, $options, $firstClass, $lastClass);
    if($menuHTML)  return $menuHTML;
    return null;
  }

  function _buildMenuHTML($menuItems, $options, $firstClass, $lastClass) {
    if (!empty($menuItems)) {
      $result = '';
      $menuItemCount = count($menuItems);
      $ulOptions = $options;
      foreach ($menuItems as $which => $menuItem) {
          //debug($menuItem);
          $options = [];
          if(!isset($menuItem[2])) $menuItem[2] = [];
          if(isset($menuItem[3])) {$menuItem['children'] = $menuItem[3];unset($menuItem[3]);}
          if (empty($menuItem[1])) {
              $elementContent = $menuItem[0];
          } else {
              $elementContent = $this->Html->link($menuItem[0], $menuItem[1], $menuItem[2]);
          }
          if (!$which && $firstClass !== false) {
              $options['class'] = $firstClass;
          } elseif ($which == $menuItemCount - 1 && $lastClass !== false) {
              $options['class'] = $lastClass;
          }
          if (!empty($separator) && ($menuItemCount - $which >= 2)) {
              $elementContent .= $separator;
          }
          
          if(isset($menuItem['children']) && $menuItem['children']) {
            $options = ['class'=>'menu'];
            $elementContent .= $this->_buildMenuHTML($menuItem['children'], $options, $firstClass, $lastClass);
          }

          $result .= $this->Html->formatTemplate('li', [
              'content' => $elementContent,
              'attrs' => $this->Html->templater()->formatAttributes($options)
          ]);
      }
      return $this->Html->formatTemplate('ul', [
          'content' => $result,
          'attrs' => $this->Html->templater()->formatAttributes($ulOptions)
      ]);
    }
  }

    /**
     * menu
     *
     * The menu method who builds up the menu. This method will return html code.
     * The binded template to an area is used to style the menu.
     *
     * @param string $area Area to build.
     * @param string $helper Helper to use.
     * @param array $options Options.
     * @return string
     */
    public function menu($area, $helper, $options = [])
    {
        $_options = [
        ];

        $options = Hash::merge($_options, $options);

        $builder = $this->_View->helpers()->load($helper);

        $menu = $this->_View->viewVars['menu'][$area];

        $html = '';

        $html .= $builder->beforeMenu($menu);

        foreach ($menu as $item) {
            $html .= $builder->beforeItem($item);
            $html .= $builder->item($item);
            if ($item['children']) {
                $html .= $builder->beforeSubItem($item);
                foreach ($item['children'] as $subItem) {
                    $html .= $builder->subItem($subItem);
                }
                $html .= $builder->afterSubItem($item);
            }
            $html .= $builder->afterItem($item);
        }

        $html .= $builder->afterMenu($menu);

        return $html;
    }

  /**
   * Prepends startText to crumbs array if set
   *
   * @param string|array|bool $startText Text to prepend
   * @param bool $escape If the output should be escaped or not
   * @return array Crumb list including startText (if provided)
   * @deprecated 3.3.6 Use the BreadcrumbsHelper instead
   */
  protected function _prepareMenuItems($startText, $escape = true)
  {
    //debug($this->area);
      //debug($this->_menuItems);
      $menuItems = isset($this->_menuItems[$this->area]) ? $this->_menuItems[$this->area] : false;

      if(!$menuItems) return false;

      //debug($menuItems);
      if ($startText) {
          if (!is_array($startText)) {
              $startText = [
                  'url' => '/',
                  'text' => $startText
              ];
          }
          $startText += ['url' => '/', 'text' => __d('cake', 'Home')];
          list($url, $text) = [$startText['url'], $startText['text']];
          unset($startText['url'], $startText['text']);
          array_unshift($menuItems, [$text, $url, $startText + ['escape' => $escape]]);
      }

      return $menuItems;
  }
}