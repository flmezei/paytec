<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

/**
 * @property int $id
 * @property string $url
 * @property string $code
 */
class MyRedirect extends Model
{
    use HasFactory;

    protected $fillable = ['url'];

    public function setCodeAttribute(int $id): void
    {
        $this->attributes['code'] = Hashids::encode($id);
    }

    public function getCodeAttribute(string $code): int
    {
        return Hashids::decode($code)[0];
    }

    public function logs()
    {
        return $this->hasMany(MyRedirectLog::class);
    }
}
