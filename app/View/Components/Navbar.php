<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar extends Component
{
    private string $title;
    private string $activeMenu;
    /**
     * Create a new component instance.
     */
    public function __construct(string $title, string $activeMenu = '')
    {
        $this->title = $title;
        $this->activeMenu = $activeMenu;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.navbar', [
            'title' => $this->title,
            'activeMenu' => $this->activeMenu,
        ]);
    }
}
