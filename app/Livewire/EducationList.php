<?php

namespace App\Livewire;

use App\Models\EducationContent;
use Livewire\Component;
use Livewire\WithPagination;

class EducationList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $query = EducationContent::where('is_published', true)
            ->when($this->search, fn ($q) => $q->where(
                fn ($q2) => $q2
                    ->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('summary', 'like', '%' . $this->search . '%')
            ))
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->latest();

        return view('livewire.education-list', [
            'contents'   => $query->paginate(12),
            'categories' => [
                'sorting_guide' => 'Panduan Pilah',
                'waste_info'    => 'Info Sampah',
                'tips'          => 'Tips',
                'news'          => 'Berita',
            ],
        ])->layout('layouts.app');
    }
}
