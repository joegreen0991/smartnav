<?php namespace Smartnav;
class Basic implements RendererInterface {
    
    public function render(Menu $menu, $menuElements, $attributes, $level){
           return $menu->element ? '<' . $menu->element . $menu->attr($attributes) . '>' . $menuElements . '</' . $menu->element . '>' : $menuElements;
    }
    
    public function render_top(Menu $menu, $menuElements, $attributes){
        return $menuElements;
    }

    public function render_item(Menu $menu, $item){
        $attrs = $item;
        foreach (array('name', 'pages', 'group', 'list_attributes') as $invalid) {
            if (isset($attrs[$invalid]))
                unset($attrs[$invalid]);
        }

        return '<li' . $menu->attr($item['list_attributes']) . '><a' . $menu->attr($attrs) . '>' . $item['name'] . '</a>' . $item['pages'] . '</li>';
    }
}
