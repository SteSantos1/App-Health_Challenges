-- TODAS AS ALTERACOES DO BANCO - Health Challenges
-- Para bancos que ja possuem o esquema-base do projeto.
-- Nao apaga tabelas nem dados. Faça backup antes e confirme que o banco ativo e healthchallenges.
-- Execute no MySQL Workbench ou phpMyAdmin.

USE healthchallenges;

SELECT DATABASE() AS banco_selecionado;

-- 1. RF20/RF23/RF24: tabelas de solicitacoes de ingresso e convites.
-- O CREATE IF NOT EXISTS permite executar esta parte mesmo se as tabelas ja existirem.

CREATE TABLE IF NOT EXISTS solicitacao_ingresso_grupo (
    id_solicitacao INT NOT NULL AUTO_INCREMENT,
    id_grupo INT NOT NULL,
    id_usuario INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    data_solicitacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_solicitacao),
    KEY idx_solicitacao_grupo_status (id_grupo, status),
    KEY idx_solicitacao_usuario (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS convite_grupo (
    id_convite INT NOT NULL AUTO_INCREMENT,
    id_grupo INT NOT NULL,
    id_usuario_convidado INT NOT NULL,
    id_usuario_remetente INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    data_convite DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_convite),
    KEY idx_convite_convidado_status (id_usuario_convidado, status),
    KEY idx_convite_grupo (id_grupo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabelas que o PHP tambem cria automaticamente, declaradas aqui para completar o esquema.

CREATE TABLE IF NOT EXISTS desafio_escolhido (
    id_usuario INT NOT NULL,
    data_escolha DATE NOT NULL,
    id_desafio_diario INT NOT NULL,
    PRIMARY KEY (id_usuario, data_escolha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS conquista_usuario (
    id_conquista_usuario INT NOT NULL AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    chave_conquista VARCHAR(50) NOT NULL,
    data_desbloqueio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_conquista_usuario),
    UNIQUE KEY uq_conquista_usuario (id_usuario, chave_conquista)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. RF25/RF26: associar desafios a grupos.
-- A coluna e a chave estrangeira sao criadas somente se ainda estiverem ausentes.

SET @ddl = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE desafio ADD COLUMN id_grupo INT NULL DEFAULT NULL',
        'SELECT ''OK: desafio.id_grupo ja existe'' AS resultado'
    )
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'desafio'
      AND COLUMN_NAME = 'id_grupo'
);
PREPARE migracao FROM @ddl;
EXECUTE migracao;
DEALLOCATE PREPARE migracao;

SET @ddl = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE desafio ADD CONSTRAINT fk_desafio_grupo FOREIGN KEY (id_grupo) REFERENCES grupo (id_grupo)',
        'SELECT ''OK: chave estrangeira de desafio.id_grupo ja existe'' AS resultado'
    )
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'desafio'
      AND COLUMN_NAME = 'id_grupo'
      AND REFERENCED_TABLE_NAME = 'grupo'
      AND REFERENCED_COLUMN_NAME = 'id_grupo'
);
PREPARE migracao FROM @ddl;
EXECUTE migracao;
DEALLOCATE PREPARE migracao;

-- 3. RF07/RF08: tornar o ID do desafio diario AUTO_INCREMENT.
-- Preserva o estado anterior de FOREIGN_KEY_CHECKS.

SET @foreign_key_checks_anterior = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;

SET @ddl = (
    SELECT IF(
        COUNT(*) > 0,
        'SELECT ''OK: desafio_diario.id_desafio_diario ja e AUTO_INCREMENT'' AS resultado',
        'ALTER TABLE desafio_diario MODIFY COLUMN id_desafio_diario INT NOT NULL AUTO_INCREMENT'
    )
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'desafio_diario'
      AND COLUMN_NAME = 'id_desafio_diario'
      AND EXTRA LIKE '%auto_increment%'
);
PREPARE migracao FROM @ddl;
EXECUTE migracao;
DEALLOCATE PREPARE migracao;

SET FOREIGN_KEY_CHECKS = @foreign_key_checks_anterior;

-- 4. WhatsApp: dados-base de categorias e acoes dos desafios diarios.
-- INSERT IGNORE evita duplicar IDs que ja existam e nao sobrescreve os dados existentes.

INSERT IGNORE INTO categoria_saude
    (id_categoria, nome_categoria, cor_hexadecimal)
VALUES
    (1, 'Alimentação', '#008236'),
    (2, 'Físico', '#155DFC'),
    (3, 'Leitura/Estudo', '#7F22FE');

INSERT IGNORE INTO acao_base
    (id_acao, nome_acao, nivel_dificuldade, unidade_medida, fator_pontos, id_categoria)
