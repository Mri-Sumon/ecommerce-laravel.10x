<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductRating;

class Product extends Model
{
    use HasFactory;
    
    public function product_images(){
        return $this->hasMany(ProductImage::class);
    }

    public function product_ratings() {
        return $this->hasMany(ProductRating::class)->where('status',1);
    }
       
    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(){
        return $this->belongsTo(User::class, 'updated_by');
    }
    
}
 