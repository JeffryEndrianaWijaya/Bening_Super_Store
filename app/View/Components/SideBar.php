<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SideBar extends Component
{
    public string $activeMenu;

    public function __construct(string $activeMenu = 'home')
    {
        $this->activeMenu = $activeMenu;
    }

    public function isActive(string $menu): string
    {
        return $this->activeMenu === $menu ? 'active' : '';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.side-bar', [
            'activeMenu' => $this->activeMenu,
            'isActive' => function (string $menu) {
                return $this->isActive($menu);
            },
        ]);
    }
}