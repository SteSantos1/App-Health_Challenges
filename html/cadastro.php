<?php
require_once __DIR__ . '/../php/env.php';

$antigo = $_SESSION['cadastro_form'] ?? [];
unset($_SESSION['cadastro_form']);

$mensagens_erro = [
    'nome' => ['Informe seu nome completo.', 1],
    'email-invalido' => ['Informe um e-mail válido.', 1],
    'email-em-uso' => ['Este e-mail já está em uso.', 1],
    'senhas' => ['As senhas não coincidem.', 1],
    'data' => ['Informe uma data de nascimento válida.', 2],
    'altura' => ['Altura inválida. Informe um valor entre 0,5 e 2,5 metros.', 2],
    'peso' => ['Peso inválido. Informe um valor entre 20 e 400 kg.', 2],
    'falha' => ['Não foi possível concluir o cadastro. Tente novamente.', 1],
];

$erro_cadastro = $mensagens_erro[$_GET['erro'] ?? ''] ?? null;

function valor_antigo(array $antigo, string $campo): string
{
    return htmlspecialchars($antigo[$campo] ?? '', ENT_QUOTES);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar conta | Health Challenges</title>
    <?php include __DIR__ . '/../css/cadastro_style.php'; ?>
</head>

<body>
    <header>
        <span class="folha folha-1"></span>
        <span class="folha folha-2"></span>
        <img src="../img/mascote.png" alt="">
        <h2>Health Challenges</h2>
    </header>
    <main>
        <button type="button" class="btn-daltonismo" data-alternar-daltonismo aria-pressed="false" aria-label="Modo de cores para daltonismo" title="Alternar contraste para daltonismo">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 0 0 20Z" fill="currentColor"/>
            </svg>
            <span>Daltonismo</span>
        </button>

        <?php if ($erro_cadastro): ?>
            <p class="aviso-app erro" role="alert"><?= htmlspecialchars($erro_cadastro[0]) ?></p>
        <?php endif; ?>

        <form action="../php/registro.php" method="post">

            <div class="fundo-barra" aria-hidden="true">
                <div class="barra-verde" id="barra-progresso"></div>
            </div>

            <div id="passo1">
                <div>
                    <h1 tabindex="-1">Criar Conta</h1>
                    <p>Passo 1 de 2 - informações básicas</p>
                </div>

                <div>
                    <label for="name">Nome Completo</label>
                    <br>
                    <input type="text" name="name" id="name" autocomplete="name" value="<?= valor_antigo($antigo, 'name') ?>" required>
                </div>

                <div>
                    <label for="email">E-mail</label>
                    <br>
                    <input type="email" name="email" id="email" autocomplete="email" value="<?= valor_antigo($antigo, 'email') ?>" required>
                    <p id="email-status" role="status" aria-live="polite"></p>
                </div>
                <div>
                    <label for="password">Senha</label>
                    <br>
                    <input type="password" name="password" id="password" autocomplete="new-password" required>
                </div>
                <div>
                    <label for="confirm-password">Confirmar Senha</label>
                    <br>
                    <input type="password" name="confirm-password" id="confirm-password" autocomplete="new-password" required>
                </div>
                <button type="button" onclick="avancarPasso()">Continuar <span aria-hidden="true">&rarr;</span></button>
            </div>

            <div id="passo2" style="display:none;">
                <div>
                    <h1 tabindex="-1">Dados físicos</h1>
                    <p>Passo 2 de 2 - Estas informações personalizam sua experiência</p>
                </div>
                <div>
                    <label for="date-birth">Data de nascimento</label>
                    <br>
                    <input type="date" name="date-birth" id="date-birth" autocomplete="bday" value="<?= valor_antigo($antigo, 'date-birth') ?>" required>
                </div>
                <div class="grupo-fisico">
                    <div class="campo-metade">
                        <label for="height">Altura (m)</label>
                        <br>
                        <input type="text" name="height" id="height" placeholder="1.75" value="<?= valor_antigo($antigo, 'height') ?>" required>
                    </div>

                    <div class="campo-metade">
                        <label for="weight">Peso (kg)</label>
                        <br>
                        <input type="text" name="weight" id="weight" placeholder="70" value="<?= valor_antigo($antigo, 'weight') ?>" required>
                    </div>
                </div>

                <div class="aviso-privacidade">
                    <p>Seus dados são privados e utilizados apenas para personalizar sua experiência de saúde e calcular seu IMC.</p>
                </div>

                <div class="botoes-acao">
                    <button type="button" onclick="voltarPasso()"><span aria-hidden="true">&larr;</span> Voltar</button>

                    <button type="submit" class="btn-principal">Criar conta</button>
                </div>

            </div>
        </form>
    </main>
    <?php include __DIR__ . '/../javascript/cadastro_script.php'; ?>
    <?php if ($erro_cadastro && $erro_cadastro[1] === 2): ?>
        <script>
            document.getElementById('passo1').style.display = 'none';
            document.getElementById('passo2').style.display = 'block';
            document.getElementById('barra-progresso').style.width = '100%';
        </script>
    <?php endif; ?>
</body>
</html>