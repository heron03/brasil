<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mensalidade Entity
 *
 * @property int $id
 * @property int $irmao_id
 * @property \Cake\I18n\FrozenDate $mes_referencia
 * @property string $valor
 * @property string|null $mutua
 * @property string|null $capitacao
 * @property string|null $diversos
 * @property string|null $reserva
 * @property string $valor_pago
 * @property bool|null $pago
 * @property string|null $forma_pagamento
 * @property \Cake\I18n\FrozenDate|null $data_pagamento
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Irmao $irmao
 */
class Mensalidade extends Entity
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
        'irmao_id' => true,
        'mes_referencia' => true,
        'valor' => true,
        'mutua' => true,
        'capitacao' => true,
        'diversos' => true,
        'reserva' => true,
        'valor_pago' => true,
        'pago' => true,
        'forma_pagamento' => true,
        'data_pagamento' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'irmao' => true,
    ];
}
