<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    /**
     * Create a new component instance.
     */
    private string $color;
    private string $label;
    private string $className;

    public function __construct(string $color = 'primary', string $label = '', string $className = '')
    {
        $this->color = $color;
        $this->label = $label;
        $this->className = $className;
    }

    public function useThemeButton(): string
    {
        switch ($this->color) {
            case 'danger':
                return 'btn-danger';
            case 'success':
                return 'btn-success';
            case 'warning':
                return 'btn-warning';
            case 'info':
                return 'btn-info';
            case 'secondary':
                return 'btn-secondary';
            case 'dark':
                return 'btn-dark';
            case 'light':
                return 'btn-light';
            case 'primary':
            default:
                return 'btn-primary';
        }
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button', [
            'color' => $this->useThemeButton(),
            'label' => $this->label,
            'className' => $this->className,
        ]);
    }
}
