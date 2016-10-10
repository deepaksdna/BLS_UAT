<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CartsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CartsTable Test Case
 */
class CartsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CartsTable
     */
    public $Carts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.carts',
        'app.users',
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
        'app.orders_shippings',
        'app.user_details',
        'app.cart_products',
        'app.cart_shippings'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Carts') ? [] : ['className' => 'App\Model\Table\CartsTable'];
        $this->Carts = TableRegistry::get('Carts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Carts);

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
