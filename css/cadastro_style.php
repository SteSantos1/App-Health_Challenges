<style>
:root {
    --cor-fundo: #fbf9f4; 
    --cor-fundo-ilustracao: #e4efe4; 
    --cor-texto-principal: #503822; 
    --cor-texto-secundario: #64748b;
    --cor-primaria: #2d6a4f; 
    --cor-borda: #cbd5e1;
    --cor-fundo-input: #ffffff;
    --cor-alerta-fundo: #e6f4ea;
    --cor-alerta-texto: #1b4d3e;
    --cor-botao-voltar: #e2dcd5;
    --fonte-padrao: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
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
    color: var(--cor-texto-principal);
    line-height: 1.2;
    position: relative;
}

.folha {
    position: absolute;
    background-color: #CFE0C3;
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

/* === Estilos Internos do Formulário === */

.fundo-barra {
    width: 100%;
    height: 6px;
    background-color: #e2dcd5;
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
    box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1);
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
    background-color: #1b4d3e;
    box-shadow: 0 4px 12px rgba(45, 106, 79, 0.15);
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
    background-color: #d6cfc8;
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
    header {
        display: none; /* Esconde a lateral verde no celular para focar no cadastro */
    }
    main {
        padding: 32px 24px;
    }
}

</style>