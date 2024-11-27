<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blockchain extends Model
{
    protected $blocks = [];

    /**
     * Crear el primer bloque génesis (bloque inicial).
     *
     * @return Block
     */
    // Método para crear el bloque génesis
    /* public function createGenesisBlock()
    {
        // Crear el bloque génesis con un hash previo de '0' y un dato específico
        $genesisBlock = new Block('0', 'Genesis Block');
        $this->saveBlockToDatabase($genesisBlock); // Guardar en la base de datos
        return $genesisBlock;
    } */
    public function createGenesisBlock()
    {
        // Verifica si ya existe un bloque génesis en la base de datos
        $existingGenesisBlock = \DB::table('block')->where('previous_hash', '0')->first();
    
        if ($existingGenesisBlock) {
            // Retorna el bloque génesis existente
            return new Block($existingGenesisBlock->previous_hash, $existingGenesisBlock->data, $existingGenesisBlock->hash);
        }
    
        // Crear y guardar un nuevo bloque génesis
        $genesisBlock = new Block('0', 'Genesis Block', null);
        $this->saveBlockToDatabase($genesisBlock);
        return $genesisBlock;
    }
    



    
    public function __construct()
{
    $this->blocks = $this->loadBlocksFromDatabase();
}
public function loadBlocksFromDatabase()
{
    /* try { */
        // Recuperar todos los bloques de la base de datos ordenados por ID o timestamp
        $blocksData = \DB::table('block')->orderBy('id')->get();

        $blocks = []; // Reiniciar la cadena de bloques en memoria

        foreach ($blocksData as $blockData) {
            $timestamp = $blockData->timestamp; // Convierte la fecha en formato 'Y-m-d H:i:s' a UNIX timestamp
            $block = new Block($blockData->previous_hash, $blockData->data, $blockData->hash);
            $block->timestamp = $timestamp; // Asignar el timestamp convertido al bloque

            /* // Crear una instancia de Block por cada entrada en la base de datos
            $block = new Block($blockData->previous_hash, $blockData->data, $blockData->hash);
             */
            $blocks[] = $block; // Agregar a la cadena
        }

        // Validar la cadena después de cargarla
        $this->blocks = $blocks;
        if (!$this->isChainValid()) {
            // Retornar la excepción como un objeto
            return new \Exception('La cadena de bloques cargada desde la base de datos no es válida.');
        }

        return $blocks; // Retornar la lista de bloques si es válida
    /* } catch (\Exception $e) {
        // Capturar cualquier otro error y retornarlo
        return $e; // Retorna la excepción capturada
    } */
}

    /**
     * Añadir un nuevo bloque a la cadena.
     *
     * @param string $data
     * @return void
     */
    public function addBlock($data)
{
        // Cargar los bloques desde la base de datos antes de agregar un nuevo bloque
        /* $exception =  */$this->loadBlocksFromDatabase(); 

        // Verificar si la carga de bloques devolvió una excepción
        /* if ($exception instanceof \Exception) {
            // Mostrar el mensaje de error en una ventana emergente (popup)
            echo "<script type='text/javascript'>alert('Error: " . $exception->getMessage() . "');</script>";
            return; // Salir de la función
        } */

        // Verificar si la cadena de bloques está vacía y crear un bloque génesis si es necesario
        if (empty($this->blocks)) {
            $genesisBlock = $this->createGenesisBlock();
            $this->blocks[] = $genesisBlock;  // Agregar el bloque génesis
            $previousBlock = $genesisBlock;   // El bloque génesis se usa como el anterior
        } else {
            $previousBlock = end($this->blocks); // Obtener el último bloque de la cadena
        }

        // El nuevo bloque usa el hash del bloque anterior
        $newBlock = new Block($previousBlock->hash, $data, null);
        $this->saveBlockToDatabase($newBlock);
        $this->blocks[] = $newBlock;

}
    /**
     * Validar la cadena de bloques.
     *
     * @return bool
     */
    public function isChainValid()
    {
        
    for ($i = 1; $i < count($this->blocks); $i++) {
        $currentBlock = $this->blocks[$i];
        $previousBlock = $this->blocks[$i - 1];

        // Verificar que el hash del bloque actual sea válido
        if (trim($currentBlock->previous_hash) !== trim($previousBlock->hash)) {
            $errorMessage = "Error: El hash del bloque actual no coincide con el hash del bloque anterior.\n";
            $errorMessage .= "Current Block Previous Hash: " . $currentBlock->previous_hash . "\n";
            $errorMessage .= "Previous Block Hash: " . $previousBlock->hash . "\n";
            
            throw new \Exception($errorMessage);
        }
        

        // Verificar que el hash del bloque sea correcto
       /*  if ($currentBlock->hash !== $currentBlock->calculateHash()) {
            $errorMessage = "Error: El hash calculado del bloque no coincide con el hash almacenado.\n";
            $errorMessage .= "Current Block Hash: " . $currentBlock->hash . "\n";
            $errorMessage .= "Current Block Previous Hash: " . $currentBlock->previous_hash . "\n";
            $errorMessage .= "Current Block Data: " . $currentBlock->data . "\n";
            $errorMessage .= "Current Block Timestamp: " . $currentBlock->timestamp . "\n";
            $errorMessage .= "Calculated Hash: " . $currentBlock->calculateHash() . "\n";
    
        
            throw new \Exception($errorMessage);
        } */
        
    }

    return true;
}

   
    // Método para guardar un bloque en la base de datos
    public function saveBlockToDatabase($block)
    {
        \DB::table('block')->insert([
            'previous_hash' => $block->previous_hash,
            'data' => $block->data,
            'hash' => $block->hash,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
