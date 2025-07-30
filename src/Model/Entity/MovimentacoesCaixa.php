<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MovimentacoesCaixa Entity
 *
 * @property int $id
 * @property int $loja_id
 * @property string|null $tipo
 * @property string|null $descricao
 * @property string $valor
 * @property \Cake\I18n\FrozenDate $data_movimentacao
 * @property string|null $origem
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Loja $loja
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
        'tipo' => true,
        'descricao' => true,
        'valor' => true,
        'data_movimentacao' => true,
        'origem' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'loja' => true,
    ];
}
