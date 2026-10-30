<?php
include __DIR__ . "/../php/env.php";
include __DIR__ . "/../php/conexao.php";

$sql_busca = "SELECT desafio_diario.id_desafio_diario,
                     desafio_diario.quantidade_exigida,
                     desafio_diario.pontos_recompensa,
                     acao_base.nome_acao,
                     acao_base.nivel_dificuldade,
                     acao_base.unidade_medida,
                     acao_base.id_categoria,
                     categoria_saude.nome_categoria
              FROM desafio_diario
              INNER JOIN acao_base ON desafio_diario.id_acao = acao_base.id_acao
              INNER JOIN categoria_saude ON acao_base.id_categoria = categoria_saude.id_categoria
              WHERE desafio_diario.data_sorteio = CURDATE()
              ORDER BY acao_base.nivel_dificuldade ASC";

$consulte_sql = $pdo->query($sql_busca);
$desafios_banco = $consulte_sql->fetchAll(PDO::FETCH_ASSOC);

// 2. Se ainda não existirem desafios hoje, gera automaticamente 3 opções (1 Fácil, 1 Médio, 1 Difícil)
if (empty($desafios_banco)) {
    $niveis = [1.0, 2.0, 3.0];

    $sql = "SELECT id_acao, nome_acao, nivel_dificuldade, unidade_medida, fator_pontos, id_categoria
            FROM acao_base
            WHERE nivel_dificuldade = :nivel
            ORDER BY RAND()
            LIMIT 1";
    $stmt = $pdo->prepare($sql);

    $insert_sql = "INSERT INTO desafio_diario (data_sorteio, quantidade_exigida, pontos_recompensa, id_acao)
                   VALUES (CURDATE(), :quantidade, :pontos, :id_acao)";
    $stmt_insert = $pdo->prepare($insert_sql);

    foreach ($niveis as $nivel) {
        $stmt->execute([':nivel' => $nivel]);
        $acao_sorteada = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$acao_sorteada) {
            continue;
        }

        $unidade = $acao_sorteada['unidade_medida'];
        $unidades_pequenas = ['L', 'unid', 'xícaras', 'porções', 'refeições', 'capítulos'];
        $unidades_medias = ['km', 'lances', 'páginas', 'palavras', 'questões', 'horas'];

        if (in_array($unidade, $unidades_pequenas, true)) {
            $quantidade = rand(2, 4);
        } elseif (in_array($unidade, $unidades_medias, true)) {
            $quantidade = rand(5, 15);
        } else {
            $quantidade = rand(20, 50);
        }

        $pontos = round((float) $acao_sorteada['fator_pontos'] * $quantidade);

        $stmt_insert->execute([
            ':quantidade' => $quantidade,
            ':pontos'     => $pontos,
            ':id_acao'    => $acao_sorteada['id_acao'],
        ]);
    }

    // Busca novamente os 3 desafios recém-gerados
    $consulte_sql = $pdo->query($sql_busca);
    $desafios_banco = $consulte_sql->fetchAll(PDO::FETCH_ASSOC);
}

// RF09: desafio escolhido pelo usuário hoje (se houver)
$pdo->exec("CREATE TABLE IF NOT EXISTS desafio_escolhido (
                id_usuario INT NOT NULL,
                data_escolha DATE NOT NULL,
                id_desafio_diario INT NOT NULL,
                PRIMARY KEY (id_usuario, data_escolha)
            )");
$stmt_escolha = $pdo->prepare("SELECT id_desafio_diario FROM desafio_escolhido
                               WHERE id_usuario = :usuario AND data_escolha = CURDATE()");
$stmt_escolha->execute([':usuario' => $_SESSION['id_usuario'] ?? 0]);
$id_escolhido = (int) $stmt_escolha->fetchColumn();

// 3. Monta o array $opcoes_hoje com o formato [ação] + [quantidade] (RF07) e os 3 níveis (RF08)
$opcoes_hoje = [];

foreach ($desafios_banco as $indice => $linha) {
    // Classificação de dificuldade (RF08)
    $dificuldade = (float) $linha['nivel_dificuldade'];
    if ($dificuldade == 1.0) {
        $nivel_texto = 'Fácil';
        $nivel_classe = 'facil';
    } elseif ($dificuldade == 2.0) {
        $nivel_texto = 'Médio';
        $nivel_classe = 'medio';
    } else {
        $nivel_texto = 'Difícil';
        $nivel_classe = 'dificil';
    }

    // Mapeamento visual da categoria para o CSS do projeto
    $id_cat = (int) $linha['id_categoria'];
    if ($id_cat === 1) {
        $cat_nome = 'Alimentação';
        $cat_classe = 'alimentacao';
        $cat_emoji = '🍎';
    } elseif ($id_cat === 2) {
        $cat_nome = 'Físico';
        $cat_classe = 'fisico';
        $cat_emoji = '🏃';
    } else {
        $cat_nome = 'Leitura';
        $cat_classe = 'leitura';
        $cat_emoji = '📚';
    }

    // Formato [ação] + [quantidade] (RF07)
    $opcoes_hoje[] = [
        'id_desafio_diario' => (int) $linha['id_desafio_diario'],
        'categoria'         => $cat_nome,
        'classe'            => $cat_classe,
        'emoji'             => $cat_emoji,
        'acao'              => $linha['nome_acao'] . ': ' . $linha['quantidade_exigida'] . ' ' . $linha['unidade_medida'],
        'nivel'             => $nivel_texto,
        'nivel_classe'      => $nivel_classe,
        'pontos'            => (int) $linha['pontos_recompensa'],
        'escolhido'         => ((int) $linha['id_desafio_diario'] === $id_escolhido),
    ];
}

?>