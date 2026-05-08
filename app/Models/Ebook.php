<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;
    protected $fillable = ['title','category','description','content','file_url','cover_image','is_published','views'];
    protected $casts = ['is_published' => 'boolean'];
}
