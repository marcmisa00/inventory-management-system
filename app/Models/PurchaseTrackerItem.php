<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseTrackerItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_tracker_id',
        'description',
        'unit_price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Get the purchase that owns the item.
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(PurchaseTracker::class, 'purchase_tracker_id');
    }

    /**
     * Get the formatted subtotal with currency.
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return '₱' . number_format($this->subtotal, 2);
    }

    /**
     * Get the formatted unit price with currency.
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return '₱' . number_format($this->unit_price, 2);
    }

    /**
     * Calculate subtotal automatically when unit_price or quantity changes.
     */
    public function calculateSubtotal(): void
    {
        $this->subtotal = $this->unit_price * $this->quantity;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate subtotal before saving
        static::saving(function ($item) {
            $item->calculateSubtotal();
        });

        // Recalculate parent grand_total when item is saved
        static::saved(function ($item) {
            if ($item->purchase) {
                $item->purchase->updateGrandTotal();
            }
        });

        // Recalculate parent grand_total when item is deleted
        static::deleted(function ($item) {
            if ($item->purchase) {
                $item->purchase->updateGrandTotal();
            }
        });
    }
}