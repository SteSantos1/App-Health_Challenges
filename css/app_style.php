<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --verde: #00BC7D;
    --verde-forte: #009966;
    --verde-escuro: #007A55;
    --verde-claro: #ECFDF5;
    --verde-suave: #D0FAE5;
    --fundo: #F0FDF8;
    --branco: #FFFFFF;
    --texto: #0F172B;
    --texto-suave: #62748E;
    --texto-fraco: #90A1B9;
    --borda: #E2E8F0;
    --cinza-claro: #F1F5F9;

    --ambar: #FFB900;
    --ambar-fundo: #FFFBEB;
    --ambar-borda: #FEF3C6;
    --ambar-texto: #BB4D00;
    --laranja: #FF6900;
    --vermelho: #E7000B;

    --cat-alimentacao-fundo: #F0FDF4;
    --cat-alimentacao: #008236;
    --cat-fisico-fundo: #EFF6FF;
    --cat-fisico: #155DFC;
    --cat-leitura-fundo: #F5F3FF;
    --cat-leitura: #7F22FE;

    --facil-fundo: #DCFCE7;
    --facil: #008236;
    --medio-fundo: #FEF3C6;
    --medio: #BB4D00;
    --dificil-fundo: #FFE2E2;
    --dificil: #C10007;

    --sombra: 0 1px 3px rgba(15, 23, 43, 0.06), 0 6px 16px rgba(15, 23, 43, 0.04);
    --raio: 20px;
    --fonte: 'Nunito', Arial, Helvetica, sans-serif;
    --fonte-titulo: 'Outfit', 'Nunito', Arial, sans-serif;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body.app {
    font-family: var(--fonte);
    background-color: var(--fundo);
    color: var(--texto);
    min-height: 100vh;
    display: grid;
    grid-template-columns: 300px minmax(0, 1fr);
    grid-template-rows: auto 1fr;
}

a {
    color: inherit;
    text-decoration: none;
}

button {
    font-family: inherit;
    cursor: pointer;
}

h1, h2, h3 {
    font-family: var(--fonte-titulo);
    color: var(--texto);
}

.icone {
    flex-shrink: 0;
}

/* ---------- Menu lateral ---------- */
.lateral {
    grid-row: 1 / span 2;
    position: sticky;
    top: 0;
    height: 100vh;
    background-color: var(--branco);
    border-right: 1px solid var(--borda);
    display: flex;
    flex-direction: column;
}

.lateral-marca {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 24px 28px;
    border-bottom: 1px solid var(--borda);
}

