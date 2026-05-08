<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rashi extends Model
{
    use HasFactory;
    protected $fillable = ['name','hindi_name','symbol','element','daily_prediction','weekly_prediction','monthly_prediction','lucky_color','lucky_number','career_prediction','health_prediction','relationship_prediction','prediction_date'];
    protected $casts = ['prediction_date' => 'date'];
}
