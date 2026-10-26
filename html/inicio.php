<?php
require_once __DIR__ . '/../php/verifica_sessao.php';
require_once __DIR__ . '/../php/desafios_diarios.php';

$pagina_atual = 'inicio';
$titulo_topo = 'Início';

$nome = $_SESSION['nome'];
$primeiro_nome = explode(' ', trim($nome))[0];

$hora = (int) date('G');
if ($hora < 12) {
    $saudacao = 'Bom dia';
} elseif ($hora < 18) {
    $saudacao = 'Boa tarde';
} else {
    $saudacao = 'Boa noite';
}

// Exemplo: virá das tabelas categoria_saude e desafio_diario (Sprints 2 e 3)
$categorias = [
    ['nome' => 'Alimentação',        'classe' => 'alimentacao', 'emoji' => '🥗'],
    ['nome' => 'Atividades Físicas', 'classe' => 'fisico',      'emoji' => '🏃'],
    ['nome' => 'Leitura/Estudo',     'classe' => 'leitura',     'emoji' => '📚'],
];

$escolhido = $opcoes_hoje[0];

$resumo = [
    'posicao' => 4,
    'pontos_totais' => '8.5k',
    'indice' => 78,
];

$conquistas = [
    ['emoji' => '🌟', 'nome' => 'Primeiro Passo', 'pontos' => 50],
    ['emoji' => '🔥', 'nome' => 'Em Chamas',      'pontos' => 200],
    ['emoji' => '💧', 'nome' => 'Hidratado',      'pontos' => 100],
    ['emoji' => '🏃', 'nome' => 'Corredor',       'pontos' => 300],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início | Health Challenges</title>
    <?php include __DIR__ . '/../css/app_style.php'; ?>
    <style>
        .inicio-grade {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(0, 1fr);
            gap: 20px;
            align-items: start;
        }

        .coluna {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .desafio-destaque {
            background: linear-gradient(135deg, var(--verde) 0%, var(--verde-forte) 100%);
            color: var(--branco);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 14px 30px rgba(0, 153, 102, 0.28);
            position: relative;
        }

        .desafio-destaque small {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 800;
            letter-spacing: 0.12em;
            font-size: 0.8rem;
            opacity: 0.95;
        }

        .desafio-destaque h3 {
            color: var(--branco);
            font-size: 1.6rem;
            margin: 8px 0 14px;
        }

        .desafio-destaque .emoji {
            position: absolute;
            top: 24px;
            right: 26px;
            font-size: 2.4rem;
        }

        .etiquetas {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .etiquetas span {
            padding: 6px 12px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 700;
            font-size: 0.9rem;
        }

        .etiquetas .etiqueta-pts {
            background-color: var(--ambar);
            color: var(--texto);
        }

        .btn-concluir {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 16px;
            background-color: var(--branco);
            color: var(--verde-escuro);
            font-size: 1.2rem;
            font-weight: 800;
        }

        .btn-concluir:hover {
            background-color: var(--verde-claro);
        }

        .opcoes-titulo {
            font-family: var(--fonte-titulo);
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: -6px;
        }

        .opcao {
            position: relative;
            padding: 20px;
            border-radius: 18px;
            border: 1px solid var(--borda);
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: 6px;
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .opcao:hover {
            transform: translateY(-2px);
            box-shadow: var(--sombra);
        }

        .opcao.alimentacao { background-color: var(--cat-alimentacao-fundo); border-color: #DCFCE7; }
        .opcao.fisico      { background-color: var(--cat-fisico-fundo);      border-color: #DBEAFE; }
        .opcao.leitura     { background-color: var(--cat-leitura-fundo);     border-color: #EDE9FE; }

        .opcao .emoji {
            font-size: 1.8rem;
        }

        .opcao .categoria {
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .opcao.alimentacao .categoria { color: var(--cat-alimentacao); }
        .opcao.fisico .categoria      { color: var(--cat-fisico); }
        .opcao.leitura .categoria     { color: var(--cat-leitura); }

        .opcao .acao {
            font-size: 1.05rem;
            font-weight: 600;
        }

        .opcao .rodape-opcao {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 6px;
        }

        .opcao .marcado {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: var(--verde-forte);
            color: var(--branco);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .opcao.escolhida {
            outline: 2px solid var(--verde);
            outline-offset: -1px;
        }

        .categorias {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .categoria-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 14px;
            font-weight: 700;
            flex: 1;
            min-width: 150px;
        }

        .categoria-item.alimentacao { background-color: var(--cat-alimentacao-fundo); color: var(--cat-alimentacao); }
        .categoria-item.fisico      { background-color: var(--cat-fisico-fundo);      color: var(--cat-fisico); }
        .categoria-item.leitura     { background-color: var(--cat-leitura-fundo);     color: var(--cat-leitura); }

        .categoria-item span:first-child {
            font-size: 1.4rem;
        }

        .numero-cartao {
            padding: 22px 12px;
            text-align: center;
        }

        .numero-cartao .emoji {
            font-size: 1.8rem;
        }

        .numero-cartao strong {
            display: block;
            font-family: var(--fonte-titulo);
            font-size: 1.7rem;
            margin-top: 4px;
        }

        .numero-cartao small {
            color: var(--texto-suave);
            font-size: 0.9rem;
        }

        .conquistas-grade {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .conquista-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            border-radius: 16px;
            background-color: #F8FAFC;
        }

        .conquista-item .emoji {
            font-size: 1.7rem;
        }

        .conquista-item strong {
            display: block;
            font-weight: 600;
        }

        .conquista-item small {
            color: var(--ambar-texto);
            font-weight: 700;
        }

        .ver-todas {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--verde-escuro);
            font-size: 1rem;
            font-weight: 600;
            font-family: var(--fonte);
        }



        @media (max-width: 1100px) {
            .inicio-grade {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        @media (max-width: 600px) {
            .conquistas-grade {
                grid-template-columns: 1fr;
            }

            .opcoes .grade-3,
            .numeros.grade-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 8px;
            }


            .numero-cartao {
                padding: 16px 6px;
            }

            .numero-cartao small {
                font-size: 0.8rem;
            }

            .opcoes .grade-3 {
                grid-template-columns: 1fr;
            }

            .numero-cartao strong {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body class="app">
    <?php include __DIR__ . '/cabecalho.php'; ?>

    <main class="conteudo">
        <div class="cabeca-pagina">
            <div>
                <h1><?= $saudacao ?>, <?= htmlspecialchars($primeiro_nome) ?>! 👋</h1>
                <p>Escolha um dos desafios de hoje e acumule pontos.</p>
            </div>
            <div class="grupo-botoes">
                <div class="pilula-info ambar">
                    <small>Pontos no mês</small>
                    <strong><?= $pontos_mes ?></strong>
                </div>
            </div>
        </div>

        <section class="cartao-app com-padding" style="margin-bottom: 20px;" aria-labelledby="titulo-categorias">
            <h3 class="cartao-titulo" id="titulo-categorias"><span>Categorias de saúde</span></h3>
            <div class="categorias">
                <?php foreach ($categorias as $categoria): ?>
                    <div class="categoria-item <?= $categoria['classe'] ?>">
                        <span aria-hidden="true"><?= $categoria['emoji'] ?></span>
                        <span><?= $categoria['nome'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="inicio-grade">
            <div class="coluna opcoes">
                <section class="desafio-destaque" aria-labelledby="titulo-destaque">
                    <small><?= icone('raio', 16) ?> DESAFIO ESCOLHIDO HOJE</small>
                    <h3 id="titulo-destaque"><?= $escolhido['acao'] ?></h3>
                    <span class="emoji" aria-hidden="true"><?= $escolhido['emoji'] ?></span>
                    <div class="etiquetas">
                        <span><?= $escolhido['categoria'] ?></span>
                        <span><?= $escolhido['nivel'] ?></span>
                        <span class="etiqueta-pts">+<?= $escolhido['pontos'] ?> pts</span>
                    </div>
                    <button type="button" class="btn-concluir">Concluir desafio ✓</button>
                </section>

                <p class="opcoes-titulo">Opções de hoje</p>
                <div class="grade-3">
                    <?php foreach ($opcoes_hoje as $opcao): ?>
                        <button type="button" class="opcao <?= $opcao['classe'] ?> <?= $opcao['escolhido'] ? 'escolhida' : '' ?>">
                            <?php if ($opcao['escolhido']): ?>
                                <span class="marcado"><?= icone('check', 16) ?></span>
                            <?php endif; ?>
                            <span class="emoji" aria-hidden="true"><?= $opcao['emoji'] ?></span>
                            <span class="categoria"><?= $opcao['categoria'] ?></span>
                            <span class="acao"><?= $opcao['acao'] ?></span>
                            <span class="rodape-opcao">
                                <span class="selo <?= $opcao['nivel_classe'] ?>"><?= $opcao['nivel'] ?></span>
                                <span class="pts">+<?= $opcao['pontos'] ?></span>
                            </span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="coluna">
                <div class="grade-3 numeros">
                    <div class="cartao-app numero-cartao">
                        <span class="emoji" aria-hidden="true">🏆</span>
                        <strong><?= $resumo['posicao'] ?>º</strong>
                        <small>No ranking</small>
                    </div>
                    <div class="cartao-app numero-cartao">
                        <span class="emoji" aria-hidden="true">💎</span>
                        <strong><?= $resumo['pontos_totais'] ?></strong>
                        <small>Pts totais</small>
                    </div>
                    <div class="cartao-app numero-cartao">
                        <span class="emoji" aria-hidden="true">🏃</span>
                        <strong><?= $resumo['indice'] ?></strong>
                        <small>Índice físico</small>
                    </div>
                </div>

                <section class="cartao-app com-padding" aria-labelledby="titulo-conquistas">
                    <h3 class="cartao-titulo" id="titulo-conquistas">
                        <span>Conquistas recentes</span>
                        <a href="#" class="ver-todas">Ver todas <?= icone('seta', 16) ?></a>
                    </h3>
                    <div class="conquistas-grade">
                        <?php foreach ($conquistas as $conquista): ?>
                            <div class="conquista-item">
                                <span class="emoji" aria-hidden="true"><?= $conquista['emoji'] ?></span>
                                <div>
                                    <strong><?= $conquista['nome'] ?></strong>
                                    <small>+<?= $conquista['pontos'] ?> pts</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>

</html>