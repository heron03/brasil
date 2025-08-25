-- Adicionar campos irmao_id e forma_pagamento na tabela movimentacoes_caixa
-- Executar este script para adicionar as novas colunas

ALTER TABLE `movimentacoes_caixa`
ADD COLUMN `irmao_id` int(11) DEFAULT NULL AFTER `loja_id`;

ALTER TABLE `movimentacoes_caixa`
ADD COLUMN `forma_pagamento` varchar(20) DEFAULT NULL AFTER `origem`;

-- Adicionar índice para melhor performance
ALTER TABLE `movimentacoes_caixa`
ADD INDEX `idx_movimentacoes_caixa_irmao_id` (`irmao_id`);

-- Adicionar foreign key constraint
ALTER TABLE `movimentacoes_caixa`
ADD CONSTRAINT `movimentacoes_caixa_ibfk_2`
FOREIGN KEY (`irmao_id`) REFERENCES `irmaos` (`id`)
ON DELETE SET NULL ON UPDATE CASCADE;
