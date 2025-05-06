<?php
namespace Interfaces;

// Interface que define os métodos necessários para um veiculo ser locável

interface Locavel {
    public function alugar() : string;
    public function devolver() : string;
    public function isDisponivel() : bool;
}

?>