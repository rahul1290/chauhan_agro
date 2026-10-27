<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_id',
        'user_id',
        'note',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
