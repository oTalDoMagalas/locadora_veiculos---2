<?php


namespace Models;

// Classe abstrata para todos os veículos

abstract class Veiculo {
    protected string $modelo;
    protected string $placa;
    protected bool $disponivel;

    public function __construct(string $modelo, string $placa) {
        $this->modelo = $modelo;
        $this->placa = $placa;
        $this->disponivel = true;
    }
    //Função para cálculo de aluguel
    abstract public function calcularAluguel(int $dias) : float;
}


?>