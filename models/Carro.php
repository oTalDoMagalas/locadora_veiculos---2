<?php

namespace Models;

use Interfaces\Locavel;

// Classe que representa um carro 

class Carro extends Veiculo implements Locavel
{
    public function calcularAluguel(int $dias): float {
        return $dias * DIARIA_CARRO;
    }
}
