<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UserShow extends Component
{
    public User $user;

    public array $sections = [
        ['id' => 'overview', 'label' => 'Overview', 'icon' => 'user'],
        ['id' => 'profile', 'label' => 'Profile Details', 'icon' => 'settings'],
        ['id' => 'security', 'label' => 'Security', 'icon' => 'hash'],
        ['id' => 'activity', 'label' => 'Activity', 'icon' => 'zap'],
    ];

    public function mount(User $user): void
    {
        $this->user = $user->load('profile');
    }

    public function render()
    {
        return view('livewire.user-show')->layout('components.layouts.admin');
    }
}
