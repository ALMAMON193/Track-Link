<?php

namespace App\Livewire\Backend\User;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DetailsComponent extends Component
{
    public $user;

    public $tab = 'info'; // Default tab

    public function mount(User $user)
    {
        $this->user = $user;
        Log::info('User mounted: ID='.$this->user->id.', Status='.$this->user->status.', IsVerified='.($this->user->is_verified ? 'true' : 'false'));
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
        Log::info('Tab set to: '.$tab);
    }

    public function verifyUser()
    {
        try {
            // Update status if is_verified is true but status is not 'verified'
            if ($this->user->is_verified && $this->user->status !== 'verified') {
                Log::info('Fixing status inconsistency for user: ID='.$this->user->id);
                $this->user->status = 'verified';
                $this->user->verified_at = $this->user->verified_at ?? now();
                $saved = $this->user->save();

                if ($saved) {
                    $this->user->refresh();
                    Log::info('Status fixed: ID='.$this->user->id.', Status='.$this->user->status);
                    session()->flash('success', 'User status updated to verified!');
                } else {
                    Log::error('Failed to fix status: ID='.$this->user->id);
                    session()->flash('error', 'Failed to update user status. Please try again.');
                }
            } elseif (! $this->user->is_verified) {
                Log::info('Verifying user: ID='.$this->user->id);
                $this->user->is_verified = true;
                $this->user->verified_at = now();
                $this->user->status = 'verified';
                $saved = $this->user->save();

                if ($saved) {
                    $this->user->refresh();
                    Log::info('User verified successfully: ID='.$this->user->id.', Status='.$this->user->status);
                    session()->flash('success', 'User has been verified successfully!');
                } else {
                    Log::error('Failed to save user verification: ID='.$this->user->id);
                    session()->flash('error', 'Failed to verify user. Please try again.');
                }
            } else {
                Log::info('User already verified: ID='.$this->user->id);
                session()->flash('info', 'User is already verified.');
            }
        } catch (\Exception $e) {
            Log::error('Error verifying user: ID='.$this->user->id.', Error='.$e->getMessage());
            session()->flash('error', 'An error occurred while verifying the user.');
        }
    }

    public function render()
    {
        return view('livewire.backend.user.details-component')->layout('backend.app');
    }
}
