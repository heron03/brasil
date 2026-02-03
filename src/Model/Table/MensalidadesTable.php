<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mensalidades Model
 *
 * @property \App\Model\Table\IrmaosTable&\Cake\ORM\Association\BelongsTo $Irmaos
 *
 * @method \App\Model\Entity\Mensalidade newEmptyEntity()
 * @method \App\Model\Entity\Mensalidade newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Mensalidade[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Mensalidade get($primaryKey, $options = [])
 * @method \App\Model\Entity\Mensalidade findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Mensalidade patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Mensalidade[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Mensalidade|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Mensalidade saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Mensalidade[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Mensalidade[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Mensalidade[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Mensalidade[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MensalidadesTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('mensalidades');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Irmaos', [
            'foreignKey' => 'irmao_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('irmao_id')
            ->notEmptyString('irmao_id');

        $validator
            ->date('mes_referencia')
            ->requirePresence('mes_referencia', 'create')
            ->notEmptyDate('mes_referencia');

        $validator
            ->decimal('valor')
            ->requirePresence('valor', 'create')
            ->notEmptyString('valor');

        $validator
            ->decimal('valor_pago')
            ->allowEmptyString('valor_pago');

        $validator
            ->boolean('pago')
            ->allowEmptyString('pago');

        $validator
            ->notBlank('data_pagamento', __('Informe a Data'))
            ->add('data_pagamento', 'date', [
                'rule' => ['date', 'dmy'],
                'message' => __('Data inválida'),
                'last' => true,
            ])
            ->add('data_pagamento', 'dataMenorQueDataAtual', [
                'rule' => 'dataMenorQueDataAtual',
                'message' => __('Data Menor que atual '),
                'provider' => 'table',
            ]);

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('irmao_id', 'Irmaos'), ['errorField' => 'irmao_id']);

        return $rules;
    }

    public function findMensalidadesPorAnual($params)
    {
        $query = $this->find()
            ->select([
                'Mensalidades.id',
                'Mensalidades.irmao_id',
                'Mensalidades.mes_referencia',
                'Mensalidades.valor',
                'Mensalidades.valor_pago',
                'Mensalidades.pago',
                'Mensalidades.data_pagamento',
            ])
            ->where($params) // ex.: ['Mensalidades.ano' => 2025, 'Mensalidades.irmao_id' => 12]
            ->contain([
                'Irmaos' => [
                    'fields' => ['Irmaos.id', 'Irmaos.nome'],
                ],
            ]);

        // Retorna objetos de entidade:
        return $query->all(); // ResultSetInterface
        // Se preferir array puro:
        // return $query->disableHydration()->toArray();
    }

    public function beforeSave(
        \Cake\Event\EventInterface $event,
        \Cake\Datasource\EntityInterface $entity,
        \ArrayObject $options
    ): void {
        if ($entity->has('valor')) {
            $valor = (string)$entity->get('valor');
            $valor = preg_replace('/[^0-9,.\-]/', '', $valor) ?? '';

            if (strpos($valor, ',') !== false) {
                $valor = str_replace('.', '', $valor);
                $valor = str_replace(',', '.', $valor);
            }

            $entity->set('valor', (float)$valor);
        }

        if ($entity->has('valor_pago')) {
            $valorPago = (string)$entity->get('valor_pago');
            $valorPago = preg_replace('/[^0-9,.\-]/', '', $valorPago) ?? '';

            if (strpos($valorPago, ',') !== false) {
                $valorPago = str_replace('.', '', $valorPago);
                $valorPago = str_replace(',', '.', $valorPago);
            }

            $entity->set('valor_pago', (float)$valorPago);
        }
    }
}