VALUES
    (1, 'Beber água', 1.0, 'L', 25, 1),
    (2, 'Comer frutas no dia', 1.0, 'unid', 25, 1),
    (3, 'Tomar chá sem açúcar', 1.0, 'xícaras', 25, 1),
    (4, 'Incluir vegetais no prato', 2.0, 'porções', 20, 1),
    (5, 'Fazer refeições limpas', 2.0, 'refeições', 20, 1),
    (6, 'Consumir proteína magra', 2.0, 'porções', 20, 1),
    (7, 'Ficar sem açúcar refinado', 3.0, 'horas', 5, 1),
    (8, 'Preparar comida saudável', 3.0, 'refeições', 25, 1),
    (9, 'Beber meta alta de água', 3.0, 'L', 25, 1),
    (10, 'Fazer alongamento corporal', 1.0, 'min', 5, 2),
    (11, 'Caminhar ao ar livre', 1.0, 'km', 25, 2),
    (12, 'Subir escadas', 1.0, 'lances', 10, 2),
    (13, 'Fazer polichinelos', 2.0, 'rep', 2, 2),
    (14, 'Pedalar em ritmo moderado', 2.0, 'km', 15, 2),
    (15, 'Fazer abdominais', 2.0, 'rep', 3, 2),
    (16, 'Correr continuamente', 3.0, 'km', 30, 2),
    (17, 'Realizar treino de força', 3.0, 'min', 4, 2),
    (18, 'Fazer agachamentos livres', 3.0, 'rep', 3, 2),
    (19, 'Ler um livro', 1.0, 'páginas', 5, 3),
    (20, 'Revisar anotações de aula', 1.0, 'min', 4, 3),
    (21, 'Praticar novo idioma', 1.0, 'palavras', 5, 3),
    (22, 'Ler focado sem celular', 2.0, 'min', 4, 3),
    (23, 'Resolver exercícios', 2.0, 'questões', 10, 3),
    (24, 'Assistir videoaulas', 2.0, 'min', 3, 3),
    (25, 'Estudar conteúdo técnico', 3.0, 'min', 3, 3),
    (26, 'Ler capítulos de um livro', 3.0, 'capítulos', 40, 3),
    (27, 'Escrever resumos de estudo', 3.0, 'páginas', 30, 3);

-- 5. RF13/RF14: guardar pontos ganhos na conclusao e impedir conclusao duplicada.
-- Se ja houver duplicatas, o indice e intencionalmente ignorado; revise os dados antes de tentar novamente.

SET @ddl = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE registro_conclusao ADD COLUMN pontos_ganhos INT NOT NULL DEFAULT 0',
        'SELECT ''OK: registro_conclusao.pontos_ganhos ja existe'' AS resultado'
    )
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'registro_conclusao'
      AND COLUMN_NAME = 'pontos_ganhos'
);
PREPARE migracao FROM @ddl;
EXECUTE migracao;
DEALLOCATE PREPARE migracao;

SET @duplicatas_registro = (
    SELECT COUNT(*)
    FROM (
        SELECT id_usuario, id_desafio_diario
        FROM registro_conclusao
        GROUP BY id_usuario, id_desafio_diario
        HAVING COUNT(*) > 1
    ) AS duplicatas
);

SET @ddl = (
    SELECT IF(
        COUNT(*) > 0,
        'SELECT ''OK: indice de conclusao ja existe'' AS resultado',
        IF(
            @duplicatas_registro = 0,
            'ALTER TABLE registro_conclusao ADD CONSTRAINT uq_registro_usuario_desafio UNIQUE (id_usuario, id_desafio_diario)',
            'SELECT ''ATENCAO: ha conclusoes duplicadas; revise-as antes de criar o indice'' AS resultado'
        )
    )
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'registro_conclusao'
      AND INDEX_NAME = 'uq_registro_usuario_desafio'
);
PREPARE migracao FROM @ddl;
EXECUTE migracao;
DEALLOCATE PREPARE migracao;

-- 6. RF26: proteger contra participacoes repetidas no mesmo desafio de grupo.
-- O dump atual ja possui este indice. Se houver duplicatas, revise-as antes de cria-lo.

SET @duplicatas_participacao = (
    SELECT COUNT(*)
    FROM (
        SELECT id_usuario, id_desafio, id_grupo
        FROM participacao
        WHERE id_grupo IS NOT NULL
        GROUP BY id_usuario, id_desafio, id_grupo
        HAVING COUNT(*) > 1
    ) AS duplicatas
);

SET @ddl = (
    SELECT IF(
        COUNT(*) > 0,
        'SELECT ''OK: indice de participacao ja existe'' AS resultado',
        IF(
            @duplicatas_participacao = 0,
            'ALTER TABLE participacao ADD UNIQUE KEY uq_participacao_usuario_desafio_grupo (id_usuario, id_desafio, id_grupo)',
            'SELECT ''ATENCAO: ha participacoes de grupo duplicadas; revise-as antes de criar o indice'' AS resultado'
        )
    )
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'participacao'
      AND INDEX_NAME = 'uq_participacao_usuario_desafio_grupo'
);
PREPARE migracao FROM @ddl;
EXECUTE migracao;
DEALLOCATE PREPARE migracao;

-- 7. Dados demonstrativos de desafios globais do alteracoes_sprint3.sql.
-- Cada desafio e inserido somente se ainda nao existir pelo titulo.

INSERT INTO desafio
    (titulo, descricao, categoria, data_inicio, data_fim, tipo_meta, valor_meta)
SELECT 'Pedal de Fim de Ano', 'Pedalar 100 km ate o fim do ano', 'fisica',
       '2026-10-15', '2026-12-31', 'km', 100
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM desafio WHERE titulo = 'Pedal de Fim de Ano'
);

INSERT INTO desafio
    (titulo, descricao, categoria, data_inicio, data_fim, tipo_meta, valor_meta)
SELECT 'Estudo Constante', 'Estudar 20 horas ate meados de dezembro', 'leitura',
       '2026-10-20', '2026-12-20', 'horas', 20
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM desafio WHERE titulo = 'Estudo Constante'
);

INSERT INTO desafio
    (titulo, descricao, categoria, data_inicio, data_fim, tipo_meta, valor_meta)
SELECT 'Prato Colorido', 'Comer 90 porcoes de vegetais', 'alimento',
       '2026-10-25', '2026-12-31', 'porcoes', 90
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM desafio WHERE titulo = 'Prato Colorido'
);

-- 8. Sem ALTER adicional para progresso de desafios de grupo.
-- participacao.progresso_atual ja existe como INT no dump; desafios de grupo usam
-- o id_grupo preenchido, e o status ja guarda valores como 'ativo'.
