<?php namespace Smartnav;
interface RendererInterface {
    public function render(Menu $menu,$menuElements,$attributes);
    public function render_item(Menu $menu,array $items);
}
