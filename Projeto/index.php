<?php
include('conexao.php');

// Adicione sua chave secreta do reCAPTCHA aqui
$secretKey = '6LezijoqAAAAAAUlBoiC_Z-jx_1vO3h9Wa2BCfeV';

if(isset($_POST['email']) || isset($_POST['senha'])) {

    if(strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if(strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {
        
        // Verificar reCAPTCHA
        $recaptchaResponse = $_POST['recaptcha_response'];
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
        $responseKeys = json_decode($response, true);

        // Verificar se a verificação do reCAPTCHA foi bem-sucedida
        if(intval($responseKeys["success"]) !== 1) {
            echo "Por favor, complete o reCAPTCHA.";
            exit;
        }

        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if($quantidade == 1) {
            $usuario = $sql_query->fetch_assoc();

            if(!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            header("Location: painel.php");

        } else {
            echo "Falha ao logar! E-mail ou senha incorretos";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Carregar API do reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render=6LezijoqAAAAAHPYSOC9BaYAg6d2dk74mg4vl47u"></script>
    <script>
        function onSubmit(e) {
            e.preventDefault(); // Previne o envio do formulário
            grecaptcha.execute('6LezijoqAAAAAHPYSOC9BaYAg6d2dk74mg4vl47u', {action: 'login'}).then(function(token) {
                document.getElementById('recaptcha_response').value = token;
                document.getElementById('loginForm').submit(); // Envia o formulário após obter o token
            });
        }
    </script>
</head>
<body>
    <h1>Acesse sua conta</h1>
    <form id="loginForm" action="" method="POST" onsubmit="onSubmit(event)">
        <p>
            <label>E-mail</label>
            <input type="text" name="email">
        </p>
        <p>
            <label>Senha</label>
            <input type="password" name="senha">
        </p>
        <input type="hidden" id="recaptcha_response" name="recaptcha_response">
        <p>
            <button type="submit">Entrar</button>
        </p>
        <p>
            <button onclick="window.location.href='cadastro.php'">Criar Cadastro</button>
        </p>
    </form>
</body>
</html>