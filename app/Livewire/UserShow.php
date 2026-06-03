<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UserShow extends Component
{
    public User $user;

    public array $sections = [
        ['id' => 'overview', 'label' => 'Overview', 'icon' => '👤'],
        ['id' => 'profile', 'label' => 'Profile Details', 'icon' => '📋'],
        ['id' => 'security', 'label' => 'Security', 'icon' => '🔒'],
        ['id' => 'activity', 'label' => 'Activity', 'icon' => '⚡'],
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
