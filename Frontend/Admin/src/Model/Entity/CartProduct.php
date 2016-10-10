<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CartProduct Entity
 *
 * @property int $id
 * @property int $cart_id
 * @property int $prod_id
 * @property int $prod_quantity
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Cart $cart
 * @property \App\Model\Entity\Prod $prod
 */
class CartProduct extends Entity
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
