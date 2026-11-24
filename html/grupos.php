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
            flex-wrap: wrap;
            gap: 12px;
            margin-left: auto;
        }

        .grupo-acoes .btn {
            padding: 8px 16px;
        }

        .btn-participando {
            padding: 8px 14px;
            font-size: 0.9rem;
            border-radius: 10px;
            background-color: var(--verde-claro);
            color: var(--verde-escuro);
            border: 1px solid var(--verde-suave);
        }

        .desafio-grupo-cartao {
            margin-top: 16px;
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .desafio-grupo-cabeca {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            flex-wrap: wrap;
        }

        .desafio-grupo-cabeca p {
            color: var(--texto-suave);
            margin-top: 4px;
        }

        .desafio-grupo-meta {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--texto);
        }

        .meu-progresso-box {
            padding: 12px 14px;
            border-radius: 14px;
            background-color: var(--cinza-claro);
            border: 1px solid var(--borda);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .meu-progresso-topo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .form-progresso-grupo {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .form-progresso-grupo label {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--texto-suave);
        }

        .form-progresso-grupo input[type="number"] {
            width: 110px;
            padding: 8px 12px;
            border: 1px solid var(--borda);
            border-radius: 10px;
            background-color: var(--branco);
            color: var(--texto);
            font: 700 0.95rem var(--fonte);
        }

        .form-progresso-grupo .btn {
            padding: 8px 14px;
            font-size: 0.9rem;
        }

        details.progresso-integrantes-desafio {
            border-top: 1px solid var(--borda);
            padding-top: 10px;
        }

        details.progresso-integrantes-desafio summary {
            cursor: pointer;
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--verde-escuro);
            list-style: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        details.progresso-integrantes-desafio summary::-webkit-details-marker {
            display: none;
        }

        details.progresso-integrantes-desafio summary::before {
            content: '▸';
            font-size: 0.9rem;
            transition: transform 0.15s;
        }

        details.progresso-integrantes-desafio[open] summary::before {
            transform: rotate(90deg);
        }

        .lista-progresso-membros {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 12px;
        }

        .linha-progresso-membro {
            display: grid;
            grid-template-columns: 38px minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid var(--borda);
            background-color: var(--branco);
        }

        .linha-progresso-membro.eu {
            background-color: var(--verde-claro);
            border-color: var(--verde-suave);
        }

        .linha-progresso-membro .avatar {
            width: 38px;
            height: 38px;
            font-size: 0.82rem;
        }

        .progresso-membro-centro {
            min-width: 0;
        }

        .progresso-membro-nome {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .progresso-membro-numeros {
            text-align: right;
            font-size: 0.9rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .progresso-membro-numeros small {
            display: block;
            color: var(--texto-suave);
            font-weight: 600;
        }

        .linha-progresso-membro:not(.eu) .barra > span {
            background-color: var(--barra-neutra);
            background-image: none;
        }

        dialog.janela.janela-detalhes-grupo {
            width: min(760px, calc(100% - 32px));
            max-height: 88vh;
            overflow-y: auto;
        }

        .abas-detalhes-grupo {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .painel-detalhes-grupo[hidden] {
            display: none;
        }

        .subtitulo-detalhes-grupo {
            color: var(--texto-suave);
            margin-bottom: 16px;
            font-size: 0.98rem;
        }

        .secao-detalhes-titulo {
            font-size: 1.1rem;
            margin: 18px 0 10px;
        }

        .bloco-desafio-progresso {
            padding: 16px;
            border: 1px solid var(--borda);
            border-radius: 16px;
            background-color: var(--cinza-claro);
            margin-bottom: 14px;
        }

        .bloco-desafio-topo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 6px;
        }

        .sua-posicao-grupo {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px 24px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--verde) 0%, var(--verde-forte) 100%);
            color: var(--branco);
            margin-bottom: 18px;
        }

        .sua-posicao-grupo .avatar {
            position: relative;
            width: 54px;
            height: 54px;
        }

        .sua-posicao-grupo .avatar em {
            position: absolute;
            right: -4px;
            bottom: -4px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: var(--ambar);
            color: var(--texto);
            border: 2px solid var(--branco);
            font-style: normal;
            font-size: 0.75rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sua-posicao-grupo strong {
            display: block;
            font-family: var(--fonte-titulo);
            font-size: 1.35rem;
        }

        .sua-posicao-grupo .total {
            margin-left: auto;
            text-align: right;
        }

        .sua-posicao-grupo .total strong {
            font-size: 1.9rem;
            font-weight: 800;
        }

        .podio-grupo {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            align-items: end;
            margin-bottom: 18px;
            padding: 18px 14px 0;
            border: 1px solid var(--borda);
            border-radius: 18px;
            background-color: var(--branco);
        }

        .podio-grupo .podio-lugar {
            text-align: center;
        }

        .podio-grupo .podio-lugar .avatar {
            width: 58px;
            height: 58px;
            font-size: 1.1rem;
            margin-bottom: 6px;
        }

        .podio-grupo .podio-lugar.primeiro .avatar {
            width: 68px;
            height: 68px;
        }

        .podio-grupo .podio-lugar strong {
            display: block;
            font-size: 1rem;
            overflow-wrap: anywhere;
        }

        .podio-grupo .pts-podio {
            font-size: 0.9rem;
            color: var(--texto-suave);
            font-weight: 700;
        }

        .podio-grupo .primeiro .pts-podio {
            color: var(--ambar-texto);
        }

        .podio-grupo .degrau {
            margin-top: 8px;
            border-radius: 14px 14px 0 0;
            display: flex;
            justify-content: center;
            padding-top: 12px;
            font-size: 1.5rem;
        }

        .podio-grupo .coroa {
            display: block;
            font-size: 1.25rem;
            margin-bottom: -4px;
        }

        .podio-grupo .segundo .degrau  { height: 74px;  background-color: var(--podio-2); border: 2px solid var(--borda-forte); border-bottom: none; }
        .podio-grupo .primeiro .degrau { height: 100px; background-color: var(--podio-1); border: 2px solid var(--ambar-borda); border-bottom: none; }
        .podio-grupo .terceiro .degrau { height: 58px;  background-color: var(--podio-3); border: 2px solid var(--ambar-texto); border-bottom: none; }

        .classificacao-grupo {
            border: 1px solid var(--borda);
            border-radius: 18px;
            overflow: hidden;
            background-color: var(--branco);
        }

        .linha-ranking-grupo {
            display: grid;
            grid-template-columns: 44px 42px minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
        }

        .linha-ranking-grupo.eu {
            background-color: var(--verde-claro);
            border-left: 4px solid var(--verde-forte);
        }

        .linha-ranking-grupo .posicao {
            font-family: var(--fonte-titulo);
            font-weight: 800;
            color: var(--texto-fraco);
            text-align: center;
        }

        .linha-ranking-grupo .avatar {
            width: 42px;
            height: 42px;
            font-size: 0.85rem;
        }

        .linha-ranking-grupo .nome {
            font-weight: 700;
            margin-bottom: 6px;
        }

        .linha-ranking-grupo:not(.eu) .barra > span {
            background-color: var(--barra-neutra);
            background-image: none;
        }

        .linha-ranking-grupo .valor {
            text-align: right;
            font-family: var(--fonte-titulo);
            font-weight: 800;
            font-size: 1.1rem;
        }

        .linha-ranking-grupo .valor small {
            display: block;
            font-family: var(--fonte);
            font-size: 0.78rem;
            color: var(--texto-suave);
            font-weight: 600;
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

            .sua-posicao-grupo {
                padding: 16px;
                flex-wrap: wrap;
            }

            .linha-progresso-membro {
                grid-template-columns: 34px minmax(0, 1fr);
            }

            .progresso-membro-numeros {
                grid-column: 1 / -1;
                text-align: left;
                display: flex;
                justify-content: space-between;
                align-items: center;
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

    <?php if (!empty($convites_recebidos)): ?>
        <section style="margin-bottom: 30px;">
            <h2 style="margin-bottom: 16px;">Convites recebidos</h2>

            <div class="lista-grupos">
                <?php foreach ($convites_recebidos as $convite): ?>
                    <article class="cartao-app grupo-cartao">
                        <div class="grupo-topo">
                            <span class="emoji-caixa" aria-hidden="true">
                                <?= htmlspecialchars(iniciais($convite['nome_grupo'])) ?>
                            </span>

                            <div class="grupo-info">
                                <h3><?= htmlspecialchars($convite['nome_grupo']) ?></h3>

                                <p><?= htmlspecialchars($convite['descricao'] ?? '') ?></p>

                                <p>
                                    Convite enviado por
                                    <?= htmlspecialchars($convite['nome_remetente']) ?>
                                </p>
                            </div>
                        </div>

                        <div class="grupo-rodape">
                            <div class="grupo-acoes">
                                <form method="post" action="../php/responder_convite_grupo.php">
                                    <input type="hidden" name="id_convite"
                                        value="<?= (int) $convite['id_convite'] ?>">
                                    <input type="hidden" name="acao" value="aceitar">

                                    <button type="submit" class="btn btn-primario">
                                        Aceitar
                                    </button>
                                </form>

                                <form method="post" action="../php/responder_convite_grupo.php">
                                    <input type="hidden" name="id_convite"
                                        value="<?= (int) $convite['id_convite'] ?>">
                                    <input type="hidden" name="acao" value="recusar">

                                    <button type="submit" class="btn btn-contorno">
                                        Recusar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
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
                                <span><?= icone('medalha', 18) ?> <?= $grupo['minha_posicao'] ?>º no ranking do grupo (<?= $grupo['meus_pontos'] ?> pts)</span>
                            </div>
                        </div>
                    </div>
                    <?php foreach ($desafios_por_grupo[$grupo['id']] ?? [] as $desafio): ?>
                        <article class="cartao-app desafio-grupo-cartao">
                            <div class="desafio-grupo-cabeca">
                                <div>
                                    <strong><?= htmlspecialchars($desafio['titulo']) ?></strong>
                                    <p><?= htmlspecialchars($desafio['descricao']) ?></p>
                                    <p class="desafio-grupo-meta">
                                        Meta: <?= (int) $desafio['valor_meta'] ?>
                                        <?= htmlspecialchars($desafio['tipo_meta']) ?>
                                        · Até <?= date('d/m', strtotime($desafio['data_fim'])) ?>
                                    </p>
                                </div>

                                <?php if ($desafio['participando']): ?>
                                    <span class="btn btn-participando">
                                        <?= icone('check', 16) ?>
                                        <?= $desafio['meu_concluido'] ? 'Concluído' : 'Participando' ?>
                                    </span>
                                <?php else: ?>
                                    <form method="post" action="../php/participar_desafio_grupo.php">
                                        <input type="hidden"
                                            name="id_grupo"
                                            value="<?= (int) $grupo['id'] ?>">
                                        <input type="hidden"
                                            name="id_desafio"
                                            value="<?= (int) $desafio['id_desafio'] ?>">
                                        <button type="submit" class="btn btn-primario">
                                            Participar
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                            <?php if ($desafio['participando']): ?>
                                <div class="meu-progresso-box">
                                    <div class="meu-progresso-topo">
                                        <span>Seu progresso: <?= (int) $desafio['meu_progresso'] ?> / <?= (int) $desafio['valor_meta'] ?> <?= htmlspecialchars($desafio['tipo_meta']) ?></span>
                                        <span><?= (int) $desafio['meu_percentual'] ?>%</span>
                                    </div>
                                    <div class="barra" role="progressbar" aria-label="Seu progresso em <?= htmlspecialchars($desafio['titulo']) ?>" aria-valuenow="<?= (int) $desafio['meu_percentual'] ?>" aria-valuemin="0" aria-valuemax="100">
                                        <span style="width: <?= (int) $desafio['meu_percentual'] ?>%"></span>
                                    </div>
                                    <form class="form-progresso-grupo" method="post" action="../php/atualizar_progresso_grupo.php">
                                        <input type="hidden" name="id_grupo" value="<?= (int) $grupo['id'] ?>">
                                        <input type="hidden" name="id_desafio" value="<?= (int) $desafio['id_desafio'] ?>">
                                        <label for="progresso-input-<?= (int) $grupo['id'] ?>-<?= (int) $desafio['id_desafio'] ?>">
                                            Progresso atingido (<?= htmlspecialchars($desafio['tipo_meta']) ?>):
                                        </label>
                                        <input type="number"
                                            id="progresso-input-<?= (int) $grupo['id'] ?>-<?= (int) $desafio['id_desafio'] ?>"
                                            name="progresso_atual"
                                            min="0"
                                            max="1000000"
                                            value="<?= (int) $desafio['meu_progresso'] ?>"
                                            required>
                                        <button type="submit" class="btn btn-primario">Salvar progresso</button>
                                    </form>
                                </div>
                            <?php endif; ?>

                            <details class="progresso-integrantes-desafio">
                                <summary>
                                    Progresso dos integrantes neste desafio (<?= count($desafio['progresso_membros']) ?>)
                                </summary>
                                <div class="lista-progresso-membros">
                                    <?php foreach ($desafio['progresso_membros'] as $membro_prog): ?>
                                        <div class="linha-progresso-membro <?= $membro_prog['eu'] ? 'eu' : '' ?>">
                                            <span class="avatar" style="background-color: <?= $membro_prog['cor'] ?>" aria-hidden="true">
                                                <?= htmlspecialchars($membro_prog['iniciais']) ?>
                                            </span>
                                            <div class="progresso-membro-centro">
                                                <div class="progresso-membro-nome">
                                                    <span><?= htmlspecialchars($membro_prog['nome']) ?><?= $membro_prog['eu'] ? ' (Você)' : '' ?></span>
                                                    <?php if ($membro_prog['concluido']): ?>
                                                        <span class="selo facil">Concluído</span>
                                                    <?php elseif ($membro_prog['participando']): ?>
                                                        <span class="selo medio">Em andamento</span>
                                                    <?php else: ?>
                                                        <span class="selo">Não participa</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="barra" role="progressbar" aria-label="Progresso de <?= htmlspecialchars($membro_prog['nome']) ?>" aria-valuenow="<?= (int) $membro_prog['percentual'] ?>" aria-valuemin="0" aria-valuemax="100">
                                                    <span style="width: <?= (int) $membro_prog['percentual'] ?>%"></span>
                                                </div>
                                            </div>
                                            <div class="progresso-membro-numeros">
                                                <?= (int) $membro_prog['progresso_atual'] ?> / <?= (int) $membro_prog['valor_meta'] ?> <?= htmlspecialchars($desafio['tipo_meta']) ?>
                                                <small><?= (int) $membro_prog['percentual'] ?>%</small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </details>
                        </article>
                    <?php endforeach; ?>

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
                                <button type="button" class="btn btn-contorno" onclick="document.getElementById('janela-convidar-<?= $grupo['id'] ?>').showModal()">Convidar usuário</button>
                                <button type="button" class="btn btn-contorno" onclick="document.getElementById('janela-desafio-<?= $grupo['id'] ?>').showModal()">Criar desafio</button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-contorno" onclick="abrirDetalhesGrupo(<?= $grupo['id'] ?>, 'ranking')"><?= icone('ranking', 18) ?> Ranking do grupo</button>
                            <a href="#janela-detalhes-<?= $grupo['id'] ?>" class="ver-detalhes" onclick="abrirDetalhesGrupo(<?= $grupo['id'] ?>, 'progresso'); return false;">Ver detalhes <?= icone('seta', 16) ?></a>
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

<dialog class="janela" id="janela-entrar">
    <div class="janela-topo">
        <h3>Explorar grupos</h3>
        <button type="button" class="janela-fechar"
                aria-label="Fechar"
                onclick="this.closest('dialog').close()">
            <?= icone('fechar', 18) ?>
        </button>
    </div>

    <div class="form-app">
        <?php if (empty($grupos_disponiveis)): ?>
            <p class="texto-suave">Não existem grupos disponíveis no momento.</p>
        <?php else: ?>

            <?php foreach ($grupos_disponiveis as $grupo_disponivel): ?>
                <div class="cartao-app" style="padding: 16px; margin-bottom: 12px;">
                    <strong>
                        <?= htmlspecialchars($grupo_disponivel['nome_grupo']) ?>
                    </strong>

                    <p class="texto-suave">
                        <?= htmlspecialchars($grupo_disponivel['descricao'] ?? '') ?>
                    </p>

                    <p class="texto-suave">
                        <?= (int) $grupo_disponivel['total_membros'] ?>
                        membros
                    </p>

                    <?php if ($grupo_disponivel['status_solicitacao'] === 'pendente'): ?>

                        <span class="selo">Solicitação pendente</span>

                    <?php else: ?>

                        <form method="post" action="../php/solicitar_ingresso_grupo.php">
                            <input type="hidden" name="id_grupo"
                                   value="<?= (int) $grupo_disponivel['id_grupo'] ?>">

                            <button type="submit" class="btn btn-primario btn-bloco">
                                Solicitar ingresso
                            </button>
                        </form>

                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</dialog>

    <?php foreach ($grupos as $grupo): ?>
        <?php if ($grupo['admin']): ?>
            <dialog class="janela" id="janela-membros-<?= $grupo['id'] ?>">
                <div class="janela-topo">
                    <h3>Membros de <?= htmlspecialchars($grupo['nome']) ?></h3>
                    <button type="button" class="janela-fechar" aria-label="Fechar" onclick="this.closest('dialog').close()"><?= icone('fechar', 18) ?></button>
                </div>
                <div class="lista-membros">

    <?php
    $solicitacoes_grupo = $solicitacoes_por_grupo[$grupo['id']] ?? [];
    ?>

    <?php if (!empty($solicitacoes_grupo)): ?>
        <h4>Solicitações de ingresso</h4>

        <?php foreach ($solicitacoes_grupo as $solicitacao): ?>
            <div class="membro-gerenciado">
                <div>
                    <strong><?= htmlspecialchars($solicitacao['nome']) ?></strong>
                    <p class="texto-suave">Solicitou entrada no grupo.</p>
                </div>

                <div class="membro-acoes">
                    <form method="post" action="../php/responder_solicitacao_grupo.php">
                        <input type="hidden" name="id_solicitacao"
                            value="<?= (int) $solicitacao['id_solicitacao'] ?>">
                        <input type="hidden" name="acao" value="aprovar">

                        <button type="submit" class="btn btn-primario">
                            Aprovar
                        </button>
                    </form>

                    <form method="post" action="../php/responder_solicitacao_grupo.php">
                        <input type="hidden" name="id_solicitacao"
                            value="<?= (int) $solicitacao['id_solicitacao'] ?>">
                        <input type="hidden" name="acao" value="recusar">

                        <button type="submit" class="btn btn-contorno">
                            Recusar
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

        <hr>
    <?php endif; ?>

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

            <dialog class="janela"
                    id="janela-desafio-<?= $grupo['id'] ?>"
                    aria-labelledby="titulo-desafio-<?= $grupo['id'] ?>">

                <div class="janela-topo">
                    <h3 id="titulo-desafio-<?= $grupo['id'] ?>">
                        Criar desafio interno
                    </h3>
                    <button type="button"
                            class="janela-fechar"
                            aria-label="Fechar"
                            onclick="this.closest('dialog').close()">✕</button>
                </div>

                <form class="form-app"
                    method="post"
                    action="../php/criar_desafio_grupo.php">

                    <input type="hidden"
                        name="id_grupo"
                        value="<?= (int) $grupo['id'] ?>">

                    <div>
                        <label for="titulo-desafio-<?= $grupo['id'] ?>">Título</label>
                        <input type="text"
                            id="titulo-desafio-<?= $grupo['id'] ?>"
                            name="titulo"
                            maxlength="100"
                            required>
                    </div>

                    <div>
                        <label for="descricao-desafio-<?= $grupo['id'] ?>">Descrição</label>
                        <textarea id="descricao-desafio-<?= $grupo['id'] ?>"
                                name="descricao"
                                maxlength="500"
                                required></textarea>
                    </div>

                    <div>
                        <label for="categoria-desafio-<?= $grupo['id'] ?>">Categoria</label>
                        <select id="categoria-desafio-<?= $grupo['id'] ?>"
                                name="categoria"
                                required>
                            <option value="fisica">Física</option>
                            <option value="leitura">Leitura</option>
                            <option value="alimento">Alimentação</option>
                        </select>
                    </div>

                    <div>
                        <label for="tipo-meta-<?= $grupo['id'] ?>">Tipo da meta</label>
                        <input type="text"
                            id="tipo-meta-<?= $grupo['id'] ?>"
                            name="tipo_meta"
                            placeholder="km, horas, porções..."
                            maxlength="30"
                            required>
                    </div>

                    <div>
                        <label for="valor-meta-<?= $grupo['id'] ?>">Valor da meta</label>
                        <input type="number"
                            id="valor-meta-<?= $grupo['id'] ?>"
                            name="valor_meta"
                            min="1"
                            required>
                    </div>

                    <div>
                        <label for="inicio-desafio-<?= $grupo['id'] ?>">Data inicial</label>
                        <input type="date"
                            id="inicio-desafio-<?= $grupo['id'] ?>"
                            name="data_inicio"
                            required>
                    </div>

                    <div>
                        <label for="fim-desafio-<?= $grupo['id'] ?>">Data final</label>
                        <input type="date"
                            id="fim-desafio-<?= $grupo['id'] ?>"
                            name="data_fim"
                            required>
                    </div>

                    <button type="submit" class="btn btn-primario btn-bloco">
                        Criar desafio interno
                    </button>
                </form>
            </dialog>

            <dialog class="janela" id="janela-convidar-<?= $grupo['id'] ?>">
                <div class="janela-topo">
                    <h3>Convidar usuário</h3>

                    <button type="button" class="janela-fechar"
                        aria-label="Fechar"
                        onclick="this.closest('dialog').close()">
                        <?= icone('fechar', 18) ?>
                    </button>
                </div>

                <form class="form-app" method="post"
                    action="../php/enviar_convite_grupo.php">

                    <input type="hidden" name="id_grupo"
                        value="<?= $grupo['id'] ?>">

                    <div>
                        <label for="usuario-convidado-<?= $grupo['id'] ?>">
                            Selecione o usuário
                        </label>

                        <select id="usuario-convidado-<?= $grupo['id'] ?>"
                            name="id_usuario_convidado" required>

                            <option value="">Escolha um usuário</option>

                            <?php
                            $ids_membros = array_column(
                                $grupo['membros_detalhados'],
                                'id'
                            );

                            foreach ($usuarios_para_convidar as $usuario):
                                if (!in_array((int) $usuario['id_usuario'], $ids_membros, true)):
                            ?>
                                <option value="<?= (int) $usuario['id_usuario'] ?>">
                                    <?= htmlspecialchars($usuario['nome']) ?>
                                </option>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primario btn-bloco">
                        Enviar convite
                    </button>
                </form>
            </dialog>
        <?php endif; ?>

        <?php
        $lista_rk = $grupo['ranking'];
        $maior_pts_grupo = max(1, $lista_rk[0]['pontos'] ?? 0);
        $eu_rk = $lista_rk[$grupo['minha_posicao'] - 1] ?? ($lista_rk[0] ?? null);
        $ordem_podio_grupo = count($lista_rk) >= 2 ? [1, 0, 2] : [0];
        $classes_podio_grupo = count($lista_rk) >= 2 ? ['segundo', 'primeiro', 'terceiro'] : ['primeiro'];
        ?>
        <dialog class="janela janela-detalhes-grupo"
                id="janela-detalhes-<?= $grupo['id'] ?>"
                aria-labelledby="titulo-detalhes-<?= $grupo['id'] ?>">
            <div class="janela-topo">
                <h3 id="titulo-detalhes-<?= $grupo['id'] ?>"><?= htmlspecialchars($grupo['nome']) ?></h3>
                <button type="button" class="janela-fechar" aria-label="Fechar" onclick="this.closest('dialog').close()">
                    <?= icone('fechar', 18) ?>
                </button>
            </div>

            <div class="abas-detalhes-grupo" role="tablist" aria-label="Visualizações do grupo <?= htmlspecialchars($grupo['nome']) ?>">
                <button type="button"
                        class="chip ativo"
                        role="tab"
                        aria-selected="true"
                        data-aba-grupo="progresso">
                    <?= icone('grupos', 18) ?> Progresso dos integrantes
                </button>
                <button type="button"
                        class="chip"
                        role="tab"
                        aria-selected="false"
                        data-aba-grupo="ranking">
                    <?= icone('ranking', 18) ?> Ranking do grupo
                </button>
            </div>

            <!-- RF27 – Progresso dos demais integrantes do grupo -->
            <div class="painel-detalhes-grupo" data-painel-grupo="progresso">
                <p class="subtitulo-detalhes-grupo">
                    Acompanhe o desempenho e a evolução de todos os integrantes nos desafios deste grupo.
                </p>

                <h4 class="secao-detalhes-titulo">Resumo geral dos integrantes</h4>
                <div class="lista-progresso-membros">
                    <?php foreach ($lista_rk as $integrante): ?>
                        <div class="linha-progresso-membro <?= $integrante['eu'] ? 'eu' : '' ?>">
                            <span class="avatar" style="background-color: <?= $integrante['cor'] ?>" aria-hidden="true">
                                <?= htmlspecialchars($integrante['iniciais']) ?>
                            </span>
                            <div class="progresso-membro-centro">
                                <div class="progresso-membro-nome">
                                    <span><?= htmlspecialchars($integrante['nome']) ?><?= $integrante['eu'] ? ' (Você)' : '' ?></span>
                                    <?php if ($integrante['papel'] === 'Administrador'): ?>
                                        <span class="selo admin">Admin</span>
                                    <?php endif; ?>
                                </div>
                                <div class="barra" role="progressbar" aria-label="Média de conclusão de <?= htmlspecialchars($integrante['nome']) ?> no grupo" aria-valuenow="<?= (int) $integrante['media_progresso'] ?>" aria-valuemin="0" aria-valuemax="100">
                                    <span style="width: <?= (int) $integrante['media_progresso'] ?>%"></span>
                                </div>
                            </div>
                            <div class="progresso-membro-numeros">
                                <?= (int) $integrante['desafios_concluidos'] ?> / <?= (int) $integrante['total_desafios'] ?> concluídos
                                <small><?= (int) $integrante['media_progresso'] ?>% médio · <?= (int) $integrante['pontos'] ?> pts</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <h4 class="secao-detalhes-titulo">Progresso por desafio do grupo</h4>
                <?php if (empty($grupo['todos_desafios'])): ?>
                    <p class="texto-suave">Nenhum desafio interno foi criado neste grupo ainda.</p>
                <?php else: ?>
                    <?php foreach ($grupo['todos_desafios'] as $desafio_det): ?>
                        <div class="bloco-desafio-progresso">
                            <div class="bloco-desafio-topo">
                                <strong><?= htmlspecialchars($desafio_det['titulo']) ?></strong>
                                <span class="selo <?= $desafio_det['ativo'] ? 'facil' : '' ?>">
                                    <?= $desafio_det['ativo'] ? 'Ativo' : 'Encerrado' ?>
                                </span>
                            </div>
                            <p class="texto-suave" style="font-size: 0.92rem; margin-bottom: 10px;">
                                Meta: <?= (int) $desafio_det['valor_meta'] ?> <?= htmlspecialchars($desafio_det['tipo_meta']) ?>
                                · Até <?= date('d/m/Y', strtotime($desafio_det['data_fim'])) ?>
                            </p>
                            <div class="lista-progresso-membros">
                                <?php foreach ($desafio_det['progresso_membros'] as $membro_prog): ?>
                                    <div class="linha-progresso-membro <?= $membro_prog['eu'] ? 'eu' : '' ?>">
                                        <span class="avatar" style="background-color: <?= $membro_prog['cor'] ?>" aria-hidden="true">
                                            <?= htmlspecialchars($membro_prog['iniciais']) ?>
                                        </span>
                                        <div class="progresso-membro-centro">
                                            <div class="progresso-membro-nome">
                                                <span><?= htmlspecialchars($membro_prog['nome']) ?><?= $membro_prog['eu'] ? ' (Você)' : '' ?></span>
                                                <?php if ($membro_prog['concluido']): ?>
                                                    <span class="selo facil">Concluído</span>
                                                <?php elseif ($membro_prog['participando']): ?>
                                                    <span class="selo medio">Em andamento</span>
                                                <?php else: ?>
                                                    <span class="selo">Não participa</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="barra" role="progressbar" aria-label="Progresso de <?= htmlspecialchars($membro_prog['nome']) ?> em <?= htmlspecialchars($desafio_det['titulo']) ?>" aria-valuenow="<?= (int) $membro_prog['percentual'] ?>" aria-valuemin="0" aria-valuemax="100">
                                                <span style="width: <?= (int) $membro_prog['percentual'] ?>%"></span>
                                            </div>
                                        </div>
                                        <div class="progresso-membro-numeros">
                                            <?= (int) $membro_prog['progresso_atual'] ?> / <?= (int) $membro_prog['valor_meta'] ?> <?= htmlspecialchars($desafio_det['tipo_meta']) ?>
                                            <small><?= (int) $membro_prog['percentual'] ?>%</small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- RF28 – Ranking próprio de cada grupo, independente do ranking geral -->
            <div class="painel-detalhes-grupo" data-painel-grupo="ranking" hidden>
                <p class="subtitulo-detalhes-grupo">
                    Classificação exclusiva deste grupo, calculada apenas pelos desafios internos do grupo (independente do ranking geral).
                </p>

                <?php if ($eu_rk): ?>
                    <section class="sua-posicao-grupo" aria-label="Sua posição no grupo">
                        <span class="avatar" style="background-color: <?= $eu_rk['cor'] ?>" aria-hidden="true">
                            <?= htmlspecialchars($eu_rk['iniciais']) ?>
                            <em><?= (int) $grupo['minha_posicao'] ?></em>
                        </span>
                        <div>
                            <small>SUA POSIÇÃO NO GRUPO</small>
                            <strong><?= (int) $grupo['minha_posicao'] ?>º lugar — <?= htmlspecialchars(explode(' ', trim($eu_rk['nome']))[0]) ?></strong>
                        </div>
                        <div class="total">
                            <strong><?= (int) $eu_rk['pontos'] ?></strong>
                            <small>pts no grupo</small>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if (!empty($lista_rk)): ?>
                    <div class="podio-grupo" style="<?= count($lista_rk) === 1 ? 'grid-template-columns: 1fr;' : '' ?>">
                        <?php foreach ($ordem_podio_grupo as $j_pod => $indice_pod): ?>
                            <?php if (!isset($lista_rk[$indice_pod])) continue; $pessoa_pod = $lista_rk[$indice_pod]; ?>
                            <div class="podio-lugar <?= $classes_podio_grupo[$j_pod] ?>">
                                <?php if ($indice_pod === 0): ?><span class="coroa" aria-hidden="true">👑</span><?php endif; ?>
                                <span class="avatar" style="background-color: <?= $pessoa_pod['cor'] ?>" aria-hidden="true">
                                    <?= htmlspecialchars($pessoa_pod['iniciais']) ?>
                                </span>
                                <strong><?= htmlspecialchars(explode(' ', trim($pessoa_pod['nome']))[0]) ?></strong>
                                <span class="pts-podio"><?= (int) $pessoa_pod['pontos'] ?> pts</span>
                                <div class="degrau">
                                    <span role="img" aria-label="<?= $indice_pod + 1 ?>º lugar"><?= $medalhas_grupo[$indice_pod + 1] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="classificacao-grupo lista-linhas">
                        <?php foreach ($lista_rk as $i_rk => $pessoa_rk): ?>
                            <div class="linha-ranking-grupo <?= $pessoa_rk['eu'] ? 'eu' : '' ?>">
                                <span class="posicao">#<?= $i_rk + 1 ?></span>
                                <span class="avatar" style="background-color: <?= $pessoa_rk['cor'] ?>" aria-hidden="true">
                                    <?= htmlspecialchars($pessoa_rk['iniciais']) ?>
                                </span>
                                <div>
                                    <div class="nome">
                                        <?= htmlspecialchars($pessoa_rk['nome']) ?><?= $pessoa_rk['eu'] ? ' (Você)' : '' ?>
                                    </div>
                                    <div class="barra" role="progressbar" aria-label="Pontuação proporcional de <?= htmlspecialchars($pessoa_rk['nome']) ?> no grupo" aria-valuenow="<?= (int) round($pessoa_rk['pontos'] / $maior_pts_grupo * 100) ?>" aria-valuemin="0" aria-valuemax="100">
                                        <span style="width: <?= (int) round($pessoa_rk['pontos'] / $maior_pts_grupo * 100) ?>%"></span>
                                    </div>
                                </div>
                                <div class="valor">
                                    <?= (int) $pessoa_rk['pontos'] ?>
                                    <small>pts · <?= (int) $pessoa_rk['desafios_concluidos'] ?> concl.</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </dialog>
    <?php endforeach; ?>

    <script>
        function alternarAbaGrupo(janela, nomeAba) {
            janela.querySelectorAll('[data-aba-grupo]').forEach(function (botao) {
                const ativo = botao.dataset.abaGrupo === nomeAba;
                botao.classList.toggle('ativo', ativo);
                botao.setAttribute('aria-selected', ativo ? 'true' : 'false');
            });

            janela.querySelectorAll('[data-painel-grupo]').forEach(function (painel) {
                painel.hidden = painel.dataset.painelGrupo !== nomeAba;
            });
        }

        function abrirDetalhesGrupo(idGrupo, abaInicial) {
            const janela = document.getElementById('janela-detalhes-' + idGrupo);
            if (!janela) {
                return;
            }
            alternarAbaGrupo(janela, abaInicial || 'progresso');
            janela.showModal();
        }

        document.querySelectorAll('.janela-detalhes-grupo').forEach(function (janela) {
            janela.querySelectorAll('[data-aba-grupo]').forEach(function (botao) {
                botao.addEventListener('click', function () {
                    alternarAbaGrupo(janela, botao.dataset.abaGrupo);
                });
            });
        });
    </script>
</body>

</html>
