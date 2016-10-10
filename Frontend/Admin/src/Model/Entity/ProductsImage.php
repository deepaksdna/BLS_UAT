<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductsImage Entity
 *
 * @property int $id
 * @property int $product_id
 * @property string $product_code
 * @property string $image
 * @property string $image_dir
 * @property int $color_id
 * @property int $status
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Color $color
 */
class ProductsImage extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
