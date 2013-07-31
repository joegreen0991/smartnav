<?php namespace Smartnav;

class Menu implements \Countable {

    /**
     * The current URI
     * @var string 
     */
    public $uri;
    
    /**
     * An Array containing the current menu structure
     * @var array 
     */
    public $menu;
    
    /**
     * An array of additional attributes to add to the containing element
     * @var array 
     */
    private $_attributes = array('class' => 'nav');
    
    /**
     * The containing element
     * @var string 
     */
    public $element = 'ul';
    
    /**
     *
     * @var type 
     */
    public $renderer;
    
    /**
     * Constructor
     * @param array $menu
     * @param type $uri
     */
    public function __construct(array $menu,$uri = null,  RendererInterface $renderer = null) {
        $this->menu = $menu;
        $this->uri = $uri;
        $this->renderer = $renderer ? : new Basic;
    }
    
    /**
     * Get the uri string
     * @return type
     */
    public function uri() {
        return $this->uri;
    }
    
    /**
     * Merge in an array of menu items
     * @param array $menu
     */
    public function add(array $menu){
        $this->menu = array_merge($this->menu,$menu);
    }
    
    /**
     * Set an attribute
     * @param array|string $name
     * @param string|null $value
     * @return \Jg\Menu
     */
    public function setAttribute($name,$value = null){
        is_array($name) || $name = array($name => $value);
        foreach($name as $k => $val){
            $this->_attributes[$k] = $val;
        }
        return $this;
    }
    
    /**
     * Set the top level elmeent
     * @param string $el
     * @return \Jg\Menu
     */
    public function setElement($el){
        $this->element = $el;
        return $this;
    }

    /**
     * Get the evaluated string content of the view.
     * 
     * @param  int  $level The number of sub levels to render
     * @return string
     */
    public function render($group = null,$level = null) {
        return $this->renderer->render_top($this,$this->_render($this->menu, $this->_attributes,$level,$group), $this->_attributes);
    }
    
    /**
     * Render the menu
     * @param array $menu_items
     * @param array $attributes
     * @param int $level
     * @return type
     */
    private function _render($menu_items,$attributes,$level = null,$group = null, $currentDepth = 0){
        if ($level !== null && $level-- <= 0)
            return ;

        $items = array();

        foreach ($menu_items as $item) {

            if(isset($group,$item['group']) && !in_array($group,explode(',',$item['group']))){
                continue;
            }
            
            isset($item['list_attributes']) or $item['list_attributes'] = array();

            if ($this->is_active($item) || $this->has_active_children($item)) {
                isset($item['list_attributes']['class']) or $item['list_attributes']['class'] = '';
                $item['list_attributes']['class'] .= 'active';
            }

            $item['pages'] = isset($item['pages']) ? $this->_render($item['pages'], array(), $level, $group, $currentDepth+1) : '';
            
            $items[] = $this->renderer->render_item($this,$item);
        }
        $str_items = implode(PHP_EOL, $items);
        
        return $this->renderer->render($this,$str_items,$attributes,$currentDepth);
    }

    /**
     * Return the second level for the active menu from the given items
     * @param bool $return_top_level
     * @return array 
     */
    public function find_submenu($return_top_level = false) {
        if (($item = $this->_find_submenu()) && isset($item['pages']))
            return $return_top_level ? array($item) : $item['pages'];
        return array();
    }
    
    /**
     * Find the the top level
     * @return boolean
     */
    private function _find_submenu(){
        foreach ($this->menu as $item) {
            if ($this->is_active($item) || $this->has_active_children($item)) {
                return $item;
            }
        }
        return false;
    }
    
    /**
     * Count the number of items in the menu
     * @return int
     */
    public function count(){
        return count($this->menu);
    }
    
    /**
     * Return a new instance of the menu builder
     * @param type $return_top_level
     * @return \self
     */
    public function submenu($return_top_level = false){
        return new self($this->find_submenu($return_top_level),$this->uri);
    }
    
    /**
     * Return the heading for the current item
     * @return type
     */
    public function heading(){
        if (($item = $this->_find_submenu()) && isset($item['name']))
            return $item['name'];
    }

    /**
     * Normalise the URL
     * @param type $url
     * @return type
     */
    private function treat_url($url) {
        return trim($url, '/');
    }

    /**
     * Check if the menu item is the current page
     * @param type $item
     * @return type
     */
    private function is_active($item) {
        return isset($item['href']) ? ($this->treat_url($item['href']) == $this->treat_url($this->uri())) : false;
    }

    /**
     * Check if any of the menu items children are the current page
     * @param type $item
     * @return boolean
     */
    private function has_active_children($item) {
        if (!isset($item['pages']))
            return false;

        foreach ($item['pages'] as $child) {
            if ($this->is_active($child) || $this->has_active_children($child))
                return true;
        }
    }

    /**
     * Return a list of breadcrumbs from the current page back up to the top level
     * @param type $crumbs
     * @return type
     */
    public function breadcrumbs($crumbs = array()) {
        return $this->_breadcrumbs($this->menu, $crumbs);
    }
    
    /**
     * Return a list of breadcrumbs from the current page back up to the top level
     * @param type $menu
     * @param type $crumbs
     * @return type
     */
    private function _breadcrumbs($menu,$crumbs = array()) {

        foreach ($menu as $item) {

            if ($this->is_active($item) || $this->has_active_children($item)) {

                $crumbs[] = $item;

                return $this->_breadcrumbs(isset($item['pages']) ? $item['pages'] : array(), $crumbs);
            }
        }
        return $crumbs;
    }

    /**
     * Attr to string
     * @param type $attr
     * @return type
     */
    public function attr($attr) {
        $string = ' ';
        foreach ($attr as $k => $v) {
            $v !== false && is_scalar($v) and $string .= is_numeric($k) ? $v : $k . '="' . e($v) . '"';
        }
        return $string;
    }
    
    /**
     * Magic renderer
     * @return string
     */
    public function __toString() {
        return $this->render();
    }
    
}
