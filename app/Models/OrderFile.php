<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'progress_id',
        'file_name',
        'file_url',
        'file_path',
        'file_size',
        'file_type',
        'description',
        'uploaded_by',
    ];

    /**
     * Relationships : File belongs to an Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationships : File belongs to a Progress (optional).
     */
    public function progress()
    {
        return $this->belongsTo(OrderProgress::class, 'progress_id');
    }

    /**
     * Relationships : File uploaded by a User.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Check if URL is Google Drive link
     */
    public function isGoogleDrive()
    {
        return str_contains($this->file_url, 'drive.google.com');
    }

    /**
     * Check if URL is Dropbox link
     */
    public function isDropbox()
    {
        return str_contains($this->file_url, 'dropbox.com');
    }

    /**
     * Get direct download link for Google Drive
     */
    public function getGoogleDriveDownloadLinkAttribute()
    {
        if ($this->isGoogleDrive()) {
            preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $this->file_url, $matches);
            if (isset($matches[1])) {
                return 'https://drive.google.com/uc?export=download&id=' . $matches[1];
            }
        }
        return null;
    }

    /**
     * Get preview link for google drive
     */
    public function getPreviewLinkAttribute()
    {
        if ($this->isGoogleDrive()) {
            preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $this->file_url, $matches);
            if (isset($matches[1])) {
                return 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
            }
        }
        return null;
    }
}
