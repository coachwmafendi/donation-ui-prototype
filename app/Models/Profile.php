<?php

namespace App\Models;

use App\Helpers\PublicId;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'public_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
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

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
