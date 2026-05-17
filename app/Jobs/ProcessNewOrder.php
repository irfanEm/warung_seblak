<?php

namespace App\Jobs;

use App\Domain\Order\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessNewOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $orderId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
        
        // Atur agar default masuk ke queue 'default'
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::find($this->orderId);
        
        if (!$order) {
            Log::warning("Order {$this->orderId} tidak ditemukan saat diproses.");
            return;
        }

        // Contoh: Broadcast event ke dapur (menggunakan Pusher/Reverb)
        // event(new OrderCreatedForKitchen($order));
        
        // Contoh: Update status (bisa dilakukan sebelum atau sesudah broadcast)
        $order->update(['status' => 'processing']);
        
        Log::info("Pesanan {$this->orderId} berhasil diproses oleh Worker.");
    }
}
