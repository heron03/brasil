<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MovimentacoesCaixa Model
 *
 * @property \App\Model\Table\LojasTable&\Cake\ORM\Association\BelongsTo $Lojas
 * @property \App\Model\Table\IrmaosTable&\Cake\ORM\Association\BelongsTo $Irmaos
 *
 * @method \App\Model\Entity\MovimentacoesCaixa newEmptyEntity()
 * @method \App\Model\Entity\MovimentacoesCaixa newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MovimentacoesCaixa[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MovimentacoesCaixa get($primaryKey, $options = [])
 * @method \App\Model\Entity\MovimentacoesCaixa findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MovimentacoesCaixa patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MovimentacoesCaixa[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MovimentacoesCaixa|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MovimentacoesCaixa saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MovimentacoesCaixa[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MovimentacoesCaixa[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MovimentacoesCaixa[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MovimentacoesCaixa[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MovimentacoesCaixaTable extends AppTable
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

        $this->setTable('movimentacoes_caixa');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Lojas', [
            'foreignKey' => 'loja_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Irmaos', [
            'foreignKey' => 'irmao_id',
            'joinType' => 'LEFT',
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
            ->integer('loja_id')
            ->notEmptyString('loja_id');

        $validator
            ->integer('irmao_id')
            ->allowEmptyString('irmao_id');

        $validator
            ->scalar('tipo')
            ->maxLength('tipo', 50)
            ->allowEmptyString('tipo');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->notEmptyString('valor');

        $validator
            ->notBlank('data_movimentacao', __('Informe a Data'))
            ->add('data_movimentacao', 'date', [
                'rule' => ['date', 'dmy'],
                'message' => __('Data inválida'),
                'last' => true,
            ])
            ->add('data_movimentacao', 'dataMenorQueDataAtual', [
                'rule' => 'dataMenorQueDataAtual',
                'message' => __('Data Menor que atual '),
                'provider' => 'table',
            ]);

        $validator
            ->scalar('origem')
            ->maxLength('origem', 50)
            ->allowEmptyString('origem');

        $validator
            ->scalar('forma_pagamento')
            ->maxLength('forma_pagamento', 20)
            ->allowEmptyString('forma_pagamento');

        $validator
            ->scalar('observacoes')
            ->allowEmptyString('observacoes');

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
        $rules->add($rules->existsIn('loja_id', 'Lojas'), ['errorField' => 'loja_id']);
        $rules->add($rules->existsIn('irmao_id', 'Irmaos'), ['errorField' => 'irmao_id']);

        return $rules;
    }

    public function beforeSave(\Cake\Event\EventInterface $event, \Cake\Datasource\EntityInterface $entity, \ArrayObject $options): void
    {
        $valor = (string)$entity->get('valor');
        $valor = preg_replace('/[^0-9,.\-]/', '', $valor) ?? '';

        if (strpos($valor, ',') !== false) {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        }

        $entity->set('valor', (float)$valor);
    }
}
