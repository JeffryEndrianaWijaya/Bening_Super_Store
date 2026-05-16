<?php

namespace App\View\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashboardLayout extends Component
{
    public string $title;
    public string $activeMenu;
    
    public function __construct(string $title, string $activeMenu = '')
    {
        $this->title = $title;
        $this->activeMenu = $activeMenu;
    }

    
    public function render(): View|Closure|string
    {
        return view('layouts.dashboard-layout', [
            'title' => $this->title,
            'activeMenu' => $this->activeMenu,
        ]);
    }
}
