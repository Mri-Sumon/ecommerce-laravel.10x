<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'icon_id',
        'companyName',
        'logo_id',
        'adminPicture_id',
        'importantUpdates',
        'facebook',
        'whatsapp',
        'twitter',
        'instagram',
        'linkedin',
        'pinterest',
        'map',
        'officeHours',
        'address',
        'footerLogo_id',
        'email',
        'contact',
        'copyright',
    ];

}













