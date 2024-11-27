<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Block extends Model
{
    protected $fillable = ['previous_hash', 'data', 'timestamp', 'hash'];
    
    // Propiedades públicas para el acceso
    public $previous_hash;
    public $data;
    public $timestamp;
    public $hash;

    /**
     * Constructor de un nuevo bloque.
     * 
     * @param  string  $previousHash
     * @param  string  $data
     * @return void
     */
    public function __construct($previousHash, $data, $hash)
{
    $this->previous_hash = $previousHash;
    $this->data = $data;
    /* $this->timestamp = time(); */
    $this->timestamp = date('Y-m-d H:i:s');
    $this->hash = $hash  ?? $this->calculateHash();
}


    /**
     * Calcular el hash del bloque.
     *
     * @return string
     */
    public function calculateHash()
    {
        return hash('sha256', $this->previous_hash . $this->data . $this->timestamp);
    }


    /**
     * Guardar el bloque en la base de datos.
     *
     * @return bool
     */
    public function saveBlock()
    {
        return $this->save();
    }

/*     public function saveBlock()
{
    return Block::create([
        'previous_hash' => $this->previous_hash,
        'data' => $this->data,
        'timestamp' => $this->timestamp,
        'hash' => $this->hash
    ]);
} */

}
