<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'email',
        'phone',
        'company',
        'country',
        'city',
        'subject',
        'message',
        'locale',
        'ip_address',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function notes()
    {
        return $this->hasMany(EnquiryNote::class)->latest();
    }

    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }
}
