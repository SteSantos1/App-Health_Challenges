-- Sprint 3 - RF10/RF11 (Desafios Globais)
-- LEIAAAA AQUIII!
-- VC vai dar um ctrl A e ctrl C e -> Rodar UMA vez no phpMyAdmin: banco healthchallenges > aba SQL > COLAR oq vc copiou > "Executar" ou "Continuar"

-- Pra que serve
-- 1) Separar desafio global de desafio de grupo
--    id_grupo vazio (NULL) = desafio global
--    id_grupo preenchido   = desafio interno de um grupo (RF25)
ALTER TABLE desafio
    ADD COLUMN id_grupo INT NULL DEFAULT NULL;

ALTER TABLE desafio
    ADD CONSTRAINT fk_desafio_grupo
    FOREIGN KEY (id_grupo) REFERENCES grupo (id_grupo);

-- 2) Desafios globais de exemplo que continuam ativos depois de outubro
INSERT INTO desafio (titulo, descricao, categoria, data_inicio, data_fim, tipo_meta, valor_meta) VALUES
('Pedal de Fim de Ano', 'Pedalar 100 km ate o fim do ano',        'fisica',   '2026-10-15', '2026-12-31', 'km',      100),
('Estudo Constante',    'Estudar 20 horas ate meados de dezembro', 'leitura',  '2026-10-20', '2026-12-20', 'horas',   20),
('Prato Colorido',      'Comer 90 porcoes de vegetais',            'alimento', '2026-10-25', '2026-12-31', 'porcoes', 90);