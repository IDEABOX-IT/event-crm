<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'qrCodePath',
        'event_id',
        'isCheckinComplete',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
