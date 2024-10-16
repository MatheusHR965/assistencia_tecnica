<?php
    session_start();

    // Incluindo o arquivo de conexão com o banco de dados
    include_once '../PHP/includes/dbconnect.php';

    // Verificando se a conexão foi criada corretamente
    if (!isset($mysqli)) {
        die("Erro: A conexão com o banco de dados não foi estabelecida.");
    }

    // Verificando se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Coletando os dados do formulário
        $nome = $_POST['nome'];
        $nome_social = $_POST['nomesocial'] ?? null;
        $email = $_POST['email'];
        $telefone = $_POST['telefone'] ?? null;
        $celular = $_POST['celular'] ?? null;
        $data_nascimento = $_POST['data_nascimento'] ?? null;
        $tipo_documento = $_POST['tipo_documento'];
        $documento = $_POST['documento'];
        $uf = $_POST['uf'];
        $cidade = $_POST['cidade'];
        $bairro = $_POST['bairro'];
        $rua = $_POST['rua'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'] ?? null;
        $cep = $_POST['cep'] ?? null;
        $senha = $_POST['password']; // Criptografando a senha
        $data_cadastro = date('Y-m-d H:i:s'); // Pegando a data e hora atual
        $status = 'ativo'; // Definindo o status do usuário como ativo

        // Criando a query de inserção
        $sql = "INSERT INTO Usuario (data_cadastro_usu, nome_usu, nome_social, email_usu, telefone_usu, celular_usu, data_nascimento, tipo_do_documento_usu, documento_usu, uf, cidade, bairro, rua, numero, complemento, cep, status_usu, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparando a declaração para evitar SQL Injection
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die("Erro na preparação da declaração: " . $mysqli->error);
        }

        // Vinculando os parâmetros da query aos valores recebidos do formulário
        $stmt->bind_param("ssssssssssssssssss", $data_cadastro, $nome, $nome_social, $email, $telefone, $celular, $data_nascimento, $tipo_documento, $documento, $uf, $cidade, $bairro, $rua, $numero, $complemento, $cep, $status, $senha);

        // Executando a query
        if ($stmt->execute()) {
            // Redirecionando para uma página de sucesso ou exibindo uma mensagem de sucesso
            echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href = 'index.php'; </script>";
        } else {
            // Exibindo uma mensagem de erro
            echo "<script>alert('Erro ao cadastrar o usuário:');</script>";
            error_log($stmt->error);
        }

        // Fechando a declaração
        $stmt->close();
    }

    // Fechando a conexão com o banco de dados
    if (isset($mysqli)) {
        $mysqli->close();
    }

    // Pegando o ano atual
    $ano_atual = date('Y');
    // Definindo a data mínima e máxima para o ano atual
    $data_maxima = $ano_atual . '-12-31';
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
                <i></i>
                <input type="text" id="telefone" name="telefone" placeholder="(00) 1234-5678"><br><br>

                <label for="celular">Celular</label><br>
                <i></i>
                <input type="text" id="celular" name="celular" placeholder="(00) 12345-6789"><br><br>

                <label for="data_nascimento">Data de Nascimento</label><br>
                <i></i>
                <input type="date" id="data_nascimento" name="data_nascimento" min="<?php echo $data_minima; ?>" max="<?php echo $data_maxima; ?>"><br><br>

                <label for="tipo_documento">Tipo de Documento</label><br>
                <select id="tipo_documento" name="tipo_documento" onchange="aplicarMascara()" required><br>
                    <option value="CPF">CPF</option>
                    <option value="CNPJ">CNPJ</option>

                    <script>
                    function aplicarMascara() {
                        const tipoDocumento = document.getElementById("tipo_documento").value;
                        const documento = document.getElementById("documento");

                        if (tipoDocumento === "CPF") {
                            documento.maxLength = 14; // 000.000.000-00
                            documento.placeholder = "000.000.000-00";
                            documento.value = ""; // Reseta o valor
                            documento.oninput = function() {
                                let valor = documento.value.replace(/\D/g, '');
                                if (valor.length > 9) {
                                    valor = valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
                                } else if (valor.length > 6) {
                                    valor = valor.replace(/(\d{3})(\d{3})(\d{3})/, "$1.$2.$3");
                                } else if (valor.length > 3) {
                                    valor = valor.replace(/(\d{3})(\d{3})/, "$1.$2");
                                } else if (valor.length > 0) {
                                    valor = valor.replace(/(\d{3})/, "$1");
                                }
                                documento.value = valor;
                            };
                        } else if (tipoDocumento === "CNPJ") {
                            documento.maxLength = 18; // 00.000.000/0000-00
                            documento.placeholder = "00.000.000/0000-00";
                            documento.value = ""; // Reseta o valor
                            documento.oninput = function() {
                                let valor = documento.value.replace(/\D/g, '');
                                if (valor.length > 12) {
                                    valor = valor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
                                } else if (valor.length > 8) {
                                    valor = valor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})/, "$1.$2.$3/$4");
                                } else if (valor.length > 5) {
                                    valor = valor.replace(/(\d{2})(\d{3})(\d{3})/, "$1.$2.$3");
                                } else if (valor.length > 2) {
                                    valor = valor.replace(/(\d{2})(\d{3})/, "$1.$2");
                                } else if (valor.length > 0) {
                                    valor = valor.replace(/(\d{2})/, "$1");
                                }
                                documento.value = valor;
                            };
                        }
                    }
                </script>

                <label for="documento">Numero do Documento</label><br>
                <i></i>
                <input type="text" id="documento" name="documento" required><br><br>

                <label for="cep">CEP</label><br>
                <i></i>
                <input type="text" id="cep" name="cep" placeholder="00000-000"><br><br>

                <label for="rua">Rua</label><br>
                <i></i>
                <input type="text" id="rua" name="rua" required><br><br>

                <label for="numero">Número</label><br>
                <i></i>
                <input type="text" id="numero" name="numero" required><br><br>

                <label for="bairro">Bairro</label><br>
                <i></i>
                <input type="text" id="bairro" name="bairro" required><br><br>

                <label for="cidade">Cidade</label><br>
                <i></i>
                <input type="text" id="cidade" name="cidade" required><br><br>

                <label for="uf">UF</label><br>
                <select name="uf" required>
                    <option value="">SELECIONE</option>
                    <option value="AC">AC</option>
                    <option value="AL">AL</option>
                    <option value="AP">AP</option>
                    <option value="AM">AM</option>
                    <option value="BA">BA</option>
                    <option value="CE">CE</option>
                    <option value="DF">DF</option>
                    <option value="ES">ES</option>
                    <option value="GO">GO</option>
                    <option value="MA">MA</option>
                    <option value="MT">MT</option>
                    <option value="MS">MS</option>
                    <option value="MG">MG</option>
                    <option value="PA">PA</option>
                    <option value="PB">PB</option>
                    <option value="PR">PR</option>
                    <option value="PE">PE</option>
                    <option value="PI">PI</option>
                    <option value="RJ">RJ</option>
                    <option value="RN">RN</option>
                    <option value="RS">RS</option>
                    <option value="RO">RO</option>
                    <option value="RR">RR</option>
                    <option value="SC">SC</option>
                    <option value="SP">SP</option>
                    <option value="SE">SE</option>
                    <option value="TO">TO</option>
                </select><br><br>

                <label for="complemento">Complemento</label><br>
                <i></i>
                <input type="text" id="complemento" name="complemento"><br><br>

                <label for="password">Senha</label><br>
                <i class="fa-solid fa-key"></i>
                <input type="password" id="password" name="password" required><br><br><br>

                <input type="submit" name="Cadastrar" id="cadastro_submit">
            </div><br>
            </form>
        </div>
    </main>
<?php
    require_once 'footer.php';
?>