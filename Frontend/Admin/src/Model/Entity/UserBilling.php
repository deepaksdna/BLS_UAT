<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserBilling Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $receivername_name
 * @property string $telephone_no
 * @property string $fax_no
 * @property string $email_address
 * @property string $block_no
 * @property string $unit_no
 * @property string $street
 * @property string $postalcode
 * @property int $telephone
 * @property string $country
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 */
class UserBilling extends Entity
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
