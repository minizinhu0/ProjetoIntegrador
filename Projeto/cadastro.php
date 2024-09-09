<?php
include('conexao.php');

if(isset($_POST['email']) && isset($_POST['senha'])) {

    if(strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if(strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {

        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        // Verifica se o e-mail já existe no banco de dados
        $sql_check_email = "SELECT * FROM usuarios WHERE email = '$email'";
        $result_check_email = $mysqli->query($sql_check_email) or die("Falha na execução do código SQL: " . $mysqli->error);

        if($result_check_email->num_rows > 0) {
            // E-mail já existe
            echo "E-mail já cadastrado. Tente outro.";
        } else {
            // Hash da senha para segurança
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            // Insere o novo usuário no banco de dados
            $sql_code = "INSERT INTO usuarios (email, senha) VALUES ('$email', '$senhaHash')";
            $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

            if($sql_query) {
                echo "Usuário cadastrado com sucesso!";
            } else {
                echo "Falha ao cadastrar o usuário.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h1>Cadastrar Novo Usuário</h1>
    <form action="" method="POST">
        <p>
            <label>E-mail</label>
            <input type="email" name="email">
        </p>
        <p>
            <label>Senha</label>
            <input type="password" name="senha">
        </p>
        <p>
            <button type="submit">Cadastrar</button>
        </p>
    </form>
    <p>
        <button onclick="window.location.href='index.php'">Voltar</button>
    </p>
</body>
</html>
