<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SamagriItem extends Model
{
    use HasFactory;
    protected $fillable = ['service_id','item_name','quantity','price'];
    public function service() { return $this->belongsTo(Service::class); }
}
