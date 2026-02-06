<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdminLayout extends Component
{
    public ?View $header; // Cho phép truyền header slot

    public function __construct($header = null)
    {
        $this->header = $header;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.admin'); // Trỏ đến file layout admin.blade.php
    }
}