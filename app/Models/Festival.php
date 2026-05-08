<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Festival extends Model
{
    use HasFactory;
    protected $fillable = ['title','tithi','festival_date','description','category','image','is_major'];
    protected $casts = ['festival_date' => 'date', 'is_major' => 'boolean'];
}
