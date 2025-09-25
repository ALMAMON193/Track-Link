<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TableHeader extends Component
{
    public $title;
    public $total;
    public $searchPlaceholder;
    public $statusOptions; // optional
    public $showStatus; // boolean to show status filter
    public $addNewRoute; // optional route
    public $perPageOptions;

    public function __construct(
        string $title,
        int $total = 0,
        string $searchPlaceholder = 'Search...',
        bool $showStatus = false,
        array $statusOptions = [],
        string $addNewRoute = '',
        array $perPageOptions = [10, 25, 50, 100]
    ) {
        $this->title = $title;
        $this->total = $total;
        $this->searchPlaceholder = $searchPlaceholder;
        $this->showStatus = $showStatus;
        $this->statusOptions = $statusOptions;
        $this->addNewRoute = $addNewRoute;
        $this->perPageOptions = $perPageOptions;
    }

    public function render()
    {
        return view('components.table-header');
    }
}
