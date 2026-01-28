<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    public function generateInvoiceNumber(): string
    {
        $lastInvoice = Invoice::latest('id')->first();
        $number = $lastInvoice ? intval(substr($lastInvoice->invoice_number, 4)) + 1 : 1;

        return 'INV-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    public function generatePDF(Invoice $invoice): string
    {
        try {
            Log::info('Starting PDF generation', ['invoice_id' => $invoice->id]);

            $invoice->load('order.user', 'order.orderItems.design');

            // Create directory if not exists
            if (!Storage::disk('public')->exists('invoices')) {
                Storage::disk('public')->makeDirectory('invoices');
                Log::info('Created invoices directory');
            }

            // Generate PDF from view
            $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

            $filename = 'invoices/' . $invoice->invoice_number . '.pdf';

            Storage::disk('public')->put($filename, $pdf->output());

            Log::info('PDF file created', ['filename' => $filename]);

            return '/storage/' . $filename;
        } catch (\Exception $e) {
            Log::error('PDF generation failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return '';
        }
    }
}
