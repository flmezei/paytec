<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyRedirectLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'redirect_id',
        'ip',
        'user_agent',
        'referer',
        'query_params',
        'access_time',
    ];

    // Relação com o redirect
    public function redirect()
    {
        return $this->belongsTo(MyRedirect::class);
    }
}
