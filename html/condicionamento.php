<?php
require_once __DIR__ . '/../php/env.php';
require_once __DIR__ . '/../php/verifica_sessao.php';

$pagina_atual = 'condicionamento';
$titulo_topo = 'Condicionamento';

$dsn = "mysql:host=$ENV_HOST;dbname=$ENV_DBNAME;charset=utf8mb4";
$pdo = new PDO($dsn, $ENV_USERNAME, $ENV_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['atualizar_medidas'])) {
    $peso = $_POST['peso'];
    $altura = $_POST['altura'];

    $sql = "UPDATE usuario 
            SET peso = :peso, altura = :altura
            WHERE id_usuario = :id_usuario";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'peso' => $peso,
        'altura' => $altura,
        'id_usuario' => $_SESSION['id_usuario']
    ]);

    header('Location: condicionamento.php');
    exit;
}

$sql = "SELECT peso, altura, data_nascimento
        FROM usuario
        WHERE id_usuario = :id_usuario
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'id_usuario' => $_SESSION['id_usuario']
]);

$dados_fisicos = $stmt->fetch(PDO::FETCH_ASSOC);

if ($dados_fisicos === false) {
    die('Não foram encontrados dados para este usuário.');
}

$indice_atual = 78;

$evolucao_indice = [
    ['mes' => 'Mai', 'valor' => 62],
    ['mes' => 'Jun', 'valor' => 65],
    ['mes' => 'Jul', 'valor' => 68],
    ['mes' => 'Ago', 'valor' => 66],
    ['mes' => 'Set', 'valor' => 71],
    ['mes' => 'Out', 'valor' => 78],
];

$historico_corporal = [
    ['data' => '10/10/2026', 'peso' => 78.0, 'altura' => 1.75],
    ['data' => '10/09/2026', 'peso' => 79.4, 'altura' => 1.75],
    ['data' => '10/08/2026', 'peso' => 80.8, 'altura' => 1.75],
];

$atividades = [
    ['tipo' => 'Corrida',   'valor' => 6,  'unidade' => 'km', 'data_hora' => '2026-10-15 07:30'],
    ['tipo' => 'Ciclismo',  'valor' => 15, 'unidade' => 'km', 'data_hora' => '2026-10-14 18:10'],
    ['tipo' => 'Caminhada', 'valor' => 5,  'unidade' => 'km', 'data_hora' => '2026-10-13 19:00'],
    ['tipo' => 'Corrida',   'valor' => 8,  'unidade' => 'km', 'data_hora' => '2026-10-11 07:15'],
    ['tipo' => 'Caminhada', 'valor' => 40, 'unidade' => 'min', 'data_hora' => '2026-10-09 12:20'],
    ['tipo' => 'Caminhada', 'valor' => 3,  'unidade' => 'km', 'data_hora' => '2026-10-07 20:05'],
];

