<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Produto.php';


$produtoModel = new Produto($conn);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($produtoModel->listar());
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $quantidade = $_POST['quantidade'] ?? 0;
    $preco = $_POST['preco'] ?? 0;

    if ($produtoModel->inserir($nome, $quantidade, $preco)) {
        echo json_encode(["sucesso" => true, "mensagem" => "Produto cadastrado com sucesso!"]);
    } else {
        echo json_encode(["erro" => "Erro ao cadastrar produto."]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $conteudo = file_get_contents("php://input");
    $dados = json_decode($conteudo, true);
    $id = $dados['id'] ?? 0;

    if ($id > 0) {
        if ($produtoModel->deletar($id)) {
            echo json_encode(["sucesso" => true, "mensagem" => "Produto excluído com sucesso!"]);
        } else {
            echo json_encode(["erro" => "Erro ao excluir produto ou produto não encontrado."]);
        }
    } else {
        echo json_encode(["erro" => "ID do produto inválido."]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $conteudo = file_get_contents("php://input");
    $dados = json_decode($conteudo, true);
    $id = $dados['id'] ?? 0;
    $nome = $dados['nome'] ?? '';
    $quantidade = $dados['quantidade'] ?? 0;
    $preco = $dados['preco'] ?? 0;
    if ($id > 0) {
        if ($produtoModel->update($id, $nome, $quantidade, $preco)) {
            echo json_encode(["sucesso" => true, "mensagem" => "Produto atualizado com sucesso!"]);
        } else {
            echo json_encode(["erro" => "Erro ao atualizar produto ou produto nao encontrado."]);
        }
    } else {
        echo json_encode(["erro" => "ID do produto inválido para atualização."]);
    }
}