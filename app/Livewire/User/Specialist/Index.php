<?php

namespace App\Livewire\User\Specialist;

use App\Models\Specialist;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Computed, Layout, On};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Especialistas', 'guard' => 'user'])]
class Index extends Component
{
    public string $search = '';

    #[Computed()]
    public function specialists()
    {
        return Specialist::with(['specialty', 'reasons'])
            ->where('is_active', true)
            ->whereHas('availabilities', function ($query) {
                $query->where('available_date', '>=', now()->toDateString());
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withExists([
                'favorites as favorited_by_user' => fn ($query) => $query->where('user_id', Auth::id()),
            ])
            ->orderByDesc('favorited_by_user')
            ->orderBy('name')
            ->get();
    }

    #[On('favorite-updated')]
    public function refreshList(): void
    {
        unset($this->specialists);
    }

    public function render()
    {
        return view('livewire.user.specialist.index');
    }
}
