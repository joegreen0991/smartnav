<?php namespace Smartnav;
interface RendererInterface {
    public function render(Menu $menu,$menuElements,$attributes,$level);
    public function render_item(Menu $menu,array $items);
}
