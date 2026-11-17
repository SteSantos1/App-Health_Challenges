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
                    <input type="text" name="name" id="name" autocomplete="name" required>
                </div>

                <div>
                    <label for="email">E-mail</label>
                    <br>
                    <input type="email" name="email" id="email" autocomplete="email" required>
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
                    <input type="date" name="date-birth" id="date-birth" autocomplete="bday" required>
                </div>
                <div class="grupo-fisico">
                    <div class="campo-metade">
                        <label for="height">Altura (m)</label>
                        <br>
                        <input type="text" name="height" id="height" placeholder="1.75" required>
                    </div>

                    <div class="campo-metade">
                        <label for="weight">Peso (kg)</label>
                        <br>
                        <input type="text" name="weight" id="weight" placeholder="70" required>
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
</body>
</html>