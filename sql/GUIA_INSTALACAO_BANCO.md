# Ajustes no banco para RF13/RF14

Este guia é para quem já tem o banco de desenvolvimento `healthchallenges` com as tabelas e os dados-base do projeto. Ele lista somente os dois ajustes aplicados manualmente para as conclusões dos desafios diários.

Execute os comandos no phpMyAdmin ou MySQL Workbench, com o banco `healthchallenges` selecionado. Antes, confira cada item: os `ALTER TABLE` devem ser executados apenas se a alteração ainda não existir.

## 1. Guardar os pontos ganhos na conclusão

Confira se a coluna já existe:

```sql
SHOW COLUMNS FROM registro_conclusao LIKE 'pontos_ganhos';
```

Se não retornar linhas, execute:

```sql
ALTER TABLE registro_conclusao
ADD COLUMN pontos_ganhos INT NOT NULL DEFAULT 0;
```

O valor padrão mantém conclusões antigas válidas. As novas conclusões gravam a recompensa definida em `desafio_diario.pontos_recompensa`.

## 2. Impedir conclusão duplicada do mesmo desafio diário

Confira se já existem pares duplicados:

```sql
SELECT id_usuario, id_desafio_diario, COUNT(*) AS quantidade
FROM registro_conclusao
GROUP BY id_usuario, id_desafio_diario
HAVING COUNT(*) > 1;
```

Se retornar linhas, não crie o índice ainda: a equipe precisa revisar os registros duplicados e decidir como tratá-los.

Se não retornar linhas, confira se o índice já existe:

```sql
SHOW INDEX FROM registro_conclusao
WHERE Key_name = 'uq_registro_usuario_desafio';
```

Se não retornar linhas, execute:

```sql
ALTER TABLE registro_conclusao
ADD CONSTRAINT uq_registro_usuario_desafio
UNIQUE (id_usuario, id_desafio_diario);
```

Esse índice preserva a chave primária `id_registro` e as chaves estrangeiras. Ele permite que um usuário conclua desafios diários diferentes, mas impede uma segunda conclusão para a mesma combinação de usuário e desafio diário.

## Observação

O script `alteracoes_sprint3.sql` é uma alteração anterior, já disponível no repositório, para a funcionalidade de desafios globais. Este guia não pede que ele seja executado novamente.
