<?php
namespace Services;

use Models\{Veiculo, Carro, Moto};
use Service\Auth;

// Classe para gerenciar a locação

class Locadora {
    private array $veiculos = [];


    public function __construct()
    {
        $this->carregarVeiculos();

    }

    private function carregarVeiculos() : void
    {
        if (file_exists(ARQUIVO_JSON)) {

            // Verifica se o arquivo JSON existe e carrega os dados
            $dados = json_decode(file_get_contents(ARQUIVO_JSON),true);
        

            foreach ($dados as $dado){

                if ($dado['tipo'] == 'carro') {
                    $veiculo = new Carro($dado['modelo'], $dado['placa']);
                } else {
                    $veiculo = new Moto($dado['modelo'], $dado['placa']);
                };

                $veiculo->setDisponivel($dado['disponivel']);

                $this->veiculos[] = $veiculo;
            }
        } 
    }

    // Salvar veículos 
    private function salvarVeiculos() : void{
        $dados = [];

        foreach ($this->veiculos as $veiculo) {
            $dados[] = [
                'tipo' => ($veiculo instanceof Carro) ? 'carro' : 'moto',
                'modelo' => $veiculo->getModelo(),
                'placa' => $veiculo->getPlaca(),
                'disponivel' => $veiculo->isDisponivel()
            ];

            $dir = dirname(ARQUIVO_JSON);

            // Verifica se o diretório existe, se não existir cria o diretório
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            file_put_contents(ARQUIVO_JSON, json_encode($dados, JSON_PRETTY_PRINT));



        }

        

    }


    // adicionar veículo

    public function adicionarVeiculo(Veiculo $veiculo) : void
    {
        $this->veiculos[] = $veiculo;
        $this->salvarVeiculos();
    }

    public function deletarVeiculo(string $modelo, string $placa) : string
    {
        foreach ($this->veiculos as $key => $veiculo) {
            // Verifica se o veículo existe por modelo e placa
            if ($veiculo->getModelo() === $modelo && $veiculo->getPlaca() === $placa) {
                unset($this->veiculos[$key]); // Remove o veículo do array
                
                // reorganiza os valores
                $this->veiculos = array_values($this->veiculos); // Reindexa o array

                // salva as alterações no arquivo JSON
                $this->salvarVeiculos();

                return "Veículo '{$modelo}' com placa '{$placa}' removido com sucesso!";
            }
        }

        return "Veículo '{$modelo}' com placa '{$placa}' não encontrado!";
    }


    public function alugarVeiculo(string $modelo, int $dias =1): string{
        foreach ($this->veiculos as $veiculo) {
           
            if ($veiculo->getModelo() === $modelo && $veiculo->isDisponivel()) {
                
                $valorAluguel = $veiculo->calcularAluguel($dias);

                // marcar como alugado
                $mensagem = $veiculo->alugar();

                $this->salvarVeiculos(); // Salva as alterações no arquivo JSON
                return "{$mensagem}, Veículo '{$modelo}' alugado por {$dias} dias. Valor total: R$" . number_format($valorAluguel, 2, ',', '.');
            }
        }

        return "Veículo '{$modelo}' não disponível para locação!";
    }


    public function devolverVeiculo(string $modelo): string{
        foreach ($this->veiculos as $veiculo) {
            // Verifica se o veículo existe e está alugado
            if ($veiculo->getModelo() === $modelo && !$veiculo->isDisponivel()) {
                $mensagem = $veiculo->devolver(); // Marca como devolvido
                $this->salvarVeiculos(); // Salva as alterações no arquivo JSON
                return "{$mensagem}, Veículo '{$modelo}' devolvido com sucesso!";
            }
        }

        return "Veículo '{$modelo}' não encontrado ou já devolvido!";
    }


    public function listarVeiculos(): array
    {
       return $this->veiculos;
    }

    // calcular valor total da locação
    public function calcularAluguel(string $tipo, int $dias): float
    {
        if($tipo === 'carro') {
            return (new Carro('','')) ->calcularAluguel($dias);
            
        } elseif ($tipo === 'moto') {
            return (new Moto('','')) ->calcularAluguel($dias);
        } 
        return (new Veiculo('','')) ->calcularAluguel($dias);
    }

}




?>