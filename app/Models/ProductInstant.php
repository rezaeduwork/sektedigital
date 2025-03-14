<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInstant extends Model
{
  use HasFactory;
  protected $fillable = [
    'id',
    'code',
    'category',
    'title',
    'highlight',
    'description',
    'price',
    'slug',
    'stock',
    'status',
    'image'
  ];
}
