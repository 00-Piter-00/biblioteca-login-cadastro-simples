//Função para fazer cadastro
async function cadastro(nome, senha) {
    const response = await fetch('cadastro.php', {
        method: 'post',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringifi({nome, senha})
    });
    const result = await response.json();
    return result;
}

//Função para fazer login
async function login(nome, senha) {
    const response = await fetch('login.php', {
        method: 'post',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringifi({nome, senha})
    });
    const result = await response.json();
    return result;
}

//Função caso deseje implementar algo ao login ser realizado
async function sucessoLogin(nome) {
    const response = await fetch('sucessoLogin.php', {
        method: 'post',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringifi({nome})
    });
    const result = await response.json();
    return result;
}