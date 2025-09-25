<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Pagination extends Component
{
    public $paginator;
    public $pageRange;

    public function __construct($paginator, $pageRange)
    {
        $this->paginator = $paginator;
        $this->pageRange = $pageRange;
    }

    public function render()
    {
        return view('components.pagination');
    }
}
