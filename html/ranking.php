<?php
require_once __DIR__ . '/../php/verifica_sessao.php';

$pagina_atual = 'ranking';
$titulo_topo = 'Ranking';

$meses = ['', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
$mes_atual = $meses[(int) date('n')];
$primeiro_nome = explode(' ', trim($_SESSION['nome']))[0];

// Exemplo: virá das tabelas ranking_temporada e ranking_diario (Sprint 4)
$rankings = [
    'mensal' => [
        ['nome' => 'Marina Santos',     'pontos' => 1890, 'cor' => '#F6339A'],
        ['nome' => 'Carlos Oliveira',   'pontos' => 1650, 'cor' => '#2B7FFF'],
        ['nome' => 'Julia Ferraz',      'pontos' => 1520, 'cor' => '#00BC7D'],
        ['nome' => $_SESSION['nome'],   'pontos' => 1240, 'cor' => '#FF2056', 'eu' => true],
        ['nome' => 'Pedro Alves',       'pontos' => 1180, 'cor' => '#8E51FF'],
        ['nome' => 'Ana Rodrigues',     'pontos' => 1020, 'cor' => '#FE9A00'],
        ['nome' => 'Lucas Ferreira',    'pontos' => 890,  'cor' => '#2B7FFF'],
        ['nome' => 'Carla Mendes',      'pontos' => 780,  'cor' => '#00BC7D'],
        ['nome' => 'Tiago Souza',       'pontos' => 650,  'cor' => '#00B8DB'],
        ['nome' => 'Beatriz Lima',      'pontos' => 540,  'cor' => '#FF2056'],
    ],
    'diario' => [
        ['nome' => 'Carlos Oliveira',   'pontos' => 150, 'cor' => '#2B7FFF'],
        ['nome' => $_SESSION['nome'],   'pontos' => 100, 'cor' => '#FF2056', 'eu' => true],
        ['nome' => 'Ana Rodrigues',     'pontos' => 100, 'cor' => '#FE9A00'],
        ['nome' => 'Marina Santos',     'pontos' => 50,  'cor' => '#F6339A'],
        ['nome' => 'Tiago Souza',       'pontos' => 50,  'cor' => '#00B8DB'],
        ['nome' => 'Pedro Alves',       'pontos' => 0,   'cor' => '#8E51FF'],
    ],
];

$textos = [
    'mensal' => ['subtitulo' => "Classificação mensal de $mes_atual. Atualizado diariamente.", 'podio' => "Pódio de $mes_atual"],
    'diario' => ['subtitulo' => 'Pontuação obtida pelos usuários hoje (' . date('d/m') . ').', 'podio' => 'Pódio de hoje'],
];

$medalhas = [1 => '🥇', 2 => '🥈', 3 => '🥉'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking | Health Challenges</title>
    <?php include __DIR__ . '/../css/app_style.php'; ?>
    <style>
        .painel-ranking {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .sua-posicao {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 24px 28px;
            border-radius: 22px;
            background: linear-gradient(135deg, var(--verde) 0%, var(--verde-forte) 100%);
            color: var(--branco);
            box-shadow: 0 14px 30px rgba(0, 153, 102, 0.28);
        }

        .sua-posicao .avatar {
            position: relative;
            width: 60px;
            height: 60px;
        }

        .sua-posicao .avatar em {
            position: absolute;
            right: -4px;
            bottom: -4px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background-color: var(--ambar);
            color: var(--texto);
            border: 2px solid var(--branco);
            font-style: normal;
            font-size: 0.8rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sua-posicao small {
            font-weight: 700;
            letter-spacing: 0.06em;
        }

        .sua-posicao strong {
            display: block;
            font-family: var(--fonte-titulo);
            font-size: 1.6rem;
        }

        .sua-posicao .total {
            margin-left: auto;
            text-align: right;
        }

        .sua-posicao .total strong {
            font-size: 2.2rem;
            font-weight: 800;
        }

        .podio {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            align-items: end;
            margin-top: 12px;
        }

        .podio-lugar {
            text-align: center;
        }

        .podio-lugar .avatar {
            width: 84px;
            height: 84px;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .podio-lugar.primeiro .avatar {
            width: 96px;
            height: 96px;
        }

        .podio-lugar strong {
            display: block;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .podio-lugar .pts-podio {
            color: var(--texto-suave);
            font-weight: 600;
        }

        .podio-lugar.primeiro .pts-podio {
            color: var(--ambar-texto);
        }

        .degrau {
            margin-top: 12px;
            border-radius: 18px 18px 0 0;
            display: flex;
            justify-content: center;
            padding-top: 22px;
            font-size: 2rem;
        }

        .segundo .degrau  { height: 110px; background-color: #90A1B9; }
        .primeiro .degrau { height: 150px; background-color: var(--ambar); }
        .terceiro .degrau { height: 85px;  background-color: #D7955F; }

        .coroa {
            display: block;
            font-size: 1.4rem;
            margin-bottom: -6px;
        }

        .linha-ranking {
            display: grid;
            grid-template-columns: 52px 52px 1fr auto;
            align-items: center;
            gap: 16px;
            padding: 18px 30px;
        }

        .linha-ranking.eu {
            background-color: var(--verde-claro);
        }

        .linha-ranking .posicao {
            font-family: var(--fonte-titulo);
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--texto-fraco);
            text-align: center;
        }

        .linha-ranking .nome {
            font-size: 1.15rem;
            margin-bottom: 8px;
        }

        .linha-ranking .barra > span {
            background-color: #CAD5E2;
        }

        .linha-ranking.eu .barra > span {
            background-color: var(--verde);
        }

        .linha-ranking .valor {
            text-align: right;
            font-family: var(--fonte-titulo);
            font-size: 1.2rem;
            font-weight: 700;
        }

        .linha-ranking .valor small {
            display: block;
            font-family: var(--fonte);
            font-size: 0.8rem;
            color: var(--texto-fraco);
            font-weight: 600;
        }

        .classificacao h3 {
            padding: 28px 30px 18px;
            font-size: 1.3rem;
            border-bottom: 1px solid var(--borda);
        }

        .comparacao-linha {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 1.1rem;
        }

        .comparacao-linha .negativo { color: var(--vermelho); font-weight: 700; }
        .comparacao-linha .positivo { color: var(--verde-escuro); font-weight: 700; }

        .dica {
            margin-top: 10px;
            padding-top: 16px;
            border-top: 1px solid var(--borda);
            color: var(--texto-suave);
        }

        [data-painel][hidden] {
            display: none;
        }

        @media (max-width: 600px) {
            .sua-posicao {
                padding: 18px;
                flex-wrap: wrap;
            }

            .sua-posicao strong {
                font-size: 1.3rem;
            }

            .podio {
                gap: 8px;
            }

            .podio-lugar .avatar,
            .podio-lugar.primeiro .avatar {
                width: 56px;
                height: 56px;
                font-size: 1.1rem;
            }

            .podio-lugar strong {
                font-size: 1rem;
            }

            .linha-ranking {
                grid-template-columns: 36px 40px 1fr auto;
                gap: 10px;
                padding: 14px 16px;
            }

            .linha-ranking .avatar {
                width: 40px;
                height: 40px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body class="app">
    <?php include __DIR__ . '/cabecalho.php'; ?>

    <main class="conteudo estreito">
        <div class="cabeca-pagina">
            <div>
                <h1>Ranking</h1>
                <p id="subtitulo-ranking"><?= $textos['mensal']['subtitulo'] ?></p>
            </div>
            <div class="filtros-grupo" role="tablist" aria-label="Tipo de ranking">
                <button type="button" class="chip ativo" role="tab" aria-selected="true" data-aba="mensal">Mensal</button>
                <button type="button" class="chip" role="tab" aria-selected="false" data-aba="diario">Diário</button>
            </div>
        </div>

        <?php foreach ($rankings as $tipo => $lista): ?>
            <?php
            usort($lista, fn($a, $b) => $b['pontos'] <=> $a['pontos']);
            $maior = max(1, $lista[0]['pontos']);
            $minha_posicao = 0;
            foreach ($lista as $i => $pessoa) {
                if (!empty($pessoa['eu'])) {
                    $minha_posicao = $i + 1;
                }
            }
            $eu = $lista[$minha_posicao - 1];
            $ordem_podio = [1, 0, 2];
            $classes_podio = ['segundo', 'primeiro', 'terceiro'];
            ?>
            <div class="painel-ranking" data-painel="<?= $tipo ?>" data-subtitulo="<?= htmlspecialchars($textos[$tipo]['subtitulo']) ?>" <?= $tipo !== 'mensal' ? 'hidden' : '' ?>>

                <section class="sua-posicao" aria-label="Sua posição">
                    <span class="avatar" style="background-color: <?= $eu['cor'] ?>">
                        <?= htmlspecialchars(iniciais($eu['nome'])) ?>
                        <em><?= $minha_posicao ?></em>
                    </span>
                    <div>
                        <small>SUA POSIÇÃO</small>
                        <strong><?= $minha_posicao ?>º lugar — <?= htmlspecialchars($primeiro_nome) ?></strong>
                    </div>
                    <div class="total">
                        <strong><?= $eu['pontos'] ?></strong>
                        <small>pontos</small>
                    </div>
                </section>

                <section class="cartao-app com-padding">
                    <h3 class="cartao-titulo" style="justify-content: center;"><span>🏆 <?= $textos[$tipo]['podio'] ?></span></h3>
                    <div class="podio">
                        <?php foreach ($ordem_podio as $j => $indice): ?>
                            <?php if (!isset($lista[$indice])) continue; $pessoa = $lista[$indice]; ?>
                            <div class="podio-lugar <?= $classes_podio[$j] ?>">
                                <?php if ($indice === 0): ?><span class="coroa" aria-hidden="true">👑</span><?php endif; ?>
                                <span class="avatar" style="background-color: <?= $pessoa['cor'] ?>"><?= htmlspecialchars(iniciais($pessoa['nome'])) ?></span>
                                <strong><?= htmlspecialchars(explode(' ', $pessoa['nome'])[0]) ?></strong>
                                <span class="pts-podio"><?= $pessoa['pontos'] ?> pts</span>
                                <div class="degrau"><span aria-label="<?= $indice + 1 ?>º lugar"><?= $medalhas[$indice + 1] ?></span></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="cartao-app classificacao">
                    <h3>Classificação completa</h3>
                    <div class="lista-linhas">
                        <?php foreach ($lista as $i => $pessoa): ?>
                            <div class="linha-ranking <?= !empty($pessoa['eu']) ? 'eu' : '' ?>">
                                <span class="posicao">#<?= $i + 1 ?></span>
                                <span class="avatar" style="background-color: <?= $pessoa['cor'] ?>"><?= htmlspecialchars(iniciais($pessoa['nome'])) ?></span>
                                <div>
                                    <div class="nome"><?= htmlspecialchars($pessoa['nome']) ?></div>
                                    <div class="barra"><span style="width: <?= round($pessoa['pontos'] / $maior * 100) ?>%"></span></div>
                                </div>
                                <div class="valor"><?= $pessoa['pontos'] ?><small>pts</small></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <?php if ($minha_posicao > 1): ?>
                    <section class="cartao-app com-padding">
                        <h3 class="cartao-titulo"><span><?= icone('subindo', 24) ?> Sua comparação</span></h3>
                        <div class="comparacao-linha">
                            <span>vs <?= htmlspecialchars(explode(' ', $lista[0]['nome'])[0]) ?> (1º)</span>
                            <span class="negativo"><?= $eu['pontos'] - $lista[0]['pontos'] ?> pts</span>
                        </div>
                        <?php if (isset($lista[$minha_posicao])): ?>
                            <div class="comparacao-linha">
                                <span>vs <?= htmlspecialchars(explode(' ', $lista[$minha_posicao]['nome'])[0]) ?> (<?= $minha_posicao + 1 ?>º)</span>
                                <span class="positivo">+<?= $eu['pontos'] - $lista[$minha_posicao]['pontos'] ?> pts</span>
                            </div>
                        <?php endif; ?>
                        <p class="dica">💡 Faltam <?= $lista[0]['pontos'] - $eu['pontos'] ?> pontos para alcançar o 1º lugar!</p>
                    </section>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </main>

    <script>
        document.querySelectorAll('[data-aba]').forEach(function (aba) {
            aba.addEventListener('click', function () {
                document.querySelectorAll('[data-aba]').forEach(function (outra) {
                    outra.classList.remove('ativo');
                    outra.setAttribute('aria-selected', 'false');
                });
                aba.classList.add('ativo');
                aba.setAttribute('aria-selected', 'true');

                document.querySelectorAll('[data-painel]').forEach(function (painel) {
                    const mostrar = painel.dataset.painel === aba.dataset.aba;
                    painel.hidden = !mostrar;
                    if (mostrar) {
                        document.getElementById('subtitulo-ranking').textContent = painel.dataset.subtitulo;
                    }
                });
            });
        });
    </script>
</body>

</html>
