<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Irmaos Model
 *
 * @property \App\Model\Table\LojasTable&\Cake\ORM\Association\BelongsTo $Lojas
 * @property \App\Model\Table\MensalidadesTable&\Cake\ORM\Association\HasMany $Mensalidades
 * @property \App\Model\Table\PresencasTable&\Cake\ORM\Association\HasMany $Presencas
 * @property \App\Model\Table\MovimentacoesCaixaTable&\Cake\ORM\Association\HasMany $MovimentacoesCaixa
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
class IrmaosTable extends AppTable
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
        $this->hasMany('MovimentacoesCaixa', [
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

        $validator
            ->scalar('nome')
            ->notBlank('nome', __('Informe um Nome'))
            ->minLength('nome', 3, __('Informe um Nome com mais de 3 caracteres'))
            ->maxLength('nome', 255, __('Informe um Nome com menos de 255 caracteres'));

        $validator
            ->scalar('cpf')
            ->add('cpf', 'validCpf', [
                'rule' => 'validCpf',
                'message' => __('CPF inválido '),
                'provider' => 'table',
            ])
            ->notBlank('cpf', __('Informe um CPF'));

        $validator
            ->scalar('cim')
            ->notBlank('cim', __('Informe um CIM'))
            ->maxLength('cim', 10, __('Informe um CIM com menos de 10 caracteres'));


        $validator
            ->notBlank('data_nascimento', __('Informe a Data de Nascimento'))
            ->add('data_nascimento', 'date', [
                'rule' => ['date', 'dmy'],
                'message' => __('Data inválida'),
                'last' => true,
            ])
            ->add('data_nascimento', 'dataMenorQueDataAtual', [
                'rule' => 'dataMenorQueDataAtual',
                'message' => __('Data Menor que atual '),
                'provider' => 'table',
            ]);

        $validator
            ->scalar('cep')
            ->maxLength('cep', 20)
            ->add('cep', 'checkCep', [
                'rule' => 'checkCep',
                'message' => __('CEP inválido '),
                'provider' => 'table',
            ]);

        $validator
            ->scalar('logradouro')
            ->minLength('logradouro', 3, __('Informe um Endereço com mais de 3 caracteres'))
            ->notBlank('logradouro', __('Informe um Endereço'))
            ->maxLength('logradouro', 255, __('Informe um Endereço com menos de 255 caracteres'));

        $validator
            ->scalar('numero')
            ->maxLength('numero', 10)
            ->allowEmptyString('numero');

        $validator
            ->scalar('bairro')
            ->maxLength('bairro', 100)
            ->allowEmptyString('bairro');

        $validator
            ->scalar('complemento')
            ->maxLength('complemento', 100)
            ->allowEmptyString('complemento');

        $validator
            ->email('email', false, __('Informe um E-mail válido'))
            ->notBlank('email', __('Informe um E-mail'));

        $validator
            ->notBlank('ativo', __('Informe um Status'));

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
