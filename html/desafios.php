<?php
require_once __DIR__ . '/../php/conexao.php';
require_once __DIR__ . '/../php/verifica_sessao.php';

$pagina_atual = 'desafios';
$titulo_topo = 'Desafios Globais';

$sql = "SELECT d.id_desafio,
               d.titulo,
               d.descricao,
               d.categoria,
               d.data_fim,
               COUNT(p.id_participacao) AS participantes,
               MAX(p.id_usuario = :id_usuario) AS participando
        FROM desafio d
        LEFT JOIN participacao p
               ON p.id_desafio = d.id_desafio
              AND p.id_grupo IS NULL
        WHERE d.id_grupo IS NULL
          AND CURDATE() BETWEEN d.data_inicio AND d.data_fim
        GROUP BY d.id_desafio
        ORDER BY d.data_fim ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id_usuario' => $_SESSION['id_usuario']]);
$desafios_banco = $stmt->fetchAll();

$categorias = [
    'fisica'   => ['classe' => 'fisico',      'emoji' => '🏃'],
    'leitura'  => ['classe' => 'leitura',     'emoji' => '📚'],
    'alimento' => ['classe' => 'alimentacao', 'emoji' => '🥗'],
];

$desafios = [];
foreach ($desafios_banco as $linha) {
    $categoria = $categorias[$linha['categoria']] ?? ['classe' => 'fisico', 'emoji' => '🎯'];

    $desafios[] = [
        'id'            => $linha['id_desafio'],
        'titulo'        => $linha['titulo'],
        'meta'          => $linha['descricao'],
        'categoria'     => $categoria['classe'],
        'emoji'         => $categoria['emoji'],
        'fim'           => date('d/m', strtotime($linha['data_fim'])),
        'participantes' => (int) $linha['participantes'],
        'participando'  => (bool) $linha['participando'],
    ];
}

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
                <small>Participantes</small>
                <strong><?= $total_comunidade ?></strong>
            </div>
            </div>
        </div>
        <?php if (isset($_GET['participando'])): ?>
            <p class="aviso-app sucesso" role="status">Você entrou no desafio!</p>
        <?php elseif (isset($_GET['erro_participar'])): ?>
            <p class="aviso-app erro" role="alert">Este desafio não está disponível.</p>
        <?php endif; ?>
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
        <?php if (empty($desafios)): ?>
            <p class="cartao-app com-padding texto-suave">Nenhum desafio global ativo no momento.</p>
        <?php endif; ?>
        <div class="grade-2" id="lista-desafios">
            <?php foreach ($desafios as $desafio): ?>
                <article class="cartao-app desafio <?= $desafio['categoria'] ?>"
                         data-categoria="<?= $desafio['categoria'] ?>"
                         data-participando="<?= $desafio['participando'] ? 'sim' : 'nao' ?>">
                    <span class="emoji-caixa" aria-hidden="true"><?= $desafio['emoji'] ?></span>
                    <div class="desafio-info">
                        <h3><?= htmlspecialchars($desafio['titulo']) ?></h3>
                        <div class="desafio-meta">
                            <span><?= icone('alvo', 16) ?> <?= htmlspecialchars($desafio['meta'] ?? '') ?></span>
                            <span><?= icone('calendario', 16) ?> Até <?= $desafio['fim'] ?></span>
                            <span><?= icone('grupos', 16) ?> <?= $desafio['participantes'] ?></span>
                        </div>
                    </div>
                    <div class="desafio-lado">
                        <?php if ($desafio['participando']): ?>
                            <span class="btn btn-participando"><?= icone('check', 16) ?> Participando</span>
                        <?php else: ?>
                            <form method="post" action="../php/participar_desafio.php">
                                <input type="hidden" name="id_desafio" value="<?= (int) $desafio['id'] ?>">
                                <button type="submit" class="btn btn-primario btn-participar">Participar</button>
                            </form>
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
