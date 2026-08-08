<?php

namespace App\Imports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class AssetsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $importedCount = 0;

    public function model(array $row)
    {
        $this->importedCount++;

        return new Asset([
            'asset_tag' => $row['asset_tag'] ?? null,
            'company' => $row['company'] ?? 'NEBG',
            'delivery_date' => $row['delivery_date'] ?? null,
            'category' => $row['category'] ?? null,
            'brand' => $row['brand'] ?? null,
            'provider' => $row['provider'] ?? null,
            'status' => $row['status'] ?? 'In-Stock',
            'specification' => $row['specification'] ?? null,
            'remarks' => $row['remarks'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'asset_tag' => 'required|string|max:255|unique:assets,asset_tag',
            'company' => 'required|in:NEBG,FA',
            'delivery_date' => 'nullable|date',
            'category' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'provider' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'specification' => 'nullable|string',
            'remarks' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'asset_tag.required' => 'Asset Tag is required',
            'asset_tag.unique' => 'Asset Tag already exists in the database',
            'company.required' => 'Company is required',
            'company.in' => 'Company must be either NEBG or FA',
            'category.required' => 'Category is required',
            'delivery_date.date' => 'Delivery Date must be a valid date format',
        ];
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }
}