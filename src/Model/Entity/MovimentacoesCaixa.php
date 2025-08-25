<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;

/**
 * MovimentacoesCaixa Entity
 *
 * @property int $id
 * @property int $loja_id
 * @property int|null $irmao_id
 * @property string|null $tipo
 * @property string|null $descricao
 * @property string $valor
 * @property string|null $origem
 * @property string|null $forma_pagamento
 * @property string|null $observacoes
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Loja $loja
 * @property \App\Model\Entity\Irmao $irmao
 */
class MovimentacoesCaixa extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'loja_id' => true,
        'irmao_id' => true,
        'tipo' => true,
        'descricao' => true,
        'valor' => true,
        'data_movimentacao' => true,
        'origem' => true,
        'forma_pagamento' => true,
        'observacoes' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'loja' => true,
        'irmao' => true,
    ];

}
