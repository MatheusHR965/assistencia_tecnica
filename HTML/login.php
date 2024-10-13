<?php
session_start();

include_once '../PHP/includes/dbconnect.php'; // Incluindo a conexão com o banco

// Inicializa a variável de erro
$erro_login = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizando e validando os dados
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST["password"]);

    // Verifica se os campos estão preenchidos
    if (empty($email)) {
        $erro_login = "E-mail é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro_login = "Formato de e-mail inválido.";
    } elseif (empty($password)) {
        $erro_login = "Senha é obrigatória.";
    } else {
        // Prepara a consulta
        $stmt = $mysqli->prepare("SELECT id_usu, nome_usu, senha FROM `Usuario` WHERE email_usu = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se o usuário existe 
        if ($result->num_rows > 0) {
            // Busca os dados do usuário
            $user = $result->fetch_assoc();

            // Verifica a senha usando password_verify
            if (password_verify($password, $user['senha'])) {
                $_SESSION['logado'] = true; // Marcar como logado
                $_SESSION['nome'] = $user['nome_usu']; // Nome do usuário na sessão
                $_SESSION['id'] = $user['id_usu']; // ID do usuário na sessão

                // Redireciona após o login
                echo '<script>window.location.href = "../index.php";</script>';
                exit;
            } else {
                $erro_login = "E-mail ou senha incorretos.";
            }
        } else {
            $erro_login = "E-mail ou senha incorretos.";
        }

        $stmt->close();
    }
}

// Fechar a conexão com o banco
$mysqli->close();
?>

<?php require_once 'header.php'; ?>

<main>
    <div>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" id="form_login">
            <div id="logo"><img src="../images/logoazul.png" alt="logo"></div>
            <h1>Login</h1>
            <hr>

            <!-- Exibe a mensagem de erro se houver -->
            <?php if (!empty($erro_login)): ?>
                <p style="color: red;"><?= $erro_login ?></p>
            <?php endif; ?>

            <label for="email">E-mail</label><br>
            <input type="email" name="email" id="email" required><br>
            <label for="password">Senha</label><br>
            <input type="password" name="password" id="password" required><br>
            
            <input type="checkbox" name="manter_logado" id="manter_logado">
            <label for="manter_logado">Manter logado</label><br>
            
            <button id="confirma" type="submit" value="logar">Logar</button>
            <p id="senharecovery">Esqueci a senha</p>
        </form>
    </div>
</main>

<?php 

require_once 'footer.php';
 
?>