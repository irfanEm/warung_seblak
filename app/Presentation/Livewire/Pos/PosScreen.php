<?php

declare(strict_types=1);

namespace App\Presentation\Livewire\Pos;

use Livewire\Attributes\On;
use Livewire\Component;

/**
 * POS Screen Orchestrator.
 * Manages global state (activeTab, lastOrderId) and composes PosMenu + PosCart sub-components.
 */
class PosScreen extends Component
{
    public string $activeTab = 'menu';
    public ?int $lastOrderId = null;

    public function mount(): void
    {
        $this->activeTab = 'menu';
        $this->lastOrderId = null;
    }

    #[On('pos:order-completed')]
    public function handleOrderCompleted(int $orderId): void
    {
        $this->lastOrderId = $orderId;
        $this->activeTab = 'menu';
    }

    public function render()
    {
        return view('livewire.pos.pos-screen')->layout('layouts.pos');
    }
}
