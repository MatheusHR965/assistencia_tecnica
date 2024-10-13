<?php
// Incluindo o arquivo de conexão com o banco de dados
include_once '../PHP/includes/dbconnect.php';

// Verificando se a conexão foi criada corretamente
if ($mysqli === false) {
    die("Erro: A conexão com o banco de dados falhou.");
}

// Verificando se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = htmlspecialchars($_POST['nome']);
    $nome_social = isset($_POST['nomesocial']) ? htmlspecialchars($_POST['nomesocial']) : null;
    $email = htmlspecialchars($_POST['email']);
    $telefone = isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : null;
    $celular = isset($_POST['celular']) ? htmlspecialchars($_POST['celular']) : null;
    $data_nascimento = isset($_POST['data_nascimento']) ? $_POST['data_nascimento'] : null;
    $tipo_documento = htmlspecialchars($_POST['tipo_documento']);
    $documento = htmlspecialchars($_POST['documento']);
    $uf = htmlspecialchars($_POST['uf']);
    $cidade = htmlspecialchars($_POST['cidade']);
    $bairro = htmlspecialchars($_POST['bairro']);
    $rua = htmlspecialchars($_POST['rua']);
    $numero = htmlspecialchars($_POST['numero']);
    $complemento = isset($_POST['complemento']) ? htmlspecialchars($_POST['complemento']) : null;
    $cep = isset($_POST['cep']) ? htmlspecialchars($_POST['cep']) : null;
    $senha = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $data_cadastro = date('Y-m-d H:i:s');
    $status = 'ativo';

    // Verificando se o email já está cadastrado
    $sql_verifica_email = "SELECT id_usu FROM Usuario WHERE email_usu = ?";
    $stmt_verifica_email = $mysqli->prepare($sql_verifica_email);
    $stmt_verifica_email->bind_param("s", $email);
    $stmt_verifica_email->execute();
    $stmt_verifica_email->store_result();

    if ($stmt_verifica_email->num_rows > 0) {
        echo "<script>alert('E-mail já cadastrado. Por favor, use outro.');</script>";
    } else {
        // Inserindo os dados no banco de dados
        $sql = "INSERT INTO Usuario (data_cadastro_usu, nome_usu, nome_social, email_usu, telefone_usu, celular_usu, data_nascimento, tipo_do_documento_usu, documento_usu, uf, cidade, bairro, rua, numero, complemento, cep, status_usu, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die("Erro na preparação da declaração: " . $mysqli->error);
        }

        $stmt->bind_param("ssssssssssssssssss", $data_cadastro, $nome, $nome_social, $email, $telefone, $celular, $data_nascimento, $tipo_documento, $documento, $uf, $cidade, $bairro, $rua, $numero, $complemento, $cep, $status, $senha);

        if ($stmt->execute()) {
            // Redireciona o usuário para a página index.php após o cadastro bem-sucedido
            echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='../index.php';</script>";
        } else {
            error_log('Erro ao executar o cadastro: ' . $stmt->error); // Log do erro
            echo "<script>alert('Ocorreu um erro ao processar seu cadastro. Por favor, tente novamente.');</script>";
        }

        $stmt->close();
    }

    $stmt_verifica_email->close();
}

// Fechando a conexão com o banco de dados
$mysqli->close();

?>

<?php
    require_once 'header.php';
?>
    <main>
        <div>
            <form action="" method="post" id="form_cadastro">
                <div id="logo"><img src="../images/logoazul.png" alt="logo"></div>
                <h1>Cadastro</h1>
                <hr>
                <div class="inputs_cadastro">
                <label for="nome">Nome</label><br>
                <i class="fa-solid fa-user"></i>
                <input type="text" id="nome" name="nome" required><br><br>

                <label for="nomesocial">Nome Social</label><br>
                <i class="fa-solid fa-user"></i>
                <input type="text" id="nomesocial" name="nomesocial"><br><br>

                <label for="email">E-mail</label><br>
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="email" name="email" required><br><br>

                <label for="telefone">Telefone</label><br>
                <i class="fa-solid fa-phone"></i>
                <input type="text" id="telefone" name="telefone" placeholder="(00) 1234-5678"><br><br>

                <label for="celular">Celular</label><br>
                <i class="fa-solid fa-mobile"></i>
                <input type="text" id="celular" name="celular" placeholder="(00) 12345-6789"><br><br>

                <label for="data_nascimento">Data de Nascimento</label><br>
                <i class="fa-solid fa-calendar"></i>
                <input type="date" id="data_nascimento" name="data_nascimento"><br><br>

                <label for="tipo_documento">Tipo do Documento</label><br>
                <i class="fa-solid fa-id-card"></i>
                <input type="text" id="tipo_documento" name="tipo_documento" required><br><br>

                <label for="documento">Documento</label><br>
                <i class="fa-solid fa-id-card"></i>
                <input type="text" id="documento" name="documento" required><br><br>

                <label for="cep">CEP</label><br>
                <i class="fa-solid fa-map-pin"></i>
                <input type="text" id="cep" name="cep" placeholder="00000-000"><br><br>

                <label for="rua">Rua</label><br>
                <i class="fa-solid fa-road"></i>
                <input type="text" id="rua" name="rua" required><br><br>

                <label for="numero">Número</label><br>
                <i class="fa-solid fa-home"></i>
                <input type="text" id="numero" name="numero" required><br><br>

                <label for="bairro">Bairro</label><br>
                <i class="fa-solid fa-city"></i>
                <input type="text" id="bairro" name="bairro" required><br><br>

                <label for="cidade">Cidade</label><br>
                <i class="fa-solid fa-building"></i>
                <input type="text" id="cidade" name="cidade" required><br><br>

                <label for="uf">UF</label><br>
                <i class="fa-solid fa-map"></i>
                <input type="text" id="uf" name="uf" required><br><br>

                <label for="complemento">Complemento</label><br>
                <i class="fa-solid fa-home"></i>
                <input type="text" id="complemento" name="complemento"><br><br>

                <label for="password">Senha</label><br>
                <i class="fa-solid fa-key"></i>
                <input type="password" id="password" name="password" required><br><br><br>

                <input type="submit" name="Cadastrar" id="cadastro_submit" value="Cadastrar">
            </div><br>
            </form>
        </div>
    </main>
<?php
    require_once 'footer.php';
?>