
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

<style>

:root {
    --bege: #F3EEE6;
    --verde: #2F6B3F;
    --verde-escuro: #24552F;
    --verde-claro: #E2EBDA;
    --folha: #CFE0C3;
    --marrom: #6B4A2F;
    --texto: #2B2B2B;
    --texto-suave: #5F6B62;
    --borda: #D9D4CB;
    --branco: #FFFFFF;
    --erro-fundo: #FDECEC;
    --erro-texto: #B42318;
    --sucesso-fundo: #E6F2E2;
    --fonte: 'Nunito', Arial, Helvetica, sans-serif;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: var(--fonte);
    background-color: var(--bege);
    color: var(--texto);
    min-height: 100vh;
}

.marca {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 28px 32px 12px;
}

.marca-icone {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background-color: var(--verde);
    color: var(--branco);
    display: flex;
    align-items: center;
    justify-content: center;
}

.marca-nome {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--verde);
}

.cartao {
    display: flex;
    max-width: 1200px;
    min-height: 680px;
    margin: 8px auto 32px;
    background-color: var(--branco);
    border-radius: 32px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(107, 74, 47, 0.12);
}

.lado-mascote {
    position: relative;
    flex: 1;
    background-color: var(--verde-claro);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    padding: 40px 48px 56px;
    text-align: center;
    overflow: hidden;
}

.folha {
    position: absolute;
    background-color: var(--folha);
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

.mascote {
    width: 300px;
    max-width: 80%;
    margin-bottom: 70px;
    position: relative;
}

.lado-mascote h2 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--marrom);
    line-height: 1.5;
    margin-bottom: 8px;
}

.lado-mascote p {
    color: var(--texto-suave);
    font-size: 1.1rem;
    line-height: 1.6;
}

/* Lado direito (formulário) */
.lado-form {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 48px 70px;
}

.sobretitulo {
    font-size: 0.85rem;
    font-weight: 800;
    letter-spacing: 0.15em;
    color: var(--verde);
    margin-bottom: 12px;
}

.lado-form h1 {
    font-size: 2.6rem;
    font-weight: 900;
    color: var(--marrom);
    margin-bottom: 10px;
}

.subtitulo {
    font-size: 1.15rem;
    color: var(--texto-suave);
    margin-bottom: 32px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

label {
    display: block;
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 10px;
}

input {
    width: 100%;
    padding: 16px 18px;
    font-family: var(--fonte);
    font-size: 1rem;
    border: 1px solid var(--borda);
    border-radius: 12px;
    background-color: var(--branco);
    outline: none;
}

input::placeholder {
    color: #8A8F8B;
    font-weight: 600;
}

input:focus {
    border-color: var(--verde);
    box-shadow: 0 0 0 3px rgba(47, 107, 63, 0.18);
}

/* Campo de senha com o botão "Mostrar" dentro */
.campo-senha {
    position: relative;
}

.campo-senha input {
    padding-right: 100px;
}

.btn-olho {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: none;
    font-family: var(--fonte);
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--verde);
    cursor: pointer;
    padding: 6px 8px;
}

.btn-principal {
    width: 100%;
    padding: 16px;
    border: none;
    border-radius: 12px;
    background-color: var(--verde);
    color: var(--branco);
    font-family: var(--fonte);
    font-size: 1.15rem;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 8px 18px rgba(47, 107, 63, 0.25);
}

.btn-principal:hover {
    background-color: var(--verde-escuro);
}

.link-cadastro {
    text-align: center;
    margin-top: 28px;
}

.link-cadastro a {
    color: var(--verde);
    font-size: 1.1rem;
    font-weight: 800;
    text-decoration: none;
}

.link-cadastro a:hover {
    text-decoration: underline;
}


.aviso {
    padding: 12px 16px;
    border-radius: 12px;
    font-weight: 600;
    margin-bottom: 20px;
}

.aviso-erro {
    background-color: var(--erro-fundo);
    color: var(--erro-texto);
}

#erro-js {
    margin-bottom: 0;
}

.aviso-sucesso {
    background-color: var(--sucesso-fundo);
    color: var(--verde);
}

.escondido {
    display: none;
}

.topo {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 32px;
    background-color: var(--branco);
    box-shadow: 0 2px 10px rgba(107, 74, 47, 0.08);
}

.topo .marca {
    padding: 0;
}

.btn-sair {
    padding: 10px 22px;
    border-radius: 12px;
    background-color: var(--marrom);
    color: var(--branco);
    font-weight: 800;
    text-decoration: none;
}

.btn-sair:hover {
    background-color: #573B24;
}

.conteudo {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 24px;
}

.conteudo h1 {
    font-size: 2.2rem;
    font-weight: 900;
    color: var(--marrom);
    margin-bottom: 8px;
}

.conteudo p {
    color: var(--texto-suave);
    font-size: 1.1rem;
}

@media (max-width: 860px) {
    .cartao {
        flex-direction: column;
        margin: 8px 16px 24px;
        min-height: 0;
        border-radius: 24px;
    }

    .lado-mascote {
        padding: 24px 24px 28px;
    }

    .mascote {
        width: 160px;
        margin-bottom: 12px;
    }

    .lado-mascote h2 {
        font-size: 1.4rem;
    }

    .lado-mascote p {
        font-size: 1rem;
    }

    .folha-2 {
        display: none;
    }

    .lado-form {
        padding: 32px 24px 36px;
    }

    .lado-form h1 {
        font-size: 2rem;
    }

    .marca {
        padding: 20px 16px 8px;
    }

    .topo {
        padding: 12px 16px;
    }

    .marca-nome {
        font-size: 1.2rem;
    }
}
</style>
