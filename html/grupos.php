<?php
require_once __DIR__ . '/../php/verifica_sessao.php';

$pagina_atual = 'grupos';
$titulo_topo = 'Grupos';

// Exemplo: virá das tabelas grupo e membro_usuario (Sprint 5)
$grupos = [
    ['nome' => 'Guerreiros do Fitness', 'emoji' => '💪', 'descricao' => 'Grupo focado em atividades físicas intensas e consistentes', 'admin' => true,  'membros' => ['AS', 'CM', 'RC', 'BL', 'TS'], 'desafios_ativos' => 3, 'posicao' => 1, 'pontos' => 1240],
    ['nome' => 'Leitores Ávidos',       'emoji' => '📚', 'descricao' => 'Para quem ama livros e quer cultivar o hábito da leitura diária', 'admin' => false, 'membros' => ['AR', 'PA', 'AS', 'LN'], 'desafios_ativos' => 2, 'posicao' => 3, 'pontos' => 1240],
    ['nome' => 'Saúde & Bem-estar',     'emoji' => '🌿', 'descricao' => 'Equilíbrio entre corpo e mente para uma vida mais saudável', 'admin' => false, 'membros' => ['MS', 'CO', 'AS', 'JF', 'PA', 'TS'], 'desafios_ativos' => 4, 'posicao' => 4, 'pontos' => 1240],
];

$medalhas = [1 => '🥇', 2 => '🥈', 3 => '🥉'];
$cores_avatar = ['#00BC7D', '#2B7FFF', '#F6339A', '#FE9A00', '#8E51FF', '#00B8DB'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupos | Health Challenges</title>
    <?php include __DIR__ . '/../css/app_style.php'; ?>
    <style>
        .lista-grupos {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .grupo-cartao {
            padding: 30px;
        }

        .grupo-topo {
            display: flex;
            gap: 22px;
            align-items: flex-start;
        }

        .grupo-topo .emoji-caixa {
            width: 72px;
            height: 72px;
            font-size: 2rem;
            background-color: var(--verde-claro);
        }

        .grupo-info {
            flex: 1;
            min-width: 0;
        }

        .grupo-info h3 {
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .grupo-info p {
            color: var(--texto-suave);
            font-size: 1.1rem;
            margin-top: 6px;
        }

        .grupo-numeros {
            display: flex;
            gap: 28px;
            flex-wrap: wrap;
            margin-top: 16px;
            color: var(--texto-suave);
        }

        .grupo-numeros span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .grupo-numeros .raio {
            color: var(--laranja);
        }

        .grupo-posicao {
            text-align: right;
        }

        .grupo-posicao small {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            color: var(--texto-fraco);
        }

        .grupo-posicao strong {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 1.3rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .grupo-posicao em {
            font-style: normal;
            color: var(--texto-fraco);
        }

        .grupo-rodape {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 22px;
            padding-top: 20px;
            border-top: 1px solid var(--borda);
        }

        .avatares {
            display: flex;
        }

        .avatares .avatar + .avatar {
            margin-left: -8px;
        }

        .ver-detalhes {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--texto-suave);
            font-weight: 600;
        }

        .ver-detalhes:hover {
            color: var(--verde-escuro);
        }

        @media (max-width: 600px) {
            .grupo-cartao {
                padding: 20px;
            }

            .grupo-topo {
                flex-wrap: wrap;
            }

            .grupo-topo .emoji-caixa {
                width: 56px;
                height: 56px;
            }

            .grupo-posicao {
                text-align: left;
                width: 100%;
            }
        }
    </style>
</head>

<body class="app">
    <?php include __DIR__ . '/cabecalho.php'; ?>

    <main class="conteudo">
        <div class="cabeca-pagina">
            <div>
                <h1>Grupos</h1>
                <p>Participe de grupos e compita com seus colegas.</p>
            </div>
            <div class="grupo-botoes">
                <button type="button" class="btn btn-contorno" onclick="document.getElementById('janela-entrar').showModal()">Entrar</button>
                <button type="button" class="btn btn-primario" onclick="document.getElementById('janela-criar').showModal()"><?= icone('mais', 20) ?> Criar</button>
            </div>
        </div>

        <div class="lista-grupos">
            <?php foreach ($grupos as $grupo): ?>
                <article class="cartao-app grupo-cartao">
                    <div class="grupo-topo">
                        <span class="emoji-caixa" aria-hidden="true"><?= $grupo['emoji'] ?></span>
                        <div class="grupo-info">
                            <h3>
                                <?= $grupo['nome'] ?>
                                <?php if ($grupo['admin']): ?>
                                    <span class="selo admin">Admin</span>
                                <?php endif; ?>
                            </h3>
                            <p><?= $grupo['descricao'] ?></p>
                            <div class="grupo-numeros">
                                <span><?= icone('grupos', 18) ?> <?= count($grupo['membros']) ?> membros</span>
                                <span><span class="raio"><?= icone('raio', 18) ?></span> <?= $grupo['desafios_ativos'] ?> desafios ativos</span>
                            </div>
                        </div>
                        <div class="grupo-posicao">
                            <small>SUA POSIÇÃO</small>
                            <strong>
                                <?php if (isset($medalhas[$grupo['posicao']])): ?>
                                    <span aria-label="<?= $grupo['posicao'] ?>º lugar"><?= $medalhas[$grupo['posicao']] ?></span>
                                <?php else: ?>
                                    <em>#<?= $grupo['posicao'] ?></em>
                                <?php endif; ?>
                                <?= $grupo['pontos'] ?> pts
                            </strong>
                        </div>
                    </div>
                    <div class="grupo-rodape">
                        <div class="avatares">
                            <?php foreach ($grupo['membros'] as $i => $sigla): ?>
                                <span class="avatar pequeno" style="background-color: <?= $cores_avatar[$i % count($cores_avatar)] ?>"><?= $sigla ?></span>
                            <?php endforeach; ?>
                        </div>
                        <a href="#" class="ver-detalhes">Ver detalhes <?= icone('seta', 16) ?></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </main>

    <dialog class="janela" id="janela-criar">
        <div class="janela-topo">
            <h3>Criar grupo</h3>
            <button type="button" class="janela-fechar" aria-label="Fechar" onclick="this.closest('dialog').close()"><?= icone('fechar', 18) ?></button>
        </div>
        <form class="form-app" method="post" action="#">
            <div>
                <label for="nome-grupo">Nome do grupo</label>
                <input type="text" id="nome-grupo" name="nome" maxlength="100" required>
            </div>
            <div>
                <label for="descricao-grupo">Descrição</label>
                <textarea id="descricao-grupo" name="descricao" rows="3" required></textarea>
            </div>
            <p class="texto-suave">Você será o administrador do grupo.</p>
            <button type="submit" class="btn btn-primario btn-bloco">Criar grupo</button>
        </form>
    </dialog>

    <dialog class="janela" id="janela-entrar">
        <div class="janela-topo">
            <h3>Entrar em um grupo</h3>
            <button type="button" class="janela-fechar" aria-label="Fechar" onclick="this.closest('dialog').close()"><?= icone('fechar', 18) ?></button>
        </div>
        <form class="form-app" method="post" action="#">
            <div>
                <label for="busca-grupo">Nome do grupo</label>
                <input type="text" id="busca-grupo" name="grupo" required>
            </div>
            <p class="texto-suave">O administrador do grupo vai receber sua solicitação.</p>
            <button type="submit" class="btn btn-primario btn-bloco">Solicitar ingresso</button>
        </form>
    </dialog>
</body>

</html>
