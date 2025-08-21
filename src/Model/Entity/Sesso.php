<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Sesso Entity
 *
 * @property int $id
 * @property int $loja_id
 * @property \Cake\I18n\FrozenDate $data_sessao
 * @property string|null $tipo
 * @property string|null $pauta
 * @property string|null $observacoes
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Loja $loja
 */
class Sesso extends Entity
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
        'data_sessao' => true,
        'tipo' => true,
        'pauta' => true,
        'observacoes' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'loja' => true,
    ];
}
