<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserBillings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserBilling get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserBilling newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserBilling[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserBilling|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserBilling patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserBilling[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserBilling findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserBillingsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('user_billings');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('receivername_name', 'create')
            ->notEmpty('receivername_name');

        $validator
            ->requirePresence('telephone_no', 'create')
            ->notEmpty('telephone_no');

        $validator
            ->requirePresence('fax_no', 'create')
            ->notEmpty('fax_no');

        $validator
            ->requirePresence('email_address', 'create')
            ->notEmpty('email_address');

        $validator
            ->requirePresence('block_no', 'create')
            ->notEmpty('block_no');

        $validator
            ->requirePresence('unit_no', 'create')
            ->notEmpty('unit_no');

        $validator
            ->requirePresence('street', 'create')
            ->notEmpty('street');

        $validator
            ->requirePresence('postalcode', 'create')
            ->notEmpty('postalcode');

        $validator
            ->integer('telephone')
            ->requirePresence('telephone', 'create')
            ->notEmpty('telephone');

        $validator
            ->requirePresence('country', 'create')
            ->notEmpty('country');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }
}
