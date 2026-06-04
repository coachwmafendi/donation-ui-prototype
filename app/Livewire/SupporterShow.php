<?php

namespace App\Livewire;

use App\Models\Profile;
use Livewire\Component;

class SupporterShow extends Component
{
    public Profile $supporter;

    public bool $showDeleteModal = false;

    public string $newTag = '';

    public array $sections = [
        ['id' => 'overview', 'label' => 'Overview', 'icon' => 'user'],
        ['id' => 'donations', 'label' => 'Donations', 'icon' => 'dollar-sign'],
        ['id' => 'recurring', 'label' => 'Recurring', 'icon' => 'refresh-cw'],
    ];

    public function mount(Profile $supporter): void
    {
        $this->supporter = $supporter->loadCount('donations');
    }

    public function delete(): void
    {
        $this->supporter->delete();
        $this->dispatch('toast', message: 'Supporter deleted.', type: 'success');
        $this->redirect('/supporters', navigate: true);
    }

    public function addTag(): void
    {
        $this->validate(['newTag' => 'required|string|max:50']);

        $tags = $this->supporter->tags ?? [];
        $tag = trim($this->newTag);

        if (! in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->supporter->update(['tags' => $tags]);
            $this->supporter->refresh();
        }

        $this->newTag = '';
    }

    public function removeTag(string $tag): void
    {
        $tags = $this->supporter->tags ?? [];
        $tags = array_values(array_filter($tags, fn ($t) => $t !== $tag));
        $this->supporter->update(['tags' => $tags]);
        $this->supporter->refresh();
    }

    public function render()
    {
        $donations = $this->supporter->donations()
            ->with('campaign')
            ->latest()
            ->paginate(10, pageName: 'donationsPage');

        $recurring = $this->supporter->donations()
            ->with('campaign')
            ->where('frequency', '!=', 'one-time')
            ->latest()
            ->get();

        $totalDonated = $this->supporter->donations()->sum('amount_cents');

        return view('livewire.supporter-show', [
            'donations' => $donations,
            'recurring' => $recurring,
            'totalDonated' => '$'.number_format($totalDonated / 100, 2),
        ])->layout('components.layouts.admin');
    }
}
