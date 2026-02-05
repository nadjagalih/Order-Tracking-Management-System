<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_code',
        'client_name',
        'client_email',
        'client_phone',
        'service_type',
        'description',
        'status',
        'estimated_completion',
        'notes',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'estimated_completion' => 'datetime',
    ];

    /**
     * Boot method untuk auto generate order_code saat membuat order baru.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_code)) {
                $order->order_code = self::generateOrderCode();
            }
        });
    }

    /**
     * Generate unique order code.
     */
    public static function generateOrderCode()
    {
        do {
            $code = 'ORD-' . Str::upper(Str::random(8));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }

    /**
     * Relasi order has many progress updates.
     */
    public function progress()
    {
        return $this->hasMany(OrderProgress::class)->orderBy('created_at', 'desc');
    }

    /**
     * Relasi order has many files.
     */
    public function files()
    {
        return $this->hasMany(OrderFile::class)->orderBy('created_at', 'desc');
    }

    /**
     * Relationship: Order belongs to a user (creator/admin)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * scope untuk filter order berdasarkan status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope filter by order code
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('order_code', $code);
    }

    /**
     * Accessor get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'revision_1' => 'bg-yellow-100 text-yellow-800',
            'revision_2' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Accessor get status label
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'in_progress' => 'Dikerjakan',
            'revision_1' => 'Revisi 1',
            'revision_2' => 'Revisi 2',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    /**
     * Check if order is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * check if order is in progress
     */
    public function isInProgress()
    {
        return in_array($this->status, ['in_progress', 'revision_1', 'revision_2']);
    }
}