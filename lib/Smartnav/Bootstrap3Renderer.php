<?php namespace Smartnav;
Class Bootstrap3Renderer implements RendererInterface {

  public function render(Menu $menu, $menuElements, $attributes, $level) {
      
                $level and $attributes['class'] = isset($attributes['class']) ? $attributes['class'] . ' dropdown-menu' : 'dropdown-menu';
      
		return $menu->element ? '<' . $menu->element . ($level ? ' class="dropdown-menu" ' : $menu->attr($attributes)) . '>' . $menuElements . '</' . $menu->element . '>' : $menuElements;
	}

	public function render_top(Menu $menu, $menuElements, $attributes) {
		return $menuElements;
	}

	public function render_item(Menu $menu, $item) {
		$attrs = $item;

		$caret = empty($item['pages']) ? '' : '<i class="icon-chevron-down"></i>';

		foreach (array('name', 'pages', 'group', 'list_attributes') as $invalid) {
			if (isset($attrs[$invalid]))
				unset($attrs[$invalid]);
		}

		$icon = empty($item['icon']) ? '' : '<i class="' . $item['icon'] . '"></i> ';

                empty($item['pages']) or $attrs['class'] = isset($attrs['class']) ? $attrs['class'] . ' dropdown-toggle' : 'dropdown-toggle';

        	empty($item['pages']) or $attrs['data-toggle'] = "dropdown";
                
		return '<li' . $menu->attr($item['list_attributes']) . '><a' . $menu->attr($attrs) . '>' . $icon . '<span>' . $item['name'] . '</span>' . $caret . '</a>' . $item['pages'] . '</li>';
	}

}
