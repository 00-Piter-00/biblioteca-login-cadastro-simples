<?php 
header('Content-Type: application/json');
require_once 'config.php';

try {
    //O que fazer após realização bem sucedida do login
    
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
        exit;
    } else {
        echo json_encode([
        'status' => 'erro',
        'tipo' => 'erro-interno',
        'mensagem' => 'Ocorreu um erro ao processar sua solicitação.'
        ]);
        exit;
    }
}

?>