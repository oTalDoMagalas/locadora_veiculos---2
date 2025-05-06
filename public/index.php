<?php
// Incluir o autoload do composer para carregar automaticamente as classes utilizadas
require_once __DIR__ . '/../vendor/autoload.php';
//Incluir o arquivo com as variáveis
require_once __DIR__ . '/../config/config.php';

session_start();

// Inserir a classe Locadora, Auth
use Services\{Locadora, Auth};

// Inporta as classes Carro e moto
use Models\{Carro, Moto};

// Verifica se já foi autenticado
if (Auth::verificarLogin()) {
    //redireciona para a página inicial
    header("Location: index.php");
    exit;
}

// Condição para logout
if (isset($_GET['logout'])) {
    (new Auth())->logout();
    header('Location: index.php');
    exit;
}
// criar uma instância da classe Locadora
$locadora = new Locadora();

$mensagem = '';

$usuario = Auth::getUsuario();

// verificar os dados do formulário via POST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // verificar se requer permissão de administrador
    if(isset($_POST['adicionar']) || isset($_POST['deletar']) || isset($_POST['alugar']) || isset($_POST['devolver'])) {

        if(!Auth::isAdmin()){
            $mensagem = "você não tem permissão para acessar esta página.";
            goto renderizar ;

        }
    }
    if (isset($_POST['adicionar'])){
        $modelo = $_POST['modelo'];
        $placa = $_POST['placa'];
        $tipo = $_POST['tipo'];

        $veiculo = ($tipo === 'carro') ? new Carro($modelo, $placa) : new Moto($modelo, $placa);

        $locadora->adicionarVeiculo($veiculo);

        $mensagem = "Veículo adicionado com sucesso!";
    }
    elseif(isset($_POST['alugar'])){
        $dias = isset($_POST['dias']) ? (int)$_POST['dias'] : 1;
        $mensagem = $locadora->alugarVeiculo($_POST['modelo'])($dias);

    }
    elseif(isset($_POST['devolver'])){
        $mensagem = $locadora->devolverVeiculo($_POST['modelo']);
    }
    elseif(isset($_POST['deletar'])){
        $mensagem = $locadora->devolverVeiculo($_POST['modelo'], $_POST['placa']);
    }
    elseif(isset($_POST['calcular'])){
        $dias = (int)$_POST['dias_calculos'];
        $tipo = $_POST['tipo_calculos'];
        $valor = $locadora->calcularPrevisaoAluguel($tipo, $dias);

        $mensagem = "Previsão de valor para {$dias} dias: R$ ". number_format($valor, 2, ',', '.');
    }
}

renderizar:
require_once __DIR__ . '/../views/templete.php';
?>