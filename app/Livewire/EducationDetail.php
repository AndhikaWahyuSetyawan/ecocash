<?php

namespace App\Livewire;

use App\Models\EducationContent;
use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EducationDetail extends Component
{
    public EducationContent $content;
    public bool $hasEarnedXp = false;

    public function mount(string $slug, GamificationService $gamificationService): void
    {
        $this->content = EducationContent::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        if (Auth::check()) {
            // Beri reward XP membaca edukasi (30 XP) jika belum pernah
            $alreadyAwarded = Auth::user()->xpLedgers()
                ->where('source_type', 'education_read')
                ->where('source_id', $this->content->id)
                ->exists();

            if (!$alreadyAwarded) {
                $gamificationService->awardXp(
                    Auth::user(),
                    30,
                    'education_read',
                    $this->content->id,
                    'Membaca panduan: ' . $this->content->title
                );
                $gamificationService->recordActionProgress(Auth::user(), 'education_read', 1);
                $this->hasEarnedXp = true;
            }
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $relatedArticles = EducationContent::where('is_published', true)
            ->where('id', '!=', $this->content->id)
            ->take(3)
            ->get();

        return view('livewire.education-detail', [
            'relatedArticles' => $relatedArticles,
        ])->layout('layouts.app');
    }
}
