<?php

namespace App\Models;

use App\Http\Controllers\StoreController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Clear categories cache when a category is created, updated, or deleted
        static::created(function () {
            StoreController::clearCategoriesCache();
        });

        static::updated(function () {
            StoreController::clearCategoriesCache();
        });

        static::deleted(function () {
            StoreController::clearCategoriesCache();
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
