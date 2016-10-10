<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Imagine\Image\Box;
/**
 * CategoryDetails Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Categories
 *
 * @method \App\Model\Entity\CategoryDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\CategoryDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CategoryDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CategoryDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CategoryDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CategoryDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CategoryDetail findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CategoryDetailsTable extends Table
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

        $this->table('category_details');
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
						$tmp2 = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;
						//$size = new \Imagine\Image\Box(100,100px);
						//$mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
						$imagine = new \Imagine\Gd\Imagine();
						
						$imagine->open($data['tmp_name'])
						->resize(new Box(630, 300))
						//->resize(new Box(550, 265))
						//->thumbnail($size, $mode)
						->save($tmp);
						
						$imagine->open($data['tmp_name'])
						->resize(new Box(1920, 300))
						//->thumbnail($size, $mode)
						->save($tmp2);
						
						return [		
						$tmp => $data['name'],
						$tmp2 => 'banner_'.$data['name'],
						];
					},
                   
            ],
        ]);

        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id',
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
            ->allowEmpty('image');

        $validator
            ->allowEmpty('image_dir');
			

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
       /*  $rules->add($rules->existsIn(['category_id'], 'Categories')); */
        return $rules;
    }
}
