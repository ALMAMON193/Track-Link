<?php

namespace App\Livewire\Backend\Overview;

use Livewire\Component;

class IndexComponent extends Component
{
    public function render()
    {
        return view('livewire.backend.overview.index-component')->layout('backend.app');
    }
}
