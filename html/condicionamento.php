<?php include __DIR__ . '/../php/condicionamento_fisico.php';?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Condicionamento | Health Challenges</title>
    <?php include __DIR__ . '/../css/app_style.php'; ?>
    <?php include __DIR__ . '/../css/condicionamento_style.php'; ?>
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

        <?php if (isset($_GET['registrado'])): ?>
            <p class="aviso-app sucesso" role="status">Atividade registrada!</p>
        <?php elseif (isset($_GET['erro'])): ?>
            <p class="aviso-app erro" role="alert">Não foi possível registrar. Confira os dados e tente de novo.</p>
        <?php endif; ?>

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
                        <?php foreach ($historico_corporal as $registro): ?>
                            <tr>
                                <td><?= $registro['data'] ?></td>
                                <td><?= number_format($registro['peso'], 1, ',', '') ?> kg</td>
                                <td><?= number_format(calcular_imc($registro['peso'], $registro['altura']), 1, ',', '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>

        <section class="cartao-app historico" aria-labelledby="titulo-historico">
            <h3 class="cartao-titulo" id="titulo-historico"><span><?= icone('calendario', 22) ?> Histórico de atividades</span></h3>
            <div class="lista-linhas">
                <?php if (empty($atividades)): ?>
                    <p class="texto-suave" style="padding: 24px 30px;">Nenhuma atividade registrada ainda. Clique em "Registrar" para começar.</p>
                <?php endif; ?>
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
        <form class="form-app" method="post" action="../php/registrar_atividade.php">
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
            <button type="submit" class="btn btn-primario btn-bloco">Salvar medidas</button>
        </form>
    </dialog>
</body>

</html>
