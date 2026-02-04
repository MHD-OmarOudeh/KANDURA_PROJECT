<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Log;

class GenerateInvoice
{
    public function __construct(private InvoiceService $invoiceService)
    {
    }

    public function handle(OrderCompleted $event): void
    {
        try {
            $order = $event->order;

            Log::info('GenerateInvoice listener triggered', ['order_id' => $order->id]);

            if ($order->invoice) {
                Log::info('Invoice already exists', ['order_id' => $order->id]);
                return;
            }

            $invoiceNumber = $this->invoiceService->generateInvoiceNumber();
            Log::info('Invoice number generated', ['invoice_number' => $invoiceNumber]);

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'order_id' => $order->id,
                'total' => $order->total,
                'pdf_url' => null,
            ]);

            Log::info('Invoice created', ['invoice_id' => $invoice->id]);

            $pdfPath = $this->invoiceService->generatePDF($invoice);

            if ($pdfPath) {
                $invoice->update(['pdf_url' => $pdfPath]);
                Log::info('PDF generated successfully', ['pdf_url' => $pdfPath]);

                // Invoice generated successfully (no notification needed based on requirements)
            }
        } catch (\Exception $e) {
            Log::error('Failed to generate invoice', [
                'order_id' => $event->order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
