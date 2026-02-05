<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProgress extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'order_progress';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'title',
        'description',
        'status',
        'files',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'files' => 'json',
        ];
    }

    /**
     * Relationships : Progress belongs to an Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationships : Progress created by a User.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Note: Removed files() relationship method to avoid conflict with files attribute
     * Files are now stored as JSON in the files column
     */

    /**
     * Accessor: get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'info' => 'info',
            'warning' => 'warning',
            'success' => 'success',
            'danger' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Accessor: get status icon
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'info' => 'ℹ️',
            'warning' => '⚠️',
            'success' => '✅',
            'danger' => '❌',
            default => '📌',
        };
    }
}
