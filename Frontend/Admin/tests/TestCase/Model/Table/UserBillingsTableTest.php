<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserBillingsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserBillingsTable Test Case
 */
class UserBillingsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserBillingsTable
     */
    public $UserBillings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_billings',
        'app.users',
        'app.carts',
        'app.cart_products',
        'app.products',
        'app.products_attrs',
        'app.brands',
        'app.brand_categories',
        'app.prod_brands',
        'app.products_images',
        'app.colors',
        'app.products_marketing_images',
        'app.promotions',
        'app.child_products',
        'app.products_prices',
        'app.products_relateds',
        'app.relatedproduct1',
        'app.categories',
        'app.category_details',
        'app.products_categories',
        'app.relatedproduct2',
        'app.relatedproduct3',
        'app.cart_shippings',
        'app.orders',
        'app.user_addresses',
        'app.order_update_statuses',
        'app.order_statuses',
        'app.admins',
        'app.aros',
        'app.acos',
        'app.permissions',
        'app.groups',
        'app.orders_products',
        'app.order_product_promotions',
        'app.orders_shippings',
        'app.orders_billings',
        'app.user_details'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UserBillings') ? [] : ['className' => 'App\Model\Table\UserBillingsTable'];
        $this->UserBillings = TableRegistry::get('UserBillings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserBillings);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
