<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'package_id',
        'product',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
