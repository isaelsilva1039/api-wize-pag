<?php

// app/Models/Empresa/Empresa.php

namespace App\Models\Empresa;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Guid\Guid;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'empresas';

    protected $fillable = [
        'id',
        'company_name',
        'company_email',
        'company_phone',
        'cnpj',
        'social_reason',
        'chave',
    ];

    protected $keyType = 'string';
    public $incrementing = true;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($empresa) {
            if (empty($empresa->chave)) {
                $empresa->chave = (string) Guid::uuid4();
            }
        });
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'empresa_usuario', 'empresa_id', 'usuario_id');
    }
}
