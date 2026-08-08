<?php

namespace App\Imports;

use App\Models\PurchaseTracker;
use App\Models\PurchaseTrackerItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class PurchaseTrackerImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation, 
    SkipsOnFailure,
    WithChunkReading,
    WithBatchInserts
{
    use SkipsFailures;

    protected $importedCount = 0;
    protected $currentPurchaseTracker = null;
    protected $rowIndex = 0;

    public function model(array $row)
    {
        $this->rowIndex++;
        $this->importedCount++;

        // Check if this is a new purchase order (new row)
        // We'll use purchase_name + purchase_date + company + vendor as unique identifier
        $purchaseTracker = PurchaseTracker::firstOrCreate(
            [
                'purchase_name' => $row['purchase_name'] ?? null,
                'purchase_date' => $this->parseDate($row['purchase_date'] ?? null),
                'company' => $row['company'] ?? 'NEBG',
                'vendor' => $row['vendor'] ?? null,
            ],
            [
                'receipt_number' => $row['receipt_number'] ?? null,
                'receipt_date' => $this->parseDate($row['receipt_date'] ?? null),
                'receipt_details' => $row['receipt_details'] ?? null,
                'grand_total' => 0, // Will be calculated from items
                'remarks' => $row['remarks'] ?? null,
                'receipt_encoded_by' => $row['receipt_encoded_by'] ?? auth()->user()->name ?? 'System',
                'received_by' => $row['received_by'] ?? null,
                'pickup_by' => $row['pickup_by'] ?? null,
                'bought_by' => $row['bought_by'] ?? null,
            ]
        );

        // Store current purchase tracker for items
        $this->currentPurchaseTracker = $purchaseTracker;

        // Create item
        $subtotal = $this->parseCurrency($row['unit_price'] ?? 0) * ($row['quantity'] ?? 1);
        
        $item = new PurchaseTrackerItem([
            'purchase_tracker_id' => $purchaseTracker->id,
            'description' => $row['item_description'] ?? null,
            'unit_price' => $this->parseCurrency($row['unit_price'] ?? 0),
            'quantity' => $row['quantity'] ?? 1,
            'subtotal' => $subtotal,
        ]);

        // Update grand total
        $purchaseTracker->grand_total += $subtotal;
        $purchaseTracker->save();

        return $item;
    }

    public function rules(): array
    {
        return [
            'purchase_name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'company' => 'required|in:NEBG,FA',
            'vendor' => 'required|string|max:255',
            'receipt_number' => 'nullable|string|max:255',
            'receipt_date' => 'nullable|date',
            'receipt_details' => 'nullable|string',
            'remarks' => 'nullable|string',
            'receipt_encoded_by' => 'nullable|string|max:255',
            'received_by' => 'nullable|string|max:255',
            'pickup_by' => 'nullable|string|max:255',
            'bought_by' => 'nullable|string|max:255',
            'item_description' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'purchase_name.required' => 'Purchase Name is required',
            'purchase_date.required' => 'Purchase Date is required',
            'purchase_date.date' => 'Purchase Date must be a valid date format',
            'company.required' => 'Company is required',
            'company.in' => 'Company must be either NEBG or FA',
            'vendor.required' => 'Vendor is required',
            'item_description.required' => 'Item Description is required',
            'unit_price.required' => 'Unit Price is required',
            'unit_price.numeric' => 'Unit Price must be a valid number',
            'quantity.required' => 'Quantity is required',
            'quantity.min' => 'Quantity must be at least 1',
        ];
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            if (strpos($value, '/') !== false) {
                $parts = explode('/', $value);
                if (count($parts) === 3) {
                    return date('Y-m-d', strtotime($parts[2] . '-' . $parts[0] . '-' . $parts[1]));
                }
            }
            return date('Y-m-d', strtotime($value));
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseCurrency($value)
    {
        if (empty($value)) {
            return 0;
        }

        $value = preg_replace('/[^0-9.,]/', '', $value);
        $value = str_replace(',', '.', $value);
        return floatval($value);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }
}