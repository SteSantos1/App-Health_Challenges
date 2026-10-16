<?php
require_once __DIR__ . '/../php/verifica_sessao.php';

$pagina_atual = 'desafios';
$titulo_topo = 'Desafios Globais';

// Exemplo: virá das tabelas desafio e participacao (Sprint 3)
$desafios = [
    ['titulo' => 'Maratona de Hidratação',  'categoria' => 'alimentacao', 'emoji' => '🥗', 'meta' => 'Beber 2 L de água / dia',      'fim' => '31/10', 'nivel' => 'Fácil',   'nivel_classe' => 'facil',   'participantes' => 1243, 'pontos' => 350, 'participando' => true],
    ['titulo' => 'Corrida do Mês',          'categoria' => 'fisico',      'emoji' => '🏃', 'meta' => 'Correr 30 km no mês',          'fim' => '31/10', 'nivel' => 'Difícil', 'nivel_classe' => 'dificil', 'participantes' => 876,  'pontos' => 500, 'participando' => false],
    ['titulo' => 'Clube da Leitura',        'categoria' => 'leitura',     'emoji' => '📚', 'meta' => 'Ler 20 min/dia por 14 dias',   'fim' => '05/11', 'nivel' => 'Médio',   'nivel_classe' => 'medio',   'participantes' => 654,  'pontos' => 400, 'participando' => false],
    ['titulo' => 'Movimento Diário',        'categoria' => 'fisico',      'emoji' => '🏃', 'meta' => 'Fazer 30 min de atividade',    'fim' => '08/11', 'nivel' => 'Médio',   'nivel_classe' => 'medio',   'participantes' => 432,  'pontos' => 200, 'participando' => false],
    ['titulo' => 'Alimentação Consciente',  'categoria' => 'alimentacao', 'emoji' => '🥗', 'meta' => 'Comer 3 porções de vegetais / dia', 'fim' => '10/11', 'nivel' => 'Médio', 'nivel_classe' => 'medio', 'participantes' => 987, 'pontos' => 300, 'participando' => false],
    ['titulo' => 'Ciclismo Urbano',         'categoria' => 'fisico',      'emoji' => '🚴', 'meta' => 'Pedalar 50 km no mês',         'fim' => '31/10', 'nivel' => 'Difícil', 'nivel_classe' => 'dificil', 'participantes' => 321,  'pontos' => 450, 'participando' => false],
    ['titulo' => 'Estudo Focado',           'categoria' => 'leitura',     'emoji' => '📚', 'meta' => 'Estudar 1 h/dia por 7 dias',   'fim' => '30/10', 'nivel' => 'Fácil',   'nivel_classe' => 'facil',   'participantes' => 1120, 'pontos' => 280, 'participando' => false],
    ['titulo' => 'Maratonista de Livros',   'categoria' => 'leitura',     'emoji' => '📚', 'meta' => 'Concluir 1 livro',             'fim' => '15/11', 'nivel' => 'Difícil', 'nivel_classe' => 'dificil', 'participantes' => 445,  'pontos' => 600, 'participando' => false],
];

