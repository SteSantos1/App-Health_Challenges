<?php
require_once __DIR__ . '/../php/verifica_sessao.php';
require_once __DIR__ . '/../php/conexao.php';

$pagina_atual = 'amigos';
$titulo_topo = 'Amigos';

$usuarios = [];
$termo = '';
$buscou = $_SERVER['REQUEST_METHOD'] === 'POST';

if ($buscou) {
    $termo = trim($_POST['nome_user'] ?? '');

    if ($termo !== '') {
        $stmt = $pdo->prepare(
            "SELECT nome, id_usuario
             FROM usuario
             WHERE id_usuario <> :id_usuario
               AND nome LIKE :nome
             LIMIT 20"
        );

        $stmt->execute([
            ':id_usuario' => $_SESSION['id_usuario'],
            ':nome' => '%' . $termo . '%',
        ]);

        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amigos | Health Challenges</title>
    <?php include __DIR__ . '/../css/app_style.php'; ?>
</head>
<body class="app">
    <?php include __DIR__ . '/cabecalho.php'; ?>

    <main class="conteudo">
        <div class="cabeca-pagina">
            <div>
                <h1>Amigos</h1>
                <p>Encontre usuários e envie solicitações de amizade.</p>
            </div>
        </div>

        <?php if (($_GET['erro'] ?? '') === 'id'): ?>
            <p class="aviso-app erro" role="alert">Este usuário não é válido.</p>
        <?php elseif (($_GET['erro'] ?? '') === 'usuario'): ?>
            <p class="aviso-app erro" role="alert">Este usuário não existe.</p>
        <?php elseif (($_GET['erro'] ?? '') === 'remetente-e-destinatario'): ?>
            <p class="aviso-app erro" role="alert">Não pode enviar solicitações a si mesmo.</p>
        <?php elseif (($_GET['erro'] ?? '') === 'ja-solicitado'): ?>
            <p class="aviso-app erro" role="alert">Já existe uma solicitação de amizade ou já é amigo deste usuário.</p>
        <?php elseif (($_GET['solicitacao'] ?? '') === 'enviada'): ?>
            <p class="aviso-app sucesso" role="status">Solicitação enviada com   sucesso!</p>
        <?php endif; ?>

        <form action="amigos.php" method="post">
            <label for="nome_user">Nome do usuário:</label>
            <input
                type="text"
                name="nome_user"
                id="nome_user"
                value="<?= htmlspecialchars($termo, ENT_QUOTES, 'UTF-8') ?>"
                required
            >
            <button type="submit">Buscar</button>
        </form>

        <?php if ($buscou && $termo === ''): ?>
            <p>Digite um nome para pesquisar.</p>
        <?php elseif ($buscou && empty($usuarios)): ?>
            <p>Nenhum usuário encontrado.</p>
        <?php endif; ?>

        <?php foreach ($usuarios as $usuario): ?>
            <article class="cartao-app com-padding">
                <p><?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?></p>

                <form action="../php/enviar_solicitacao.php" method="post" data-form-solicitacao>
                    <input
                        type="hidden"
                        name="id_destinatario"
                        value="<?= (int) $usuario['id_usuario'] ?>"
                    >
                    <button type="submit">Enviar solicitação</button>
                </form>
                <p role="status" aria-live="polite" data-status-solicitacao></p>
            </article>
        <?php endforeach; ?>
    </main>

    <script>
        document.querySelectorAll('[data-form-solicitacao]').forEach(function (formulario) {
            formulario.addEventListener('submit', async function (evento) {
                evento.preventDefault();

                const botao = formulario.querySelector('button[type="submit"]');
                const status = formulario.parentElement.querySelector('[data-status-solicitacao]');
                botao.disabled = true;
                status.textContent = 'Enviando solicitação...';

                try {
                    const resposta = await fetch(formulario.action, {
                        method: 'POST',
                        body: new FormData(formulario),
                        headers: { 'Accept': 'application/json' }
                    });
                    const resultado = await resposta.json();

                    status.textContent = resultado.mensagem;
                    status.className = resultado.sucesso ? 'aviso-app sucesso' : 'aviso-app erro';

                    if (resultado.sucesso) {
                        botao.textContent = 'Solicitação enviada';
                    } else {
                        botao.disabled = false;
                    }
                } catch (erro) {
                    status.textContent = 'Não foi possível enviar a solicitação. Tente novamente.';
                    status.className = 'aviso-app erro';
                    botao.disabled = false;
                }
            });
        });
    </script>
</body>
</html>