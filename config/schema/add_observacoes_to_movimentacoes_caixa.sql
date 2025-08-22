-- Adicionar campo observacoes na tabela movimentacoes_caixa
-- Executar este script para adicionar a nova coluna

ALTER TABLE `movimentacoes_caixa`
ADD COLUMN `observacoes` text DEFAULT NULL AFTER `forma_pagamento`;
