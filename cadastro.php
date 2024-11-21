<?php
//Definir método json e incluir o arquivo de conexão com o banco de dados
header('Content-Type: application/json');
require_once 'config.php';

try {
    //Resgatar dados enviados
    $data = json_decode(file_get_contents('php://input'), true);
    $nome = trim($data['nome'] ?? '');
    $senha = trim($data['senha'] ?? '');
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    //Validar dados
    if (empty($nome) || empty($senha)) {
        //Caso o valor do nome ou da senha esteja vázio
        echo json_encode([
            'status' => 'erro',
            'tipo' => 'dados-invalidos',
            'mensagem' => 'Preencha todos os campos!'
        ]);
        exit;
    } else {
        //Verificar tamanho dos valores enviados
        if (strlen($nome) < 3 || strlen($nome) > 50 || strlen($senha) < 6) {
            echo json_encode([
                'status' => 'erro',
                'tipo' => 'dados-invalidos',
                'mensagem' => 'O Nome deve ter entre 3 à 50 caracteres, e a senha de ter pelo menos 6 caracteres!'
            ]);
            exit;
        }
    }

    //Verificar se o nome ja está registrado
    $stmt = $conexao->prepare("SELECT id FROM usuarios WHERE nome = ?");
    $stmt->execute([$nome]);

    if ($stmt->fetch()) {
        echo json_encode([
            'status' => 'erro',
            'tipo' => 'nome-existente',
            'mensagem' => 'Já existe uma conta com esse nome!'
        ]);
        exit;
    }

    //Registra caso ainda não exista conta com esse nome
    $stmt = $conexao->prepare("INSERT INTO usuarios (nome, senha) VALUES (?, ?)");
    $stmt->execute([$nome, $senhaHash]);
    echo json_encode([
        'status' => 'sucesso',
        'tipo' => 'conta-registrada',
        'mensagem' => 'Conta registrada com sucesso!'
    ]);
} catch (Exception $e) {
    //O que fazer ao ter algum erro no código
    $debug = false;
    if ($debug) {
        echo json_encode([
            'status' => 'erro',
            'tipo' => 'erro-interno',
            'mensagem' => 'Ocorreu um erro ao processar sua solicitação.',
            'detalhes' => $e->getMessage()
        ]);
    } else {
        echo json_encode([
            'status' => 'erro',
            'tipo' => 'erro-interno',
            'mensagem' => 'Ocorreu um erro ao processar sua solicitação.'
        ]);
    }
    exit;
}
