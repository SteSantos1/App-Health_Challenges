 <style>
        .cond-grade {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.65fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .indice-cartao {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .indice-cartao .cartao-titulo {
            align-self: stretch;
        }

        .anel {
            position: relative;
            width: 190px;
            height: 190px;
            margin: 10px 0 24px;
        }

        .anel svg {
            transform: rotate(-90deg);
        }

        .anel-texto {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .anel-texto strong {
            font-family: var(--fonte-titulo);
            font-size: 3rem;
            line-height: 1;
            color: #155DFC;
        }

        .anel-texto small {
            color: var(--texto-suave);
            font-size: 1rem;
        }

        .anel-texto span {
            color: #155DFC;
            font-weight: 700;
            margin-top: 2px;
        }

        .medidas {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .medida {
            padding: 20px;
            border-radius: 16px;
            background-color: #F8FAFC;
        }

        .medida small {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--texto-suave);
            font-weight: 600;
            letter-spacing: 0.04em;
        }

        .medida strong {
            display: block;
            font-size: 1.8rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .medida .classificacao {
            font-weight: 600;
        }

        .classificacao.bom   { color: var(--verde-escuro); }
        .classificacao.aviso { color: var(--ambar-texto); }
        .classificacao.ruim  { color: var(--dificil); }

        .contagem {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--borda);
            text-align: center;
        }

        .contagem span {
            font-size: 1.8rem;
        }

        .contagem strong {
            display: block;
            font-family: var(--fonte-titulo);
            font-size: 1.6rem;
        }

        .contagem small {
            color: var(--texto-suave);
        }

        .selo-variacao {
            padding: 6px 14px;
            border-radius: 999px;
            background-color: var(--verde-claro);
            color: var(--verde-escuro);
            font-family: var(--fonte);
            font-size: 0.95rem;
            font-weight: 700;
        }

        .grafico > svg {
            width: 100%;
            height: auto;
            display: block;
        }

        .grafico-legenda {
            display: grid;
            text-align: center;
            margin-top: 6px;
        }

        .grafico-legenda small {
            display: block;
            color: var(--texto-fraco);
        }

        .grafico-legenda strong {
            color: var(--verde-escuro);
        }

        .tabela {
            width: 100%;
            border-collapse: collapse;
        }

        .tabela th,
        .tabela td {
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid var(--borda);
        }

        .tabela th {
            font-size: 0.8rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--texto-suave);
        }

        .tabela tr:last-child td {
            border-bottom: none;
        }

        .historico h3 {
            padding: 26px 30px 18px;
            border-bottom: 1px solid var(--borda);
            margin-bottom: 0;
        }

        .atividade {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 18px 30px;
        }

        .atividade:hover {
            background-color: #F8FAFC;
        }

        .atividade .emoji-caixa {
            background-color: var(--branco);
            border: 1px solid var(--borda);
        }

        .atividade strong {
            display: block;
            font-weight: 600;
            font-size: 1.15rem;
        }

        .atividade .data {
            margin-left: auto;
            color: var(--texto-suave);
            white-space: nowrap;
        }

        .dois {
            display: grid;
            grid-template-columns: minmax(0, 1.65fr) minmax(0, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        @media (max-width: 1100px) {
            .cond-grade,
            .dois {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .medidas {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .medida {
                padding: 14px;
            }

            .medida strong {
                font-size: 1.4rem;
            }

            .atividade {
                padding: 14px 16px;
                gap: 12px;
            }

            .historico h3 {
                padding: 20px 16px 14px;
            }

            .grafico-legenda small {
                font-size: 0.7rem;
            }
        }
        
    </style>