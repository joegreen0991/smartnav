<?php namespace Smartnav;
interface RendererInterface {
    public function render(Menu $menu,$menuElements,$attributes,$level);
    public function render_top(Menu $menu,$menuElements,$attributes);
    public function render_item(Menu $menu,array $items);
}
