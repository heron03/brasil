-- Migração para adicionar campo observacoes na tabela movimentacoes_caixa
USE brasil;

-- Verificar se o campo já existe antes de adicionar
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
     WHERE TABLE_SCHEMA = 'brasil'
     AND TABLE_NAME = 'movimentacoes_caixa'
     AND COLUMN_NAME = 'observacoes') = 0,
    'ALTER TABLE movimentacoes_caixa ADD COLUMN observacoes text DEFAULT NULL AFTER forma_pagamento;',
    'SELECT "Campo observacoes já existe na tabela movimentacoes_caixa" as message;'
));

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
