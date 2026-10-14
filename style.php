<style>
:root {
    --cor-fundo: #f4fbf7;
    --cor-texto-principal: #0f172a;
    --cor-texto-secundario: #64748b;
    --cor-primaria: #00c471;
    --cor-borda: #e2e8f0;
    --cor-fundo-input: #ffffff;
    --cor-alerta-fundo: #e6f9f0;
    --cor-alerta-texto: #047857;
    --cor-botao-voltar: #f1f5f9;
    --fonte-padrao: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: var(--fonte-padrao);
    background-color: var(--cor-fundo);
    color: var(--cor-texto-principal);
    display: flex;
    justify-content: center;
    padding: 20px;
}

main {
    width: 100%;
    max-width: 400px;
    background: transparent;
}

header {
    text-align: center;
    margin-bottom: 24px;
}

header h2 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-top: 8px;
}

.fundo-barra {
    width: 100%;
    height: 6px;
    background-color: #e2e8f0;
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
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 4px;
}

p {
    color: var(--cor-texto-secundario);
    font-size: 0.9rem;
    margin-bottom: 24px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--cor-texto-secundario);
    margin-bottom: 6px;
    display: inline-block;
}

input {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid var(--cor-borda);
    border-radius: 12px;
    font-size: 1rem;
    background-color: var(--cor-fundo-input);
    outline: none;
    transition: border-color 0.2s;
}

input:focus {
    border-color: var(--cor-primaria);
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
    margin: 0;
    line-height: 1.4;
}

button {
    width: 100%;
    padding: 16px;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.2s, transform 0.1s;
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
    background-color: #00a860;
}

.botoes-acao {
    display: flex;
    gap: 12px;
    margin-top: 8px;
}

button[onclick="voltarPasso()"] {
    background-color: var(--cor-botao-voltar);
    color: var(--cor-texto-principal);
    flex: 0.5;
    margin-top: 0;
}

.botoes-acao .btn-principal {
    flex: 1.5;
    margin-top: 0;
}

.menu-navegacao {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
    margin-bottom: 24px;
}

.menu-navegacao a {
    color: var(--cor-texto-principal);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
}

.menu-navegacao a:hover {
    color: var(--cor-primaria);
}
</style>