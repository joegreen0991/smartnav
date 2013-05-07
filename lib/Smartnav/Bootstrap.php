<?php namespace Smartnav;
class Bootstrap extends Basic {
    
    public function render_top(Menu $menu,$menuElements,$attributes){
        return '<div class="navbar"><div class="navbar-inner"><div class="container"><a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></a><div class="nav-collapse collapse">'.$menuElements.'</div></div></div></div>';
    }
    
    public function render(Menu $menu,$menuElements,$attributes){
        return '<ul '.$menu->attr($attributes).'>'.$menuElements.'</ul>';
    }
}

