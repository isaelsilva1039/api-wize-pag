<?php

namespace App\Models\Empresa;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EmpresaUsuario extends Model
{

    protected $table = 'empresa_usuario';

    public $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'empresa_id',
        'usuario_id',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