$total_participando = count(array_filter($desafios, fn($d) => $d['participando']));
$total_comunidade = array_sum(array_column($desafios, 'participantes'));
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desafios Globais | Health Challenges</title>
    <?php include __DIR__ . '/../css/app_style.php'; ?>
    <style>
        .desafio {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 24px;
            border-left: 5px solid var(--borda);
        }

        .desafio.alimentacao { border-left-color: var(--verde); }
        .desafio.fisico      { border-left-color: #2B7FFF; }
        .desafio.leitura     { border-left-color: #A684FF; }

        .desafio.alimentacao .emoji-caixa { background-color: var(--cat-alimentacao-fundo); }
        .desafio.fisico .emoji-caixa      { background-color: var(--cat-fisico-fundo); }
        .desafio.leitura .emoji-caixa     { background-color: var(--cat-leitura-fundo); }

        .desafio-info {
            flex: 1;
            min-width: 0;
        }

        .desafio-info h3 {
            font-family: var(--fonte);
            font-size: 1.2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .desafio-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 16px;
            margin-top: 8px;
            color: var(--texto-suave);
            font-size: 0.95rem;
        }

        .desafio-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .desafio-lado {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }

        .desafio-lado .pts {
            font-size: 1.15rem;
            text-align: right;
            line-height: 1.1;
        }

        .desafio-lado .pts small {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .btn-participar {
            padding: 8px 14px;
            font-size: 0.9rem;
            border-radius: 10px;
            box-shadow: none;
        }

        .btn-participando {
            padding: 8px 14px;
            font-size: 0.9rem;
            border-radius: 10px;
            background-color: var(--verde-claro);
            color: var(--verde-escuro);
            border: 1px solid var(--verde-suave);
        }

        @media (max-width: 600px) {
            .desafio {
                flex-wrap: wrap;
                padding: 18px;
            }

            .desafio-lado {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                width: 100%;
            }

            .desafio-lado .pts small {
                display: inline;
            }
        }
    </style>
</head>

<body class="app">
    <?php include __DIR__ . '/cabecalho.php'; ?>

    <main class="conteudo">
        <div class="cabeca-pagina">
            <div>
                <h1>Desafios Globais</h1>
                <p>Desafios abertos para toda a comunidade. Participe e acumule pontos!</p>
            </div>
            <div class="grupo-botoes">
                <div class="pilula-info verde">
                    <small>Ativos</small>
                    <strong><?= count($desafios) ?></strong>
                </div>
                <div class="pilula-info roxo">
                    <small>Participando</small>
                    <strong><?= $total_participando ?></strong>
                </div>
                <div class="pilula-info ambar">
                    <small>Comunidade</small>
                    <strong><?= number_format($total_comunidade / 1000, 1, ',', '') ?>k</strong>
                </div>
            </div>
        </div>

        <div class="filtros">
            <div class="filtros-grupo" role="group" aria-label="Filtrar por categoria">
                <button type="button" class="chip ativo" data-filtro="todos">✨ Todos</button>
                <button type="button" class="chip" data-filtro="alimentacao">🥗 Alimentação</button>
                <button type="button" class="chip" data-filtro="fisico">🏃 Físico</button>
                <button type="button" class="chip" data-filtro="leitura">📚 Leitura</button>
            </div>
            <div class="filtros-grupo" role="group" aria-label="Filtrar por participação">
                <button type="button" class="chip escuro ativo" data-situacao="todos">Todos</button>
                <button type="button" class="chip escuro" data-situacao="participando">Participando</button>
            </div>
        </div>

        <div class="grade-2" id="lista-desafios">
            <?php foreach ($desafios as $desafio): ?>
                <article class="cartao-app desafio <?= $desafio['categoria'] ?>"
                         data-categoria="<?= $desafio['categoria'] ?>"
                         data-participando="<?= $desafio['participando'] ? 'sim' : 'nao' ?>">
                    <span class="emoji-caixa" aria-hidden="true"><?= $desafio['emoji'] ?></span>
                    <div class="desafio-info">
                        <h3><?= $desafio['titulo'] ?> <span class="selo <?= $desafio['nivel_classe'] ?>"><?= $desafio['nivel'] ?></span></h3>
                        <div class="desafio-meta">
                            <span><?= icone('alvo', 16) ?> <?= $desafio['meta'] ?></span>
                            <span><?= icone('calendario', 16) ?> Até <?= $desafio['fim'] ?></span>
                            <span><?= icone('grupos', 16) ?> <?= $desafio['participantes'] ?></span>
                        </div>
                    </div>
                    <div class="desafio-lado">
                        <span class="pts">+<?= $desafio['pontos'] ?> <small>pts</small></span>
                        <?php if ($desafio['participando']): ?>
                            <span class="btn btn-participando"><?= icone('check', 16) ?> Participando</span>
                        <?php else: ?>
                            <button type="button" class="btn btn-primario btn-participar">Participar</button>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </main>

    <script>
        const cartoes = document.querySelectorAll('#lista-desafios .desafio');
        let filtroCategoria = 'todos';
        let filtroSituacao = 'todos';

        function aplicarFiltros() {
            cartoes.forEach(function (cartao) {
                const okCategoria = filtroCategoria === 'todos' || cartao.dataset.categoria === filtroCategoria;
                const okSituacao = filtroSituacao === 'todos' || cartao.dataset.participando === 'sim';
                cartao.style.display = okCategoria && okSituacao ? '' : 'none';
            });
        }

        document.querySelectorAll('[data-filtro]').forEach(function (botao) {
            botao.addEventListener('click', function () {
                document.querySelectorAll('[data-filtro]').forEach(b => b.classList.remove('ativo'));
                botao.classList.add('ativo');
                filtroCategoria = botao.dataset.filtro;
                aplicarFiltros();
            });
        });

        document.querySelectorAll('[data-situacao]').forEach(function (botao) {
            botao.addEventListener('click', function () {
                document.querySelectorAll('[data-situacao]').forEach(b => b.classList.remove('ativo'));
                botao.classList.add('ativo');
                filtroSituacao = botao.dataset.situacao;
                aplicarFiltros();
            });
        });
    </script>
</body>

</html>
