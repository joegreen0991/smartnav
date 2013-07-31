<?php namespace Smartnav;
Class BootstrapRenderer implements RendererInterface {

  public function render(Menu $menu, $menuElements, $attributes, $level) {
		return $menu->element ? '<' . $menu->element . ($level ? ' class="submenu" ' : $menu->attr($attributes)) . '>' . $menuElements . '</' . $menu->element . '>' : $menuElements;
	}

	public function render_top(Menu $menu, $menuElements, $attributes) {
		return $menuElements;
	}

	public function render_item(Menu $menu, array $item) {
		$attrs = $item;

		$caret = empty($item['pages']) ? '' : '<i class="icon-chevron-down"></i>';

		foreach (array('name', 'pages', 'group', 'list_attributes') as $invalid) {
			if (isset($attrs[$invalid]))
				unset($attrs[$invalid]);
		}

		$icon = empty($item['icon']) ? '' : '<i class="' . $item['icon'] . '"></i>';

		return '<li' . $menu->attr($item['list_attributes']) . '><a' . $menu->attr($attrs) . '>' . $icon . '<span>' . $item['name'] . '</span>' . $caret . '</a>' . $item['pages'] . '</li>';
	}

}