.lateral-logo {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background-color: var(--verde);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.lateral-logo img {
    width: 46px;
    height: 46px;
    object-fit: contain;
}

.lateral-marca strong {
    display: block;
    font-family: var(--fonte-titulo);
    font-size: 1.2rem;
}

.lateral-marca small {
    color: var(--texto-fraco);
    font-size: 0.85rem;
}

.lateral-usuario {
    margin: 20px;
    padding: 18px;
    border-radius: 18px;
    background-color: var(--verde-claro);
    display: flex;
    align-items: center;
    gap: 14px;
}

.lateral-usuario strong {
    display: block;
    font-weight: 600;
    font-size: 1.05rem;
}

.lateral-usuario div span {
    color: var(--verde-escuro);
    font-weight: 700;
    font-size: 0.9rem;
}

.lateral-titulo {
    padding: 20px 32px 10px;
    border-top: 1px solid var(--borda);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    color: var(--texto-fraco);
}

.lateral-menu {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 0 16px;
    flex: 1;
}

.lateral-menu a,
.lateral-sair {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 18px;
    border-radius: 14px;
    font-size: 1.05rem;
    font-weight: 500;
    color: #45556C;
    transition: background-color 0.15s;
}

.lateral-menu a:hover,
.lateral-sair:hover {
    background-color: var(--verde-claro);
}

.lateral-menu a.ativo {
    background-color: var(--verde-forte);
    color: var(--branco);
    font-weight: 700;
    box-shadow: 0 6px 16px rgba(0, 153, 102, 0.25);
}

.lateral-menu a.ativo::after {
    content: '';
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background-color: var(--branco);
    margin-left: auto;
}

.lateral-sair {
    margin: 16px;
    border-top: 1px solid var(--borda);
    border-radius: 0;
    padding-top: 24px;
}

/* ---------- Barra do topo ---------- */
.topo-app {
    grid-column: 2;
    position: sticky;
    top: 0;
    z-index: 5;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 18px 36px;
    background-color: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(6px);
    border-bottom: 1px solid var(--borda);
}

.topo-app h2 {
    font-size: 1.3rem;
    font-weight: 600;
}

.selo-pontos {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 999px;
    background-color: var(--ambar-fundo);
    border: 1px solid var(--ambar-borda);
    color: var(--ambar-texto);
    font-weight: 700;
    white-space: nowrap;
}

/* ---------- Conteúdo ---------- */
.topo-app,
.conteudo {
    min-width: 0;
}

.conteudo {
    grid-column: 2;
    padding: 32px 36px 48px;
    width: 100%;
    max-width: 1440px;
}

.conteudo.estreito {
    max-width: 1180px;
    margin: 0 auto;
}

.cabeca-pagina {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.cabeca-pagina h1 {
    font-size: 2.6rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    line-height: 1.15;
    overflow-wrap: anywhere;
}

.cabeca-pagina p {
    color: var(--texto-suave);
    font-size: 1.1rem;
    margin-top: 4px;
}

.grupo-botoes {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* ---------- Componentes ---------- */
.cartao-app {
    background-color: var(--branco);
    border: 1px solid var(--borda);
    border-radius: var(--raio);
    box-shadow: var(--sombra);
}

.cartao-app.com-padding {
    padding: 28px;
}

.cartao-titulo {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    font-family: var(--fonte-titulo);
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.cartao-titulo span {
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.cartao-titulo .icone {
    color: var(--verde-forte);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 22px;
    border-radius: 14px;
    border: 1px solid transparent;
    font-size: 1.05rem;
    font-weight: 700;
    transition: background-color 0.15s, transform 0.1s;
}

.btn:active {
    transform: scale(0.98);
}

.btn-primario {
    background-color: var(--verde-forte);
    color: var(--branco);
    box-shadow: 0 6px 16px rgba(0, 153, 102, 0.25);
}

.btn-primario:hover {
    background-color: var(--verde-escuro);
}

.btn-contorno {
    background-color: var(--branco);
    border-color: var(--borda);
    color: var(--texto);
}

.btn-contorno:hover {
    background-color: var(--cinza-claro);
}

.btn-bloco {
    width: 100%;
}

.selo {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.selo.facil   { background-color: var(--facil-fundo);   color: var(--facil); }
.selo.medio   { background-color: var(--medio-fundo);   color: var(--medio); }
.selo.dificil { background-color: var(--dificil-fundo); color: var(--dificil); }
.selo.admin   { background-color: var(--cat-leitura-fundo); color: var(--cat-leitura); }

.pilula-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 8px 18px;
    border-radius: 14px;
    border: 1px solid var(--borda);
    background-color: var(--branco);
    min-width: 88px;
}

.pilula-info small {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--texto-suave);
}

.pilula-info strong {
    font-family: var(--fonte-titulo);
    font-size: 1.3rem;
}

.pilula-info.verde   { background-color: var(--verde-claro);      border-color: var(--verde-suave); }
.pilula-info.verde strong { color: var(--verde-escuro); }
.pilula-info.roxo    { background-color: var(--cat-leitura-fundo); border-color: #EDE9FE; }
.pilula-info.roxo strong { color: var(--cat-leitura); }
.pilula-info.ambar   { background-color: var(--ambar-fundo);       border-color: var(--ambar-borda); }
.pilula-info.ambar strong { color: var(--ambar-texto); }

.filtros {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.filtros-grupo {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    border-radius: 999px;
    border: 1px solid var(--borda);
    background-color: var(--branco);
    color: #45556C;
    font-size: 1rem;
    font-weight: 600;
}

.chip:hover {
    background-color: var(--cinza-claro);
}

.chip.ativo {
    background-color: var(--verde-forte);
    border-color: var(--verde-forte);
    color: var(--branco);
}

.chip.escuro.ativo {
    background-color: var(--texto);
    border-color: var(--texto);
}

.avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background-color: var(--verde-forte);
    color: var(--branco);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
}

.avatar.grande {
    width: 60px;
    height: 60px;
    font-size: 1.2rem;
}

.avatar.pequeno {
    width: 34px;
    height: 34px;
    font-size: 0.75rem;
    border: 2px solid var(--branco);
}

.emoji-caixa {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background-color: var(--cinza-claro);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.7rem;
    flex-shrink: 0;
}

.barra {
    height: 8px;
    border-radius: 999px;
    background-color: var(--cinza-claro);
    overflow: hidden;
}

.barra > span {
    display: block;
    height: 100%;
    border-radius: 999px;
    background-color: var(--verde);
}

.texto-suave {
    color: var(--texto-suave);
}

.pts {
    color: var(--ambar-texto);
    font-weight: 800;
}

.grade-2 {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.grade-3 {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
}

.lista-linhas > * + * {
    border-top: 1px solid var(--borda);
}

/* ---------- Janela (formulários) ---------- */
dialog.janela {
    margin: auto;
    border: none;
    border-radius: 24px;
    padding: 32px;
    width: min(480px, calc(100% - 32px));
    box-shadow: 0 30px 80px rgba(15, 23, 43, 0.25);
    font-family: var(--fonte);
    color: var(--texto);
}

dialog.janela::backdrop {
    background-color: rgba(15, 23, 43, 0.45);
}

.janela-topo {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.janela-topo h3 {
    font-size: 1.5rem;
}

.janela-fechar {
    border: none;
    background-color: var(--cinza-claro);
    border-radius: 10px;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--texto-suave);
}

.form-app {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.form-app label {
    display: block;
    font-weight: 700;
    margin-bottom: 6px;
}

.form-app input,
.form-app select,
.form-app textarea {
    width: 100%;
    padding: 13px 16px;
    border: 1px solid var(--borda);
    border-radius: 14px;
    font-family: inherit;
    font-size: 1rem;
    color: var(--texto);
    background-color: var(--branco);
    outline: none;
}

.form-app input:focus,
.form-app select:focus,
.form-app textarea:focus {
    border-color: var(--verde-forte);
    box-shadow: 0 0 0 3px rgba(0, 153, 102, 0.15);
}

.form-linha {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

/* ---------- Celular e telas pequenas ---------- */
@media (max-width: 1100px) {
    .grade-2 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 900px) {
    body.app {
        grid-template-columns: minmax(0, 1fr);
        padding-bottom: 78px;
    }

    .lateral {
        position: fixed;
        top: auto;
        bottom: 0;
        left: 0;
        right: 0;
        height: auto;
        z-index: 10;
        border-right: none;
        border-top: 1px solid var(--borda);
    }

    .lateral-marca,
    .lateral-usuario,
    .lateral-titulo,
    .lateral-sair {
        display: none;
    }

    .lateral-menu {
        flex-direction: row;
        justify-content: space-around;
        padding: 6px 4px;
        gap: 0;
    }

    .lateral-menu a {
        flex-direction: column;
        gap: 4px;
        padding: 8px 4px;
        font-size: 0.7rem;
        flex: 1;
        text-align: center;
        border-radius: 12px;
    }

    .lateral-menu a.ativo::after {
        display: none;
    }

    .topo-app,
    .conteudo {
        grid-column: 1;
    }

    .topo-app {
        padding: 12px 16px;
    }

    .topo-app h2 {
        font-size: 1.05rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .selo-pontos {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .selo-extra {
        display: none;
    }

    .cabeca-pagina .grupo-botoes,
    .cabeca-pagina .filtros-grupo {
        width: 100%;
    }

    .cabeca-pagina .grupo-botoes .btn {
        flex: 1;
    }

    .topo-sair {
        display: inline-flex !important;
    }

    .conteudo {
        padding: 20px 16px 32px;
    }

    .cabeca-pagina h1 {
        font-size: 1.7rem;
    }

    .cartao-app.com-padding {
        padding: 20px;
    }

    .grade-3 {
        grid-template-columns: 1fr;
    }

    .form-linha {
        grid-template-columns: 1fr;
    }
}

.topo-sair {
    display: none;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    border: 1px solid var(--borda);
    color: var(--texto-suave);
}

.topo-direita {
    display: flex;
    align-items: center;
    gap: 10px;
}

.aviso-app {
    padding: 14px 18px;
    border-radius: 14px;
    font-weight: 700;
    margin-bottom: 20px;
}

.aviso-app.sucesso {
    background-color: var(--verde-claro);
    color: var(--verde-escuro);
}
.aviso-app.erro {
    background-color: var(--dificil-fundo);
    color: var(--dificil);
}
</style>