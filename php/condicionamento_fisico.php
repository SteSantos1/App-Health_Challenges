<?php
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

$id_usuario = $_SESSION['id_usuario'];

$pagina_atual = 'condicionamento';
$titulo_topo = 'Condicionamento';

$stmt_usuario = $pdo->prepare("SELECT peso, altura, data_nascimento FROM usuario WHERE id_usuario = :id");
$stmt_usuario->execute(['id' => $id_usuario]);
$dados_fisicos = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

if (!$dados_fisicos || empty($dados_fisicos['peso'])) {
    $dados_fisicos = ['peso' => 0, 'altura' => 0, 'data_nascimento' => date('Y-m-d')];
}

$stmt_hist = $pdo->prepare("SELECT DATE_FORMAT(data_registro, '%d/%m/%Y') AS data, peso, altura FROM historico_corporal WHERE id_usuario = :id ORDER BY data_registro DESC");
$stmt_hist->execute(['id' => $id_usuario]);
$historico_corporal = $stmt_hist->fetchAll(PDO::FETCH_ASSOC);

$stmt_ativ = $pdo->prepare("SELECT tipo_atividade as tipo, valor_atingido as valor, unidade, data_hora FROM atividade WHERE id_usuario = :id ORDER BY data_hora DESC");
$stmt_ativ->execute(['id' => $id_usuario]);
$atividades = $stmt_ativ->fetchAll(PDO::FETCH_ASSOC);


//nn mudar
$stmt_indice = $pdo->prepare("SELECT tipo_atividade, valor_atingido, unidade FROM atividade WHERE id_usuario = :id AND data_hora >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
//nn mudar
$stmt_indice->execute(['id' => $id_usuario]);
//nn mudar
$atividades_recentes = $stmt_indice->fetchAll(PDO::FETCH_ASSOC);

// criar funcao pra usar umas 6 vezes no codigo dps

function calcular_indice($lista_atividades){
    $pontos = 0;
    foreach ($lista_atividades as $ativ) {
        $valor = (float) $ativ['valor_atingido'];
        $tipo = $ativ['tipo_atividade'];
        $unidade = $ativ['unidade'];

        if ($tipo === 'Corrida'){
            $pontos += ($unidade === 'km') ? ($valor * 10) : ($valor * 1);
        } elseif ($tipo === 'Ciclismo'){
            $pontos += ($unidade === 'km') ? ($valor * 4) : ($valor * 0.4);
        } elseif ($tipo === 'Caminhada'){
            $pontos += ($unidade === 'km') ? ($valor * 5) : ($valor * 0.5);
        }
    }
    return min(100, round($pontos));
}
$indice_atual = calcular_indice($atividades_recentes);

$emojis = ['Caminhada' => '🚶', 'Corrida' => '🏃', 'Ciclismo' => '🚴'];
$meses_curtos = ['', 'jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];

function calcular_imc($peso, $altura) {
    if ($altura <= 0) return 0;
    return $peso / ($altura * $altura);
}

function classificar_imc($imc) {
    if ($imc <= 0) return ['Sem dados', 'aviso'];
    if ($imc < 18.5) return ['Abaixo do peso', 'aviso'];
    if ($imc < 25)   return ['Peso normal', 'bom'];
    if ($imc < 30)   return ['Sobrepeso', 'aviso'];
    return ['Obesidade', 'ruim'];
}

function classificar_indice($valor) {
    if ($valor >= 85) return 'Excelente';
    if ($valor >= 70) return 'Bom';
    if ($valor >= 50) return 'Regular';
    return 'Baixo';
}

$imc = calcular_imc($dados_fisicos['peso'], $dados_fisicos['altura']);
[$imc_texto, $imc_classe] = classificar_imc($imc);

$data_nasc = new DateTime($dados_fisicos['data_nascimento']);
$idade = $data_nasc->diff(new DateTime())->y;

$contagem = ['Caminhada' => 0, 'Corrida' => 0, 'Ciclismo' => 0];
foreach ($atividades as $atividade) {
    if (isset($contagem[$atividade['tipo']])) {
        $contagem[$atividade['tipo']]++;
    }
}

$inicio = new DateTime('first day of this month 00:00');
$inicio->modify('-5 months');

$stmt_evolucao = $pdo->prepare("SELECT tipo_atividade, valor_atingido, unidade, DATE_FORMAT(data_hora, '%Y-%m') AS mes
                                FROM atividade
                                WHERE id_usuario = :id AND data_hora >= :inicio");
$stmt_evolucao->execute([
    'id' => $id_usuario,
    'inicio' => $inicio->format('Y-m-d H:i:s'),
]);

$atividades_por_mes = [];
foreach ($stmt_evolucao->fetchAll(PDO::FETCH_ASSOC) as $linha) {
    $atividades_por_mes[$linha['mes']][] = $linha;
}

$evolucao_indice = [];
for ($i = 0; $i < 6; $i++) {
    $mes = (clone $inicio)->modify("+$i months");
    $chave = $mes->format('Y-m');

    $evolucao_indice[] = [
        'mes'   => ucfirst($meses_curtos[(int) $mes->format('n')]),
        'valor' => calcular_indice($atividades_por_mes[$chave] ?? []),
    ];
}

$largura = 1000;
$altura_grafico = 200;
$margem = 30;
$valores = array_column($evolucao_indice, 'valor');
$minimo = !empty($valores) ? min($valores) - 5 : 0;
$maximo = !empty($valores) ? max($valores) + 5 : 100;
if ($minimo === $maximo) $maximo += 10; 

$pontos = [];
$qtd_valores = count($valores);
foreach ($valores as $i => $valor) {
    $x = $margem + $i * (($largura - 2 * $margem) / max(1, $qtd_valores - 1));
    $y = $altura_grafico - $margem - (($valor - $minimo) / ($maximo - $minimo)) * ($altura_grafico - 2 * $margem);
    $pontos[] = [round($x, 1), round($y, 1)];
}

$linha = implode(' ', array_map(fn($p) => $p[0] . ',' . $p[1], $pontos));
$area = $linha . ' ' . end($pontos)[0] . ',' . ($altura_grafico - 10) . ' ' . $pontos[0][0] . ',' . ($altura_grafico - 10);
$variacao = end($valores) - $valores[0];

$raio_anel = 70;
$circunferencia = 2 * M_PI * $raio_anel;
$preenchido = $circunferencia * ($indice_atual / 100);
?>