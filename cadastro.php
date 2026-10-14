<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Challenges</title>
    <?php include 'style.php'; ?>
</head>

<body>
    <header>
        <img src="#" alt="logo">
        <h2>Health Challenges</h2>
    </header>
    <main>
        <form action="registro.php" method="post">

            <div class="fundo-barra">
                <div class="barra-verde" id="barra-progresso"></div>
            </div>

            <div id="passo1">
                <div>
                    <h1>Criar Conta</h1>
                    <p>Passo 1 de 2 - informações básicas</p>
                </div>

                <div>
                    <label for="name">Nome Completo</label>
                    <br>
                    <input type="text" name="name" id="name" required>
                </div>

                <div>
                    <label for="email">E-mail</label>
                    <br>
                    <input type="email" name="email" id="email" required>
                </div>
                <div>
                    <label for="password">Senha</label>
                    <br>
                    <input type="password" name="password" id="password" required>
                </div>
                <div>
                    <label for="confirm-password">Confirmar Senha</label>
                    <br>
                    <input type="password" name="confirm-password" id="confirm-password" required>
                </div>
                <button type="button" onclick="avancarPasso()">Continuar &rarr;</button>
            </div>

            <div id="passo2" style="display:none;">
                <div>
                    <h1>Dados físicos</h1>
                    <p>Passo 2 de 2 - Estas informações personalizam sua experiência</p>
                </div>
                <div>
                    <label for="date-birth">Data de nascimento</label>
                    <br>
                    <input type="date" name="date-birth" id="date-birth" required>
                </div>
                <div class="grupo-fisico">
                    <div class="campo-metade">
                        <label for="height">Altura (cm)</label>
                        <br>
                        <input type="number" name="height" id="height" placeholder="175" required>
                    </div>

                    <div class="campo-metade">
                        <label for="weight">Peso (kg)</label>
                        <br>
                        <input type="number" name="weight" id="weight" placeholder="70" required>
                    </div>
                </div>

                <div class="aviso-privacidade">
                    <p>🔒 Seus dados são privados e utilizados apenas para personalizar sua experiência de saúde e calcular seu IMC.</p>
                </div>

                <div class="botoes-acao">
                    <button type="button" onclick="voltarPasso()">&larr; Voltar</button>

                    <button type="submit" class="btn-principal">Criar conta</button>
                </div>

            </div>
        </form>
    </main>
    <?php include 'script.php'; ?>
</body>
</html>