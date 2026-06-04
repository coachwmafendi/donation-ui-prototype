<?php

namespace App\Models;

use App\Helpers\PublicId;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'public_id',
        'name',
        'slug',
        'status',
        'goal_amount_cents',
        'raised_amount_cents',
        'donor_count',
        'description',
        'start_date',
        'end_date',
        'currency',
        'cover_image',
        'settings',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'settings' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->public_id)) {
                $model->public_id = PublicId::generateFor(self::class);
            }
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function getGoalAmountAttribute(): string
    {
        return '$'.number_format($this->goal_amount_cents / 100, 2);
    }

    public function getRaisedAmountAttribute(): string
    {
        return '$'.number_format($this->raised_amount_cents / 100, 2);
    }

    public function getProgressPercentageAttribute(): int
    {
        if (! $this->goal_amount_cents) {
            return 0;
        }

        return min(100, (int) round(($this->raised_amount_cents / $this->goal_amount_cents) * 100));
    }
}
