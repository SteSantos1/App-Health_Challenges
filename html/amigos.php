<?php
require_once __DIR__ . '/../php/verifica_sessao.php';
require_once __DIR__ . '/../php/conexao.php';
require_once __DIR__ . '/../php/lista_amigos.php';

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

// RF33: lista de amigos do usuário logado (solicitações aceitas)
$amigos = buscar_amigos($pdo, (int) $_SESSION['id_usuario']);

// Busca solicitações de amizade pendentes recebidas pelo usuário logado (RF31 / RF32)
$stmt_pendentes = $pdo->prepare(
    "SELECT a.id_usuario_remetente, a.data_conexao, u.nome
     FROM amizade a
     INNER JOIN usuario u ON u.id_usuario = a.id_usuario_remetente
     WHERE a.id_usuario_destinatario = :id_usuario
       AND a.status = 'pendente'
     ORDER BY a.data_conexao DESC"
);
$stmt_pendentes->execute([':id_usuario' => $_SESSION['id_usuario']]);
$solicitacoes_recebidas = $stmt_pendentes->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amigos | Health Challenges</title>
    <?php include __DIR__ . '/../css/app_style.php'; ?>
    <style>
        #form-busca {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 10px 12px;
            align-items: end;
            margin-bottom: 24px;
        }

        #form-busca label {
            grid-column: 1 / -1;
            color: var(--texto);
            font-weight: 800;
        }

        #form-busca input {
            width: 100%;
            min-width: 0;
            padding: 13px 16px;
            border: 1px solid var(--borda);
            border-radius: 14px;
            background-color: var(--branco);
            color: var(--texto);
            font: 600 1rem var(--fonte);
            outline: none;
        }

        #form-busca input:focus {
            border-color: var(--verde-forte);
            box-shadow: 0 0 0 3px rgba(0, 153, 102, 0.15);
        }

        #form-busca button,
        .usuario-resultado button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            border: 1px solid transparent;
            border-radius: 14px;
            background-color: var(--verde-forte);
            box-shadow: 0 6px 16px rgba(0, 153, 102, 0.2);
            color: var(--branco);
            font: 700 1rem var(--fonte);
            cursor: pointer;
            transition: background-color 0.15s, transform 0.1s;
        }

        #form-busca button:hover,
        .usuario-resultado button:hover {
            background-color: var(--verde-escuro);
        }

        #form-busca button:active,
        .usuario-resultado button:active {
            transform: scale(0.98);
        }

        .usuario-resultado {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px 20px;
            margin-bottom: 14px;
            padding: 18px 22px;
        }

        .usuario-resultado > p:first-child {
            min-width: 0;
            margin: 0;
            color: var(--texto);
            font-size: 1.05rem;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .usuario-resultado form {
            margin: 0;
        }

        .usuario-resultado [data-status-solicitacao]:empty {
            display: none;
        }

        .usuario-resultado [data-status-solicitacao]:not(:empty) {
            grid-column: 1 / -1;
            margin: 0;
        }

        .conteudo > .aviso-app {
            margin-top: 16px;
        }

        /* RF33: lista de amigos */
        .lista-amigos {
            margin-top: 28px;
        }

        .lista-amigos h3 {
            padding: 22px 22px 14px;
            font-size: 1.2rem;
            border-bottom: 1px solid var(--borda);
        }

        .lista-amigos .vazio {
            padding: 22px;
            margin: 0;
        }

        .linha-amigo {
            display: grid;
            grid-template-columns: 52px minmax(0, 1fr);
            align-items: center;
            gap: 16px;
            padding: 14px 22px;
        }

        .linha-amigo .nome {
            font-size: 1.1rem;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        @media (max-width: 520px) {
            #form-busca {
                grid-template-columns: minmax(0, 1fr);
            }

            #form-busca label {
                grid-column: auto;
            }

            .usuario-resultado {
                grid-template-columns: minmax(0, 1fr);
            }

            .usuario-resultado form,
            .usuario-resultado button {
                width: 100%;
            }

            .usuario-resultado [data-status-solicitacao]:not(:empty) {
                grid-column: auto;
            }

            .linha-amigo {
                grid-template-columns: 40px minmax(0, 1fr);
                gap: 10px;
                padding: 12px 16px;
            }

            .linha-amigo .avatar {
                width: 40px;
                height: 40px;
                font-size: 0.85rem;
            }
        }
    </style>
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
        <?php elseif (($_GET['solicitacao'] ?? '') === 'aceita'): ?>
            <p class="aviso-app sucesso" role="status">Solicitação de amizade aceita!</p>
        <?php elseif (($_GET['solicitacao'] ?? '') === 'recusada'): ?>
            <p class="aviso-app sucesso" role="status">Solicitação de amizade recusada.</p>
        <?php elseif (($_GET['erro'] ?? '') === 'solicitacao'): ?>
            <p class="aviso-app erro" role="alert">Não foi possível processar esta solicitação.</p>
        <?php endif; ?>

        <form id="form-busca" action="amigos.php" method="post">
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
            <article class="cartao-app usuario-resultado">
                <p><?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?></p>

                <form action="../php/enviar_solicitacao.php" method="post" data-form-solicitacao>
                    <input
                        type="hidden"
                        name="id_destinatario"
                        value="<?= (int) $usuario['id_usuario'] ?>"
                    >
                    <button type="submit" class="btn btn-primario">Enviar solicitação</button>
                </form>
                <p role="status" aria-live="polite" data-status-solicitacao></p>
            </article>
        <?php endforeach; ?>

        <section class="cartao-app lista-amigos" aria-label="Meus amigos">
            <h3>Meus amigos (<?= count($amigos) ?>)</h3>

            <?php if (empty($amigos)): ?>
                <p class="texto-suave vazio">Você ainda não adicionou nenhum amigo.</p>
            <?php else: ?>
                <div class="lista-linhas">
                    <?php foreach ($amigos as $amigo): ?>
                        <div class="linha-amigo">
                            <span class="avatar"><?= htmlspecialchars(iniciais($amigo['nome'])) ?></span>
                            <div class="nome"><?= htmlspecialchars($amigo['nome'], ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="cartao-app com-padding" style="margin-top: 24px;">
            <h3 class="cartao-titulo">
                <span>Solicitações recebidas (<?= count($solicitacoes_recebidas) ?>)</span>
            </h3>

            <?php if (empty($solicitacoes_recebidas)): ?>
                <p class="texto-suave">Você não tem solicitações de amizade pendentes.</p>
            <?php else: ?>
                <div class="lista-linhas">
                    <?php foreach ($solicitacoes_recebidas as $pedido): ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 14px 0; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span class="avatar"><?= htmlspecialchars(iniciais($amigo['nome'])) ?></span>
                                <strong><?= htmlspecialchars($pedido['nome'], ENT_QUOTES, 'UTF-8') ?></strong>
                            </div>

                            <form action="../php/responder_amizade.php" method="post" style="display: flex; gap: 10px; margin: 0;">
                                <input type="hidden" name="id_remetente" value="<?= (int) $pedido['id_usuario_remetente'] ?>">
                                <button type="submit" name="acao" value="aceitar" class="btn btn-primario" style="padding: 8px 16px; font-size: 0.95rem;">
                                    Aceitar
                                </button>
                                <button type="submit" name="acao" value="recusar" class="btn btn-contorno" style="padding: 8px 16px; font-size: 0.95rem;">
                                    Recusar
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
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