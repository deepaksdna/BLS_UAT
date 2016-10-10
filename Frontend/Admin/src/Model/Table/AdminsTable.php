<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Imagine\Image\Box;
/**
 * Admins Model
 *
 * @method \App\Model\Entity\Admin get($primaryKey, $options = [])
 * @method \App\Model\Entity\Admin newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Admin[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Admin|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Admin patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Admin[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Admin findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AdminsTable extends Table
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

        $this->table('admins');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
		$this->addBehavior('Josegonzalez/Upload.Upload', [
            'image' => [
                'fields' => [
                    // if these fields or their defaults exist
                    // the values will be set.
                    'dir' => 'image_dir', // defaults to `dir`
                    'size' => 'image_size', // defaults to `size`
                    'type' => 'image_type', // defaults to `type`
                ],
				'path' => 'webroot{DS}img{DS}files{DS}{model}{DS}{field}{DS}',
				'transformer' => function (\Cake\Datasource\RepositoryInterface $table, \Cake\Datasource\EntityInterface $entity, $data, $field, $settings) 
					{
						
						$extension = pathinfo($data['name'], PATHINFO_EXTENSION);		
						$tmp = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;
						//$size = new \Imagine\Image\Box(100,100px);
						//$mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
						$imagine = new \Imagine\Gd\Imagine();
						$imagine->open($data['tmp_name'])
						->resize(new Box(60, 60))
						//->thumbnail($size, $mode)
						->save($tmp);
						
						return [		
						$tmp => $data['name']
					
						];
					},
				
                   
            ],
        ]);
		$this->addBehavior('Acl.Acl', ['type' => 'requester']);
		
		 $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
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
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

		$validator
			->allowEmpty('image');
			
		$validator
			->allowEmpty('group_id');	

		$validator
			->allowEmpty('image_dir');

        $validator
            ->allowEmpty('dob');

        $validator
            ->requirePresence('mobile', 'create')
            ->notEmpty('mobile')
			->integer('mobile')
			->add('mobile', [
					'minLength' => [
						'rule' => ['minLength', 10],
						'last' => true,
						'message' => 'Minimum 10 Numbers required'
					],
					'maxLength' => [
						'rule' => ['maxLength', 15],
						'message' => 'Mobile number contains maximum 15 numbers.'
					],
					'unique' => [
						'rule' => 'validateUnique',
						'provider' => 'table',
						'message' => 'Mobile Already exist choose other mobile'
					]
			]);

        $validator        
            ->allowEmpty('firstname');

        $validator           
            ->allowEmpty('lastname');

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
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
	
	
}
