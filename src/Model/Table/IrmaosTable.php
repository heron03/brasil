<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Irmaos Model
 *
 * @property \App\Model\Table\LojasTable&\Cake\ORM\Association\BelongsTo $Lojas
 * @property \App\Model\Table\MensalidadesTable&\Cake\ORM\Association\HasMany $Mensalidades
 * @property \App\Model\Table\PresencasTable&\Cake\ORM\Association\HasMany $Presencas
 *
 * @method \App\Model\Entity\Irmao newEmptyEntity()
 * @method \App\Model\Entity\Irmao newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Irmao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Irmao get($primaryKey, $options = [])
 * @method \App\Model\Entity\Irmao findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Irmao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Irmao[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Irmao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Irmao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Irmao[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Irmao[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Irmao[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Irmao[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class IrmaosTable extends Table
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

        $this->setTable('irmaos');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Lojas', [
            'foreignKey' => 'loja_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Mensalidades', [
            'foreignKey' => 'irmao_id',
        ]);
        $this->hasMany('Presencas', [
            'foreignKey' => 'irmao_id',
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
        // $validator
        //     ->integer('loja_id')
        //     ->notEmptyString('loja_id');

        // $validator
        //     ->scalar('nome')
        //     ->maxLength('nome', 255)
        //     ->requirePresence('nome', 'create')
        //     ->notEmptyString('nome');

        // $validator
        //     ->date('data_nascimento')
        //     ->allowEmptyDate('data_nascimento');

        // $validator
        //     ->scalar('grau')
        //     ->maxLength('grau', 50)
        //     ->allowEmptyString('grau');

        // $validator
        //     ->scalar('logradouro')
        //     ->maxLength('logradouro', 255)
        //     ->allowEmptyString('logradouro');

        // $validator
        //     ->scalar('numero')
        //     ->maxLength('numero', 10)
        //     ->allowEmptyString('numero');

        // $validator
        //     ->scalar('complemento')
        //     ->maxLength('complemento', 100)
        //     ->allowEmptyString('complemento');

        // $validator
        //     ->scalar('bairro')
        //     ->maxLength('bairro', 100)
        //     ->allowEmptyString('bairro');

        // $validator
        //     ->scalar('cidade')
        //     ->maxLength('cidade', 100)
        //     ->allowEmptyString('cidade');

        // $validator
        //     ->scalar('estado')
        //     ->maxLength('estado', 2)
        //     ->allowEmptyString('estado');

        // $validator
        //     ->scalar('cep')
        //     ->maxLength('cep', 10)
        //     ->allowEmptyString('cep');

        // $validator
        //     ->scalar('telefone')
        //     ->maxLength('telefone', 20)
        //     ->allowEmptyString('telefone');

        // $validator
        //     ->email('email')
        //     ->allowEmptyString('email');

        // $validator
        //     ->boolean('ativo')
        //     ->allowEmptyString('ativo');

        // $validator
        //     ->dateTime('deleted')
        //     ->allowEmptyDateTime('deleted');

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
        // $rules->add($rules->existsIn('loja_id', 'Lojas'), ['errorField' => 'loja_id']);

        return $rules;
    }

    public function selectOptions()
    {
        $options = $this->find('list', [
            'keyField' => 'id',
            'valueField' => 'nome',
        ])
        ->order(['nome' => 'ASC'])
        ->toArray();

        return $options;
    }
}
