<?php

namespace App\Livewire\Backend\User;

use App\Models\User;
use App\Traits\WithCustomPagination;
use Livewire\Component;
use Livewire\WithPagination;

class IndexComponent extends Component
{
    use WithCustomPagination, WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public bool $showStatus = true; // <-- add this

    protected string $paginationTheme = 'tailwind';

    // Reset pagination when filters change
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function delete(User $user)
    {
        $user->personalInformation()->delete();
        $user->experiencePreference()->delete();
        $user->driverDetail()->delete();
        $user->jobApplications()->delete();
        $user->jobPosts()->delete();
        $user->setAvailabilities()->delete();
        $user->delete();
        session()->flash('message', 'User deleted successfully!');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.trim($this->search).'%'))
            ->whereIn('user_type', ['shipper', 'trucker'])
            ->orderByDesc('id')
            ->paginate($this->perPage);

        return view('livewire.backend.user.index-component', [
            'users' => $users,
            'pageRange' => $this->getPageRange($users),
        ])->layout('backend.app');
    }
}
