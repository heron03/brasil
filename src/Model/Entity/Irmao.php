<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Irmao Entity
 *
 * @property int $id
 * @property int $loja_id
 * @property string $nome
 * @property \Cake\I18n\FrozenDate|null $data_nascimento
 * @property string|null $grau
 * @property string|null $logradouro
 * @property string|null $numero
 * @property string|null $complemento
 * @property string|null $bairro
 * @property string|null $cidade
 * @property string|null $estado
 * @property string|null $cep
 * @property string|null $telefone
 * @property string|null $email
 * @property bool|null $ativo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Loja $loja
 * @property \App\Model\Entity\Mensalidade[] $mensalidades
 * @property \App\Model\Entity\Presenca[] $presencas
 */
class Irmao extends Entity
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
        'nome' => true,
        'data_nascimento' => true,
        'grau' => true,
        'logradouro' => true,
        'numero' => true,
        'complemento' => true,
        'bairro' => true,
        'cidade' => true,
        'estado' => true,
        'cep' => true,
        'telefone' => true,
        'email' => true,
        'ativo' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'loja' => true,
        'mensalidades' => true,
        'presencas' => true,
    ];
}