$emojis = ['Caminhada' => '🚶', 'Corrida' => '🏃', 'Ciclismo' => '🚴'];
$meses_curtos = ['', 'jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];

function calcular_imc(float $peso, float $altura): float
{
    return $peso / ($altura * $altura);
}

function classificar_imc(float $imc)
{
    if ($imc < 18.5) return ['Abaixo do peso', 'aviso'];
    if ($imc < 25)   return ['Peso normal', 'bom'];
    if ($imc < 30)   return ['Sobrepeso', 'aviso'];
    return ['Obesidade', 'ruim'];
}

function classificar_indice(int $valor)
{
    if ($valor >= 85) return 'Excelente';
    if ($valor >= 70) return 'Bom';
    if ($valor >= 50) return 'Regular';
    return 'Baixo';
}

$imc = calcular_imc($dados_fisicos['peso'], $dados_fisicos['altura']);
[$imc_texto, $imc_classe] = classificar_imc($imc);
$idade = (new DateTime($dados_fisicos['data_nascimento']))->diff(new DateTime())->y;

$contagem = ['Caminhada' => 0, 'Corrida' => 0, 'Ciclismo' => 0];
foreach ($atividades as $atividade) {
    $contagem[$atividade['tipo']]++;
}

// Gráfico de linha (SVG)
$largura = 1000;
$altura_grafico = 200;
$margem = 30;
$valores = array_column($evolucao_indice, 'valor');
$minimo = min($valores) - 5;
$maximo = max($valores) + 5;
$pontos = [];
foreach ($valores as $i => $valor) {
    $x = $margem + $i * (($largura - 2 * $margem) / (count($valores) - 1));
    $y = $altura_grafico - $margem - (($valor - $minimo) / ($maximo - $minimo)) * ($altura_grafico - 2 * $margem);
    $pontos[] = [round($x, 1), round($y, 1)];
}
$linha = implode(' ', array_map(fn($p) => $p[0] . ',' . $p[1], $pontos));
$area = $linha . ' ' . end($pontos)[0] . ',' . ($altura_grafico - 10) . ' ' . $pontos[0][0] . ',' . ($altura_grafico - 10);
$variacao = end($valores) - $valores[0];

// Anel do índice
$raio_anel = 70;
$circunferencia = 2 * M_PI * $raio_anel;
$preenchido = $circunferencia * $indice_atual / 100;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Condicionamento | Health Challenges</title>
    <?php include __DIR__ . '/../css/app_style.php'; ?>
    <style>
        .cond-grade {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.65fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .indice-cartao {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .indice-cartao .cartao-titulo {
            align-self: stretch;
        }

        .anel {
            position: relative;
            width: 190px;
            height: 190px;
            margin: 10px 0 24px;
        }

        .anel svg {
            transform: rotate(-90deg);
        }

        .anel-texto {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .anel-texto strong {
            font-family: var(--fonte-titulo);
            font-size: 3rem;
            line-height: 1;
            color: #155DFC;
        }

        .anel-texto small {
            color: var(--texto-suave);
            font-size: 1rem;
        }

        .anel-texto span {
            color: #155DFC;
            font-weight: 700;
            margin-top: 2px;
        }

        .medidas {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .medida {
            padding: 20px;
            border-radius: 16px;
            background-color: #F8FAFC;
        }

        .medida small {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--texto-suave);
            font-weight: 600;
            letter-spacing: 0.04em;
        }

        .medida strong {
            display: block;
            font-size: 1.8rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .medida .classificacao {
            font-weight: 600;
        }

        .classificacao.bom   { color: var(--verde-escuro); }
        .classificacao.aviso { color: var(--ambar-texto); }
        .classificacao.ruim  { color: var(--dificil); }

        .contagem {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--borda);
            text-align: center;
        }

        .contagem span {
            font-size: 1.8rem;
        }

        .contagem strong {
            display: block;
            font-family: var(--fonte-titulo);
            font-size: 1.6rem;
        }

        .contagem small {
            color: var(--texto-suave);
        }

        .selo-variacao {
            padding: 6px 14px;
            border-radius: 999px;
            background-color: var(--verde-claro);
            color: var(--verde-escuro);
            font-family: var(--fonte);
            font-size: 0.95rem;
            font-weight: 700;
        }

        .grafico > svg {
            width: 100%;
            height: auto;
            display: block;
        }

        .grafico-legenda {
            display: grid;
            text-align: center;
            margin-top: 6px;
        }

        .grafico-legenda small {
            display: block;
            color: var(--texto-fraco);
        }

        .grafico-legenda strong {
            color: var(--verde-escuro);
        }

        .tabela {
            width: 100%;
            border-collapse: collapse;
        }

        .tabela th,
        .tabela td {
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid var(--borda);
        }

        .tabela th {
            font-size: 0.8rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--texto-suave);
        }

        .tabela tr:last-child td {
            border-bottom: none;
        }

        .historico h3 {
            padding: 26px 30px 18px;
            border-bottom: 1px solid var(--borda);
            margin-bottom: 0;
        }

        .atividade {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 18px 30px;
        }

        .atividade:hover {
            background-color: #F8FAFC;
        }

        .atividade .emoji-caixa {
            background-color: var(--branco);
            border: 1px solid var(--borda);
        }

        .atividade strong {
            display: block;
            font-weight: 600;
            font-size: 1.15rem;
        }

        .atividade .data {
            margin-left: auto;
            color: var(--texto-suave);
            white-space: nowrap;
        }

        .dois {
            display: grid;
            grid-template-columns: minmax(0, 1.65fr) minmax(0, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        @media (max-width: 1100px) {
            .cond-grade,
            .dois {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .medidas {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .medida {
                padding: 14px;
            }

            .medida strong {
                font-size: 1.4rem;
            }

            .atividade {
                padding: 14px 16px;
                gap: 12px;
            }

            .historico h3 {
                padding: 20px 16px 14px;
            }

            .grafico-legenda small {
                font-size: 0.7rem;
            }
        }
    </style>
</head>

<body class="app">
    <?php include __DIR__ . '/cabecalho.php'; ?>

    <main class="conteudo estreito">
        <div class="cabeca-pagina">
            <div>
                <h1>Condicionamento Físico</h1>
                <p>Acompanhe sua evolução e registre suas atividades.</p>
            </div>
            <div class="grupo-botoes">
                <button type="button" class="btn btn-contorno" onclick="document.getElementById('janela-medidas').showModal()">Atualizar medidas</button>
                <button type="button" class="btn btn-primario" onclick="document.getElementById('janela-registrar').showModal()"><?= icone('mais', 20) ?> Registrar</button>
            </div>
        </div>

        <div class="cond-grade">
            <section class="cartao-app com-padding indice-cartao" aria-labelledby="titulo-indice">
                <h3 class="cartao-titulo" id="titulo-indice"><span>Índice de Condicionamento</span></h3>
                <div class="anel">
                    <svg width="190" height="190" viewBox="0 0 190 190" aria-hidden="true">
                        <circle cx="95" cy="95" r="<?= $raio_anel ?>" fill="none" stroke="#EFF6FF" stroke-width="16" />
                        <circle cx="95" cy="95" r="<?= $raio_anel ?>" fill="none" stroke="#2B7FFF" stroke-width="16"
                                stroke-linecap="round"
                                stroke-dasharray="<?= round($preenchido, 1) ?> <?= round($circunferencia, 1) ?>" />
                    </svg>
                    <div class="anel-texto">
                        <strong><?= $indice_atual ?></strong>
                        <small>/100</small>
                        <span><?= classificar_indice($indice_atual) ?></span>
                    </div>
                </div>
                <p class="texto-suave">Baseado nas suas atividades dos últimos 30 dias</p>
            </section>

            <section class="cartao-app com-padding" aria-labelledby="titulo-dados">
                <h3 class="cartao-titulo" id="titulo-dados"><span>Dados físicos</span></h3>
                <div class="medidas">
                    <div class="medida">
                        <small><span aria-hidden="true">⚖️</span> PESO</small>
                        <strong><?= number_format($dados_fisicos['peso'], 1, ',', '') ?> kg</strong>
                    </div>
                    <div class="medida">
                        <small><span aria-hidden="true">📏</span> ALTURA</small>
                        <strong><?= number_format($dados_fisicos['altura'], 2, ',', '') ?> m</strong>
                    </div>
                    <div class="medida">
                        <small><span aria-hidden="true">📊</span> IMC</small>
                        <strong><?= number_format($imc, 1, ',', '') ?></strong>
                        <span class="classificacao <?= $imc_classe ?>"><?= $imc_texto ?></span>
                    </div>
                    <div class="medida">
                        <small><span aria-hidden="true">🎂</span> IDADE</small>
                        <strong><?= $idade ?> anos</strong>
                    </div>
                </div>
                <div class="contagem">
                    <?php foreach ($contagem as $tipo => $quantidade): ?>
                        <div>
                            <span aria-hidden="true"><?= $emojis[$tipo] ?></span>
                            <strong><?= $quantidade ?></strong>
                            <small><?= $tipo ?><?= $quantidade === 1 ? '' : 's' ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <div class="dois">
            <section class="cartao-app com-padding grafico" aria-labelledby="titulo-evolucao">
                <h3 class="cartao-titulo" id="titulo-evolucao">
                    <span><?= icone('subindo', 24) ?> Evolução do índice</span>
                    <span class="selo-variacao"><?= $variacao >= 0 ? '+' : '' ?><?= $variacao ?> pts em <?= count($valores) ?> meses</span>
                </h3>
                <svg viewBox="0 0 <?= $largura ?> <?= $altura_grafico ?>" role="img" aria-label="Gráfico da evolução do índice de condicionamento">
                    <defs>
                        <linearGradient id="area-verde" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#00BC7D" stop-opacity="0.28" />
                            <stop offset="100%" stop-color="#00BC7D" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                    <polygon points="<?= $area ?>" fill="url(#area-verde)" />
                    <polyline points="<?= $linha ?>" fill="none" stroke="#00BC7D" stroke-width="4" stroke-linejoin="round" stroke-linecap="round" />
                    <?php foreach ($pontos as $ponto): ?>
                        <circle cx="<?= $ponto[0] ?>" cy="<?= $ponto[1] ?>" r="8" fill="#00BC7D" stroke="#FFFFFF" stroke-width="3" />
                    <?php endforeach; ?>
                </svg>
                <div class="grafico-legenda" style="grid-template-columns: repeat(<?= count($evolucao_indice) ?>, 1fr);">
                    <?php foreach ($evolucao_indice as $item): ?>
                        <div><small><?= $item['mes'] ?></small><strong><?= $item['valor'] ?></strong></div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="cartao-app com-padding" aria-labelledby="titulo-medidas">
                <h3 class="cartao-titulo" id="titulo-medidas"><span><?= icone('calendario', 22) ?> Evolução das medidas</span></h3>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Peso</th>
                            <th>IMC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($historico_corporal)): ?>
                            <tr>
                                <td colspan="3">Nenhuma medida registrada ainda.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($historico_corporal as $registro): ?>
                                <tr>
                                    <td><?= htmlspecialchars($registro['data'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= number_format((float) $registro['peso'], 1, ',', '') ?> kg</td>
                                    <td><?= number_format(calcular_imc((float) $registro['peso'], (float) $registro['altura']), 1, ',', '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </div>

        <section class="cartao-app historico" aria-labelledby="titulo-historico">
            <h3 class="cartao-titulo" id="titulo-historico"><span><?= icone('calendario', 22) ?> Histórico de atividades</span></h3>
            <div class="lista-linhas">
                <?php foreach ($atividades as $atividade): ?>
                    <?php $data = new DateTime($atividade['data_hora']); ?>
                    <div class="atividade">
                        <span class="emoji-caixa" aria-hidden="true"><?= $emojis[$atividade['tipo']] ?></span>
                        <div>
                            <strong><?= $atividade['tipo'] ?></strong>
                            <span class="texto-suave"><?= $atividade['valor'] ?> <?= $atividade['unidade'] ?> · <?= $data->format('H:i') ?></span>
                        </div>
                        <span class="data"><?= $data->format('j') ?> de <?= $meses_curtos[(int) $data->format('n')] ?>.</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <dialog class="janela" id="janela-registrar">
        <div class="janela-topo">
            <h3>Registrar atividade</h3>
            <button type="button" class="janela-fechar" aria-label="Fechar" onclick="this.closest('dialog').close()"><?= icone('fechar', 18) ?></button>
        </div>
        <form class="form-app" method="post" action="#">
            <div>
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo" required>
                    <option value="Caminhada">🚶 Caminhada</option>
                    <option value="Corrida">🏃 Corrida</option>
                    <option value="Ciclismo">🚴 Ciclismo</option>
                </select>
            </div>
            <div class="form-linha">
                <div>
                    <label for="valor">Valor atingido</label>
                    <input type="number" id="valor" name="valor" min="1" step="1" placeholder="5" required>
                </div>
                <div>
                    <label for="unidade">Unidade</label>
                    <select id="unidade" name="unidade" required>
                        <option value="km">km</option>
                        <option value="min">minutos</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="data_hora">Data e hora</label>
                <input type="datetime-local" id="data_hora" name="data_hora" value="<?= date('Y-m-d\TH:i') ?>" max="<?= date('Y-m-d\TH:i') ?>" required>
            </div>
            <button type="submit" class="btn btn-primario btn-bloco">Salvar atividade</button>
        </form>
    </dialog>

    <dialog class="janela" id="janela-medidas">
        <div class="janela-topo">
            <h3>Atualizar medidas</h3>
            <button type="button" class="janela-fechar" aria-label="Fechar" onclick="this.closest('dialog').close()"><?= icone('fechar', 18) ?></button>
        </div>
        <form class="form-app" method="post" action="#">
            <div class="form-linha">
                <input type="hidden" name="acao" value="atualizar_medidas">
                <div>
                    <label for="peso">Peso (kg)</label>
                    <input type="number" id="peso" name="peso" min="20" max="400" step="0.1" value="<?= $dados_fisicos['peso'] ?>" required>
                </div>
                <div>
                    <label for="altura">Altura (m)</label>
                    <input type="number" id="altura" name="altura" min="0.5" max="2.5" step="0.01" value="<?= $dados_fisicos['altura'] ?>" required>
                </div>
            </div>
            <p class="texto-suave">O IMC é calculado automaticamente a partir do peso e da altura.</p>
            <button type="submit" name="atualizar_medidas" value="1" class="btn btn-primario btn-bloco">
                Salvar medidas
            </button>
        </form>
    </dialog>
</body>

</html>
