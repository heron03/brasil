<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Presenca Entity
 *
 * @property int $id
 * @property int $sessao_id
 * @property int $irmao_id
 * @property bool|null $presente
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Sesso $sesso
 * @property \App\Model\Entity\Irmao $irmao
 */
class Presenca extends Entity
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
        'sessao_id' => true,
        'irmao_id' => true,
        'presente' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'sesso' => true,
        'irmao' => true,
    ];
}
