<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';

$usuarioModel = new Usuario($conn);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($usuarioModel->listar());
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    if ($usuarioModel->inserir($nome, $email)) {
        echo json_encode(["sucesso" => true, "mensagem" => "Usuário cadastrado com sucesso!"]);
    } else {
        echo json_encode(["erro" => "Erro ao cadastrar usuário."]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $conteudo = file_get_contents("php://input");
    $dados = json_decode($conteudo, true);
    $id = $dados['id'] ?? 0;

    if ($id > 0) {
        if ($usuarioModel->deletar($id)) {
            echo json_encode(["sucesso" => true, "mensagem" => "Usuário excluído com sucesso!"]);
        } else {
            echo json_encode(["erro" => "Erro ao excluir usuário ou usuário não encontrado."]);
        }
    } else {
        echo json_encode(["erro" => "ID do usuário inválido para exclusão."]);
    }
}elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $conteudo = file_get_contents("php://input");
    $dados = json_decode($conteudo, true);
    $id = $dados['id'] ?? 0;
    $nome = $dados['nome'] ?? '';
    $email = $dados['email'] ?? '';
    if ($id > 0) {
        if ($usuarioModel->update($id, $nome, $email)) {
            echo json_encode(["sucesso" => true, "mensagem" => "Usuário atualizado com sucesso!"]);
        } else {
            echo json_encode(["erro" => "Erro ao atualizar usuário ou usuário nao encontrado."]);
        }
    } else {
        echo json_encode(["erro" => "ID do usuário inválido para atualização."]);
    }
}