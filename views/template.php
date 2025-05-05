<?php
require_once __DIR__ . '/../services/Auth.php';

use services\Auth;

$usuario = Auth::getUsuario();


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Página ADM - Locadora de Automóveis</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <link rel="stylesheet" href="style.css">
        <!-- ///\\\///\\\///\\\///\\\///\\\///\\\///\\\///\\\///\\\///\\\ -->
        <style>
            .action-wrapper {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                justify-content: flex-start;

            }

            .btn-group-actions {
                display: flex;
                gap: 0.5rem;
                align-items: center;
            }

            .delete-btn {
                order: 1;

            }

            .rent-group {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                order: 2;
            }

            .days-input {
                width: 60px;
                padding: 0.25rem 0.5rem;
                text-align: center;
            }

            @media (max-width: 768px) {
                .action-wrapper {
                    flex-direction: column;
                    align-items: stretch;
                }

                .btn-group-actions {
                    flex-direction: column;
                }

                .rent-group {
                    order: 1;
                    width: 100%;
                }

                .delete-btn {
                    order: 1;
                    width: 100%;
                }

                .days-input {
                    width: 100% !important;
                }
            }
        </style>
    </head>

<body class="container py-4">
    <div class="container py-4">
        <!-- Barra de informações de usuário -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center inicio">
                    <h2>Sistema de Locadora de Veículos</h2>
                    <div class="d-flex align-items-center gap-3 user-info mx-3">
                        <span class="user-icon">
                            <i class="bi bi-person-circle" style="font-size:24px;"></i>
                        </span>
                        <!-- Bem vindo,[usuário] -->
                        <span class="welcome-text">
                            Bem-vindo, <strong><?=htmlspecialchars($usuario['username']) ?></strong>!
                        </span>
                        <!-- botão de logout -->
                        <a href="?logout=1" class="btn btn-outline-danger d-flex align-items-center gap-1">
                            <i class="bi bi-box-arrow-right"></i>
                            Sair
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($mensagem): ?>
            <?php if (isset($mensagem)): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($mensagem) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Formulário para adicionar novo veículo -->
    <div class="row same-height-row">
    <?php if (Auth::isAdmin()): ?>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="mb-0">Adicionar novo automóvel 🚗</h4>
                </div>
                <div class="card-body">
                    <form action="" method="post" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="modelo" class="form-label">Modelo:</label>
                            <input type="text" class="form-control" name="modelo" id="modelo" required>
                            <div class="invalid-feedback">
                                Informe um modelo válido!
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="placa" class="form-label">Placa:</label>
                            <input type="text" class="form-control" name="placa" id="placa" required>
                            <div class="invalid-feedback">
                                Informe uma placa válida!
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo:</label>
                            <select name="tipo" id="tipo" class="form-select" required>
                                <option value="" disabled selected>Selecione um tipo</option>
                                <option value="Carro">Carro</option>
                                <option value="Moto">Moto</option>
                            </select>
                        </div>
                        <button class="btn btn-success w-100" type="submit" name="adicionar">Adicionar Veículo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
        <!-- Formulário para cálcular de aluguel -->
    <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="mb-0">
                            Calcular a previsão de aluguel 💰
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="post" class="nrrds-validation" novalidate>
                            <div class="mb-3">
                                <label for="tipo" class="form-label">
                                    Tipo de veículo:
                                </label>
                                <select name="tipo" id="tipo" class="form-select" required>
                                    <option value="carro">Carro</option>
                                    <option value="moto">Moto</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="quantidade" class="form-label">
                                    Quantidade de dia:
                                </label>
                                <input type="Number" class="form-control" name="dias_calculos" value="1" required>
                            </div>
                            <button class="btn btn-success w-100" type="submit" name="calcular">Calcular</button>
                        </form>
                    </div>
                </div>
            </div>
        </div

</body>