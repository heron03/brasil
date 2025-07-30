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
class MensalidadesTable extends Table
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
            ->boolean('pago')
            ->allowEmptyString('pago');

        $validator
            ->date('data_pagamento')
            ->allowEmptyDate('data_pagamento');

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
}
