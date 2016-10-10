<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Imagine\Image\Box;
/**
 * SliderImages Model
 *
 * @method \App\Model\Entity\SliderImage get($primaryKey, $options = [])
 * @method \App\Model\Entity\SliderImage newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SliderImage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SliderImage|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SliderImage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SliderImage[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SliderImage findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SliderImagesTable extends Table
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

        $this->table('slider_images');
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
						->resize(new Box(1920, 500))
						//->thumbnail($size, $mode)
						->save($tmp);
						return [		
						$tmp => $data['name'],
						];
					},
				
                   
            ],
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
}
