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
        'selectImageSection',
        'imageFirstTitle',
        'imageSection_id',
        'imageSecondTitle',
        'selectImgWithTextSection',
        'description',
        'selectVideoSection',
        'videoLink',
        'videoFirstTitle',
        'videoSecondTitle',
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
    ];

}













