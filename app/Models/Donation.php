<?php

namespace App\Models;

use App\Helpers\PublicId;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'public_id',
        'profile_id',
        'amount_cents',
        'currency',
        'converted_amount_cents',
        'converted_currency',
        'status',
        'campaign_id',
        'campaign',
        'designation',
        'frequency',
        'donation_date',
        'success_date',
        'payment_amount_cents',
        'processing_fee_cents',
        'net_amount_cents',
        'payment_method',
        'source',
        'device',
        'donor_type',
        'tribute_info',
        'comment',
        'utm_source',
        'utm_campaign',
        'receipt_email_sent',
        'thank_you_email_sent',
        'custom_fields',
    ];

    protected $casts = [
        'donation_date' => 'datetime',
        'success_date' => 'datetime',
        'receipt_email_sent' => 'boolean',
        'thank_you_email_sent' => 'boolean',
        'custom_fields' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->public_id)) {
                $model->public_id = PublicId::generateFor(self::class);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getAmountAttribute(): string
    {
        return '$' . number_format($this->amount_cents / 100, 2) . ' ' . $this->currency;
    }

    public function getConvertedAmountAttribute(): ?string
    {
        if (!$this->converted_amount_cents || !$this->converted_currency) {
            return null;
        }
        return $this->converted_currency . ' ' . number_format($this->converted_amount_cents / 100, 2);
    }

    public function getPaymentAmountAttribute(): ?string
    {
        if (!$this->payment_amount_cents) {
            return null;
        }
        return '$' . number_format($this->payment_amount_cents / 100, 2) . ' ' . $this->currency;
    }

    public function getProcessingFeeAttribute(): ?string
    {
        if (!$this->processing_fee_cents) {
            return null;
        }
        return '$' . number_format($this->processing_fee_cents / 100, 2) . ' ' . $this->currency;
    }

    public function getNetAmountAttribute(): ?string
    {
        if (!$this->net_amount_cents) {
            return null;
        }
        return '$' . number_format($this->net_amount_cents / 100, 2) . ' ' . $this->currency;
    }
}
