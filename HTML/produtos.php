<?php
session_start();
include_once '../PHP/includes/dbconnect.php';
    $result = $mysqli->query("SELECT * FROM Produto WHERE status_prod != 'Desabilitado'"); // Apenas produtos ativos
?>
<?php
    require_once 'header.php';
?>
    <main>
        <table>
            <tr>
                <th>Marca</th>
                <th>Descriçao</th>
                <th>Nome Produto</th>
            </tr>
        <?php while ($produto = $result->fetch_assoc()): ?>
            <tr id="divisoriaprod">
                <div class="fundoprod">
                    <div id="produtos"><img src="/images/maquinadelavar1.png" alt="">
                    <td><?= htmlspecialchars($produto['marca']) ?></td>
                    <td><?= htmlspecialchars($produto['desc_prod']) ?></td>
                    </div>
                    <td><a href="modelo.php?id=<?= $produto['id_prod'] ?>">Ver detalhes</a></td>
                </div>
            </tr>
        <?php endwhile; ?>
        </table>
    </main>
<?php
    require_once 'footer.php';
?>