<?php require_once __DIR__ . '/../php/grupos_dados.php'; ?>
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

        .grupo-acoes {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-left: auto;
        }

        .grupo-acoes .btn {
            padding: 8px 16px;
        }

        .lista-membros {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 60vh;
            overflow-y: auto;
        }

        .membro-gerenciado {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px;
            border: 1px solid var(--borda);
            border-radius: 14px;
        }

        .membro-gerenciado > div:first-child {
            min-width: 0;
        }

        .membro-acoes {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .membro-acoes .btn {
            padding: 8px 12px;
        }

        .sem-grupos {
            padding: 40px 30px;
            text-align: center;
        }

        .sem-grupos h3 {
            font-size: 1.3rem;
        }

        .sem-grupos p {
            color: var(--texto-suave);
            margin-top: 8px;
        }

        @media (max-width: 600px) {
            .grupo-cartao {
                padding: 20px;
            }

            .grupo-rodape,
            .grupo-acoes,
            .membro-gerenciado {
                flex-wrap: wrap;
            }

            .grupo-topo {
                flex-wrap: wrap;
            }

            .grupo-topo .emoji-caixa {
                width: 56px;
                height: 56px;
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

        <?php if ($aviso): ?>
            <p class="aviso-app <?= $aviso[0] ?>" role="<?= $aviso[0] === 'erro' ? 'alert' : 'status' ?>"><?= htmlspecialchars($aviso[1]) ?></p>
        <?php endif; ?>

        <div class="lista-grupos">
            <?php if (!$grupos): ?>
                <div class="cartao-app sem-grupos">
                    <h3>Você ainda não participa de nenhum grupo</h3>
                    <p>Crie um grupo para competir com seus colegas ou peça para entrar em um.</p>
                </div>
            <?php endif; ?>

            <?php foreach ($grupos as $grupo): ?>
                <article class="cartao-app grupo-cartao">
                    <div class="grupo-topo">
                        <span class="emoji-caixa" aria-hidden="true"><?= htmlspecialchars(iniciais($grupo['nome'])) ?></span>
                        <div class="grupo-info">
                            <h3>
                                <?= htmlspecialchars($grupo['nome']) ?>
                                <?php if ($grupo['admin']): ?>
                                    <span class="selo admin">Admin</span>
                                <?php endif; ?>
                            </h3>
                            <p><?= htmlspecialchars($grupo['descricao']) ?></p>
                            <div class="grupo-numeros">
                                <span><?= icone('grupos', 18) ?> <?= $grupo['total_membros'] ?> <?= $grupo['total_membros'] === 1 ? 'membro' : 'membros' ?></span>
                                <span><span class="raio"><?= icone('raio', 18) ?></span> <?= $grupo['desafios_ativos'] ?> <?= $grupo['desafios_ativos'] === 1 ? 'desafio ativo' : 'desafios ativos' ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="grupo-rodape">
                        <div class="avatares">
                            <?php foreach ($grupo['membros'] as $i => $sigla): ?>
                                <span class="avatar pequeno" style="background-color: <?= $cores_avatar[$i % count($cores_avatar)] ?>" aria-hidden="true"><?= htmlspecialchars($sigla) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="grupo-acoes">
                            <?php if ($grupo['admin']): ?>
                                <button type="button" class="btn btn-contorno" onclick="document.getElementById('janela-editar-<?= $grupo['id'] ?>').showModal()">Editar</button>
                                <button type="button" class="btn btn-contorno" onclick="document.getElementById('janela-membros-<?= $grupo['id'] ?>').showModal()">Gerenciar membros</button>
                            <?php endif; ?>
                            <a href="#" class="ver-detalhes">Ver detalhes <?= icone('seta', 16) ?></a>
                        </div>
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
        <form class="form-app" method="post" action="../php/criar_grupo.php">
            <div>
                <label for="nome-grupo">Nome do grupo</label>
                <input type="text" id="nome-grupo" name="nome" maxlength="100" required>
            </div>
            <div>
                <label for="descricao-grupo">Descrição</label>
                <textarea id="descricao-grupo" name="descricao" rows="3" maxlength="500" required></textarea>
            </div>
            <p class="texto-suave">Você será o administrador do grupo.</p>
            <button type="submit" class="btn btn-primario btn-bloco">Criar grupo</button>
        </form>
    </dialog>

    <?php foreach ($grupos as $grupo): ?>
        <?php if ($grupo['admin']): ?>
            <dialog class="janela" id="janela-membros-<?= $grupo['id'] ?>">
                <div class="janela-topo">
                    <h3>Membros de <?= htmlspecialchars($grupo['nome']) ?></h3>
                    <button type="button" class="janela-fechar" aria-label="Fechar" onclick="this.closest('dialog').close()"><?= icone('fechar', 18) ?></button>
                </div>
                <div class="lista-membros">
                    <?php foreach ($grupo['membros_detalhados'] as $membro): ?>
                        <?php $membro_admin = $membro['papel'] === 'Administrador'; ?>
                        <div class="membro-gerenciado">
                            <div>
                                <strong><?= htmlspecialchars($membro['nome']) ?></strong>
                                <?php if ($membro_admin): ?>
                                    <span class="selo admin">Admin</span>
                                <?php else: ?>
                                    <span class="texto-suave">Membro</span>
                                <?php endif; ?>
                            </div>
                            <?php if ($membro['id'] !== $id_usuario): ?>
                                <div class="membro-acoes">
                                    <?php if (!$membro_admin): ?>
                                        <form method="post" action="../php/gerenciar_membro_grupo.php">
                                            <input type="hidden" name="id_grupo" value="<?= $grupo['id'] ?>">
                                            <input type="hidden" name="id_membro" value="<?= $membro['id'] ?>">
                                            <input type="hidden" name="acao" value="promover">
                                            <button type="submit" class="btn btn-contorno">Promover</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if (!$membro_admin || $grupo['total_admins'] > 1): ?>
                                        <form method="post" action="../php/gerenciar_membro_grupo.php">
                                            <input type="hidden" name="id_grupo" value="<?= $grupo['id'] ?>">
                                            <input type="hidden" name="id_membro" value="<?= $membro['id'] ?>">
                                            <input type="hidden" name="acao" value="remover">
                                            <button type="submit" class="btn btn-contorno">Remover</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </dialog>
            <dialog class="janela" id="janela-editar-<?= $grupo['id'] ?>">
                <div class="janela-topo">
                    <h3>Editar grupo</h3>
                    <button type="button" class="janela-fechar" aria-label="Fechar" onclick="this.closest('dialog').close()"><?= icone('fechar', 18) ?></button>
                </div>
                <form class="form-app" method="post" action="../php/editar_grupo.php">
                    <input type="hidden" name="id_grupo" value="<?= $grupo['id'] ?>">
                    <div>
                        <label for="nome-grupo-<?= $grupo['id'] ?>">Nome do grupo</label>
                        <input type="text" id="nome-grupo-<?= $grupo['id'] ?>" name="nome" maxlength="100" value="<?= htmlspecialchars($grupo['nome']) ?>" required>
                    </div>
                    <div>
                        <label for="descricao-grupo-<?= $grupo['id'] ?>">Descrição</label>
                        <textarea id="descricao-grupo-<?= $grupo['id'] ?>" name="descricao" rows="3" maxlength="500" required><?= htmlspecialchars($grupo['descricao']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primario btn-bloco">Salvar alterações</button>
                </form>
            </dialog>
        <?php endif; ?>
    <?php endforeach; ?>

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
