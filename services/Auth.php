<?php

namespace Services;

class Auth
{
    private array $usuarios = [];

    // método construtor

    public function __construct()
    {
        $this->carregarUsuarios();
    }

    // método para carregar os usuários do arquivo JSON
    private function carregarUsuarios(): void
    {
        if (file_exists('ARQUIVO_USUARIOS')) {
            $conteudo = json_decode(file_get_contents('ARQUIVO_USUARIOS'), true);
            $this->usuarios = is_array($conteudo) ? $conteudo : [];
        } else {
            $this->usuarios = [
                [
                    'usuario' => 'admin',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'perfil' => 'admin'
                ],
                [
                    'usuario' => 'usuario',
                    'password' => password_hash('usuario123', PASSWORD_DEFAULT),
                    'perfil' => 'usuario'
                ]
            ];

            $this->salvarUsuarios();
        }
    }

    // função para salvar os usuários no arquivo JSON
    private function salvarUsuarios(): void
    {
        $dir = dirname(DIARIA_CARRO);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents(ARQUIVO_USUARIO, json_encode($this->usuarios, JSON_PRETTY_PRINT));
    }

    public function login(string $username, string $password): bool
    {
        foreach ($this->usuarios as $usuario) {
            if ($usuario['usuario'] === $username && password_verify($password, $usuario['password'])) {
                $_SESSION['auth'] = [
                    'usuario' => true,
                    'username' => $username,
                    'perfil' => $usuario['perfil']
                ];
                return true; // login realizado
            }
        }
        return false; // login falhou
    }
    public function logout(): void
    {
        session_destroy();
    }
    public function verificarLogin(string $perfil): bool
    {
        return isset($_SESSION['auth']) && $_SESSION['auth']['perfil'] === $perfil;
    }

    public function isPerfil(string $perfil): bool
    {
        return isset($_SESSION['auth']) && $_SESSION['auth']['perfil'] === $perfil;
    }
    public static function isAdmin(): bool
    {
        return self::isPerfil('admin');
    }
    public static function getUsuario(): ?array
    {
        return $_SESSION['auth'] ?? null;
    }
}
