<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    //Resgatar valores enviados
    $data = json_decode(file_get_contents('php://input'), true);
    $nome = trim($data['nome'] ?? '');
    $senha = trim($data['senha'] ?? '');

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

    //Verificar se a conta existe
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE nome = ?");
    $stmt->execute([$nome]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $senhaHash = $row['senha'];
        
        //Caso exista verificar 
        if (password_verify($senha, $senhaHash)) {
            echo json_encode([
                'status' => 'sucesso',
                'tipo' => 'login-feito',
                'mensagem' => 'Login realizado com sucesso'
            ]);
            exit;
        } else {
            echo json_encode([
                'status' => 'erro',
                'tipo' => 'senha-incorreta',
                'mensagem' => 'A senha inserida não condiz com o nome.'
            ]);
            exit;
        }
    } else {
        echo json_encode([
            'status' => 'erro',
            'tipo' => 'conta-inexistente',
            'mensagem' => 'Nenhuma conta encontrada com esse nome!'
        ]);
        exit;
    }
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
