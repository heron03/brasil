<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Presencas Model
 *
 * @property \App\Model\Table\SessoesTable&\Cake\ORM\Association\BelongsTo $Sessoes
 * @property \App\Model\Table\IrmaosTable&\Cake\ORM\Association\BelongsTo $Irmaos
 *
 * @method \App\Model\Entity\Presenca newEmptyEntity()
 * @method \App\Model\Entity\Presenca newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Presenca[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Presenca get($primaryKey, $options = [])
 * @method \App\Model\Entity\Presenca findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Presenca patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Presenca[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Presenca|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Presenca saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Presenca[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Presenca[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Presenca[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Presenca[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PresencasTable extends Table
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

        $this->setTable('presencas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Sessoes', [
            'foreignKey' => 'sessao_id',
            'joinType' => 'INNER',
        ]);
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
            ->integer('sessao_id')
            ->notEmptyString('sessao_id');

        $validator
            ->integer('irmao_id')
            ->notEmptyString('irmao_id');

        $validator
            ->boolean('presente')
            ->allowEmptyString('presente');

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
        $rules->add($rules->existsIn('sessao_id', 'Sessoes'), ['errorField' => 'sessao_id']);
        $rules->add($rules->existsIn('irmao_id', 'Irmaos'), ['errorField' => 'irmao_id']);

        return $rules;
    }
}
