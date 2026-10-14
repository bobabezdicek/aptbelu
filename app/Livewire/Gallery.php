<?php

namespace App\Livewire;

use Livewire\Component;

class Gallery extends Component
{
    /** @var array<string> */
    public array $images = [
        'apartment-kitchen',
        'apartment-living',
        'apartment-bedroom',
        'apartment-bathroom',
        'apartment-dining',
        'apartment-workspace',
        'hero-apartment',
        'IMG-20250527-WA0002',
        'IMG-20250527-WA0003',
        'IMG-20250527-WA0004',
        'IMG-20250527-WA0007',
        'IMG-20250527-WA0010',
        'IMG-20250527-WA0011',
        'IMG-20250527-WA0012',
        'IMG-20250527-WA0013',
        'IMG-20250527-WA0015',
        '20250920_104137',
        '20250920_104148',
        '20250920_104208'
    ];

    public int $visible = 6;
    public bool $expanded = false;

    public bool $isOpen = false;
    public int $current = 0;

    public function toggle()
    {
        $this->expanded = ! $this->expanded;
        $this->dispatch('gallery-toggle', expanded: $this->expanded);
    }

    public function open(int $index): void
    {
        $this->current = max(0, min($index, count($this->images) - 1));
        $this->isOpen  = true;

        $this->dispatch('gallery-focus');
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->dispatch('gallery-close');
    }

    public function next(): void
    {
        if (!$this->isOpen) return;
        $this->current = ($this->current + 1) % count($this->images);
    }

    public function prev(): void
    {
        if (!$this->isOpen) return;
        $count = count($this->images);
        $this->current = ($this->current - 1 + $count) % $count;
    }

    public function render()
    {
        return view('livewire.gallery');
    }
}
