<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'purachase_name',
        'purchase_date',
        'company',
        'vendor',
        'receipt_number',
        'receipt_date',
        'receipt_details',
        'grand_total',
        'remarks',
        'receipt_encoded_by',
        'received_by',
        'pickup_by',
        'bought_by',
    ];

    /**
     * Get the items for this purchase.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseTrackerItem::class);
    }

    /**
     * Update the grand total based on all items.
     */
    public function updateGrandTotal(): void
    {
        $this->grand_total = $this->items()->sum('subtotal');
        $this->saveQuietly(); // Save without triggering events to avoid infinite loops
    }

    /**
     * Scope a query to only include records with a specific company.
     */
    public function scopeCompany($query, $company)
    {
        return $query->where('company', $company);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $from, $to)
    {
        if ($from) {
            $query->whereDate('purchase_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('purchase_date', '<=', $to);
        }
        return $query;
    }

    /**
     * Scope a query to search by various fields.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('purachase_name', 'LIKE', "%{$search}%")
              ->orWhere('vendor', 'LIKE', "%{$search}%")
              ->orWhere('receipt_number', 'LIKE', "%{$search}%")
              ->orWhere('company', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Cascade delete items when purchase is deleted
        static::deleting(function ($purchase) {
            $purchase->items()->delete();
        });
    }
}