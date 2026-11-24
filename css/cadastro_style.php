<script>
(function () {
    try {
        if (localStorage.getItem('hc_daltonismo') === '1') {
            document.documentElement.setAttribute('data-daltonismo', 'ativo');
        }
    } catch (e) {}

    function sincronizarBotoesDaltonismo() {
        var ativo = document.documentElement.getAttribute('data-daltonismo') === 'ativo';
        document.querySelectorAll('[data-alternar-daltonismo]').forEach(function (botao) {
            botao.setAttribute('aria-pressed', ativo ? 'true' : 'false');
            botao.classList.toggle('ativo', ativo);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        sincronizarBotoesDaltonismo();
        document.querySelectorAll('[data-alternar-daltonismo]').forEach(function (botao) {
            botao.addEventListener('click', function () {
                var ativo = document.documentElement.getAttribute('data-daltonismo') === 'ativo';
                if (ativo) {
                    document.documentElement.removeAttribute('data-daltonismo');
                } else {
                    document.documentElement.setAttribute('data-daltonismo', 'ativo');
                }
                try {
                    localStorage.setItem('hc_daltonismo', ativo ? '0' : '1');
                } catch (e) {}
                sincronizarBotoesDaltonismo();
            });
        });
    });
})();
</script>
<style>
:root {
    --cor-fundo: #F0FDF8; 
    --cor-fundo-ilustracao: #007F58; 
    --cor-texto-principal: #0F172B; 
    --cor-texto-secundario: #475569;
    --cor-primaria: #007A55; 
    --cor-primaria-hover: #005F42;
    --cor-borda: #CBD5E1;
    --cor-fundo-input: #ffffff;
    --cor-alerta-fundo: #ECFDF5;
    --cor-alerta-borda: #6EE7B7;
    --cor-alerta-texto: #005F42;
    --cor-erro-fundo: #FFE2E2;
    --cor-erro-borda: #F87171;
    --cor-erro-texto: #A80008;
    --cor-botao-voltar: #F1F5F9;
    --fonte-padrao: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}

html[data-daltonismo="ativo"] {
    --cor-fundo: #F4F7FB;
    --cor-fundo-ilustracao: #005AB5;
    --cor-texto-secundario: #334155;
    --cor-primaria: #004C99;
    --cor-primaria-hover: #003B75;
    --cor-borda: #B8C4D4;
    --cor-alerta-fundo: #E6F2FF;
    --cor-alerta-borda: #005AB5;
    --cor-alerta-texto: #003B75;
    --cor-erro-fundo: #FFEBE0;
    --cor-erro-borda: #CC3D00;
    --cor-erro-texto: #A63200;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* 1. O HTML vira o fundo total da tela */
html {
    background-color: var(--cor-fundo);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}


body {
    font-family: var(--fonte-padrao);
    color: var(--cor-texto-principal);
    display: flex;
    width: 100%;
    max-width: 900px;
    background-color: #ffffff;
    border-radius: 40px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    margin: 0;
}


header {
    flex: 1;
    background-color: var(--cor-fundo-ilustracao);
    padding: 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    border-top-left-radius: 40px;
    border-bottom-left-radius: 40px;
    position: relative;
    overflow: hidden;
}

header img {
    width: 360px;
    max-width: 90%;
    height: auto;
    margin-bottom: 24px;
    position: relative;
}

header h2 {
    font-size: 2rem;
    font-weight: 800;
    color: #FFFFFF;
    line-height: 1.2;
    position: relative;
}

.folha {
    position: absolute;
    background-color: rgba(255, 255, 255, 0.14);
}
.folha-1 {
    width: 200px;
    height: 90px;
    top: -10px;
    left: -10px;
    border-radius: 0 100% 0 100%;
}
.folha-2 {
    width: 110px;
    height: 190px;
    top: 70px;
    right: 10px;
    border-radius: 100% 0 100% 0;
    transform: rotate(20deg);
}

/* 4. O MAIN vira a coluna branca da direita */
main {
    flex: 1;
    padding: 48px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Estilos internos do formulário */

.fundo-barra {
    width: 100%;
    height: 6px;
    background-color: #E2E8F0;
    border-radius: 4px;
    margin-bottom: 32px;
    overflow: hidden;
}

.barra-verde {
    width: 50%;
    height: 100%;
    background-color: var(--cor-primaria);
    border-radius: 4px;
    transition: width 0.4s ease-in-out;
}

h1 {
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 4px;
    color: var(--cor-texto-principal);
}

p {
    color: var(--cor-texto-secundario);
    font-size: 0.95rem;
    margin-bottom: 24px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

label {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--cor-texto-principal);
    margin-bottom: 6px;
    display: inline-block;
}

input {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid var(--cor-borda);
    border-radius: 50px; 
    padding: 14px 20px;
    font-size: 1rem;
    color: var(--cor-texto-principal);
    background-color: var(--cor-fundo-input);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}

input:focus {
    border-color: var(--cor-primaria);
    box-shadow: 0 0 0 3px rgba(0, 153, 102, 0.15);
}

.grupo-fisico {
    display: flex;
    gap: 16px;
}

.campo-metade {
    flex: 1;
}

.aviso-privacidade {
    background-color: var(--cor-alerta-fundo);
    padding: 16px;
    border-radius: 12px;
    margin-top: 8px;
    margin-bottom: 16px;
}

.aviso-privacidade p {
    color: var(--cor-alerta-texto);
    font-size: 0.8rem;
    font-weight: 500;
    margin: 0;
    line-height: 1.4;
}

.aviso-app {
    padding: 14px 18px;
    border-radius: 14px;
    border: 1px solid transparent;
    border-left-width: 5px;
    font-weight: 700;
    line-height: 1.4;
    margin-bottom: 20px;
}

.aviso-app.erro {
    background-color: var(--cor-erro-fundo);
    border-color: var(--cor-erro-borda);
    border-left-color: var(--cor-erro-texto);
    color: var(--cor-erro-texto);
}

.aviso-app.sucesso {
    background-color: var(--cor-alerta-fundo);
    border-color: var(--cor-alerta-borda);
    border-left-color: var(--cor-primaria);
    color: var(--cor-alerta-texto);
}

.aviso-app.informacao {
    background-color: var(--cor-fundo);
    border-color: var(--cor-borda);
    border-left-color: var(--cor-primaria);
    color: var(--cor-alerta-texto);
}

.aviso-app:empty {
    display: none;
}

#email-status {
    margin: 8px 0 0;
    font-size: 0.9rem;
}

/* Botões */
button {
    width: 100%;
    padding: 16px;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.2s, transform 0.1s, box-shadow 0.2s;
    margin-top: 16px;
}

button:active {
    transform: scale(0.98);
}

button[type="button"][onclick="avancarPasso()"],
button.btn-principal {
    background-color: var(--cor-primaria);
    color: white;
}

button[type="button"][onclick="avancarPasso()"]:hover,
button.btn-principal:hover {
    background-color: var(--cor-primaria-hover);
    box-shadow: 0 4px 12px rgba(0, 153, 102, 0.25);
}

button.btn-daltonismo {
    width: auto;
    align-self: flex-end;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    margin: 0 0 14px;
    border-radius: 999px;
    border: 1px solid var(--cor-borda);
    background-color: #FFFFFF;
    color: var(--cor-texto-principal);
    font-size: 0.85rem;
    font-weight: 700;
}

button.btn-daltonismo.ativo {
    background-color: var(--cor-primaria);
    border-color: var(--cor-primaria-hover);
    color: #FFFFFF;
}

.botoes-acao {
    display: flex;
    gap: 12px;
    margin-top: 8px;
}

button[onclick="voltarPasso()"] {
    background-color: var(--cor-botao-voltar);
    color: var(--cor-texto-principal);
    flex: 0.4;
    margin-top: 0;
}

button[onclick="voltarPasso()"]:hover {
    background-color: #E2E8F0;
}

.botoes-acao .btn-principal {
    flex: 1.6;
    margin-top: 0;
}


@media (max-width: 768px) {
    body {
        flex-direction: column;
        max-width: 440px;
    }
    html {
        padding: 12px;
        align-items: flex-start;
    }
    body {
        border-radius: 28px;
    }
    header {
        padding: 24px;
        border-radius: 0;
    }
    header img {
        width: 150px;
        margin-bottom: 12px;
    }
    header h2 {
        font-size: 1.4rem;
    }
    .folha-2 {
        display: none;
    }
    main {
        padding: 28px 20px;
    }
    h1 {
        font-size: 1.8rem;
    }
    .grupo-fisico {
        flex-direction: column;
        gap: 16px;
    }
}

</style>