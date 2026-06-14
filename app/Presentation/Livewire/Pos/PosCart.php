<?php

declare(strict_types=1);

namespace App\Presentation\Livewire\Pos;

use App\Application\Actions\Order\PlaceOrderAction;
use App\Domain\Outlet\Models\Outlet;
use App\Domain\Table\Models\Table;
use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

/**
 * POS Cart Panel: cart items, order type toggle, customer info, totals, payment modal.
 * Listens to `pos:add-item` from PosMenu. Dispatches `pos:order-completed` on success.
 */
class PosCart extends Component
{
    // Prop from parent (controls mobile tab visibility)
    #[Reactive]
    public string $activeTab = 'menu';

    // Order state (owned by PosCart — UI controls live in pos-cart view)
    public string $orderType = 'dine_in';
    public ?string $tableId = null;
    public string $customerName = '';
    public string $customerPhone = '';
    public string $notes = '';

    // Cart data
    public array $cart = [];

    // Payment modal state
    public bool $showPaymentModal = false;
    public string $paymentMethod = 'tunai';
    public mixed $cashAmount = 0;

    #[On('pos:add-item')]
    public function addItem(array $item): void
    {
        $cartService = app(CartService::class);
        $this->cart = $cartService->addItem($this->cart, $item);
    }

    public function removeFromCart(string $key): void
    {
        $cartService = app(CartService::class);
        $this->cart = $cartService->removeItem($this->cart, $key);
    }

    public function incrementQuantity(string $key): void
    {
        if (!isset($this->cart[$key])) {
            return;
        }
        $cartService = app(CartService::class);
        $this->cart = $cartService->updateQuantity($this->cart, $key, $this->cart[$key]['quantity'] + 1);
    }

    public function decrementQuantity(string $key): void
    {
        if (!isset($this->cart[$key])) {
            return;
        }
        $cartService = app(CartService::class);
        $this->cart = $cartService->updateQuantity($this->cart, $key, $this->cart[$key]['quantity'] - 1);
    }

    public function getCartTotal(): int
    {
        return app(CartService::class)->calculateTotal($this->cart);
    }

    public function getTax(): int
    {
        $outlet = Outlet::first();
        $taxRate = $outlet?->tax_rate ?? 0;
        return (int) round($this->getCartTotal() * $taxRate / 100);
    }

    public function getGrandTotal(): int
    {
        return $this->getCartTotal() + $this->getTax();
    }

    public function openPaymentModal(): void
    {
        $this->resetErrorBag();

        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang belanja kosong.');
            return;
        }

        if ($this->orderType === 'dine_in' && !$this->tableId) {
            $this->addError('tableId', 'Meja harus dipilih untuk tipe Dine-in.');
            return;
        }

        $this->cashAmount = intdiv($this->getGrandTotal(), 100);
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
    }

    public function calculateChange(): int
    {
        $cashInCents = (int) (floatval($this->cashAmount) * 100);
        return max(0, $cashInCents - $this->getGrandTotal());
    }

    public function placeOrder(): void
    {
        $this->resetErrorBag();

        if (empty($this->cart)) {
            return;
        }

        if ($this->orderType === 'dine_in' && !$this->tableId) {
            $this->addError('tableId', 'Meja harus dipilih untuk tipe Dine-in.');
            return;
        }

        $total = $this->getGrandTotal();

        if ($this->paymentMethod === 'tunai') {
            $cashInCents = (int) (floatval($this->cashAmount) * 100);
            if ($cashInCents < $total) {
                $this->addError('cashAmount', 'Uang tunai pembayaran tidak mencukupi.');
                return;
            }
            $status = 'paid';
        } else {
            $status = 'payment_pending';
        }

        $placeOrderAction = app(PlaceOrderAction::class);
        $tableParam = ($this->orderType === 'dine_in') ? intval($this->tableId) : null;

        $order = $placeOrderAction->execute(
            cart: $this->cart,
            tableId: $tableParam,
            customerName: $this->customerName ?: null,
            customerPhone: $this->customerPhone ?: null,
            notes: $this->notes ?: null,
            status: $status,
        );

        $successMsg = "Transaksi {$order->order_number} berhasil disimpan!";
        if ($this->paymentMethod === 'tunai') {
            $change = $this->calculateChange();
            $successMsg .= ' Kembalian: ' . formatRupiah($change);
        }

        $this->dispatch('pos:order-completed', orderId: $order->id);

        $this->cart = [];
        $this->customerName = '';
        $this->customerPhone = '';
        $this->notes = '';
        $this->tableId = null;
        $this->cashAmount = 0;
        $this->showPaymentModal = false;

        session()->flash('success', $successMsg);
    }

    public function render()
    {
        $tables = Table::orderBy('table_number', 'asc')->get();

        return view('livewire.pos.pos-cart', [
            'tables' => $tables,
        ]);
    }
}
