<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductsImagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductsImagesTable Test Case
 */
class ProductsImagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductsImagesTable
     */
    public $ProductsImages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.products_images',
        'app.products',
        'app.products_attrs',
        'app.brands',
        'app.brand_categories',
        'app.prod_brands',
        'app.products_marketing_images',
        'app.products_prices',
        'app.products_promos',
        'app.promo1_discount_a_products',
        'app.promo1_discount_b_products',
        'app.promo1_discount_c_products',
        'app.promo1_discount_d_products',
        'app.promo1_discount_e_products',
        'app.promo1_foc_products',
        'app.promo2_discount_a_products',
        'app.promo2_discount_b_products',
        'app.promo2_discount_c_products',
        'app.promo2_discount_d_products',
        'app.promo2_discount_e_products',
        'app.promo2_foc_products',
        'app.promo3_discount_a_products',
        'app.promo3_discount_b_products',
        'app.promo3_discount_c_products',
        'app.promo3_discount_d_products',
        'app.promo3_discount_e_products',
        'app.promo3_foc_products',
        'app.products_relateds',
        'app.orders',
        'app.users',
        'app.carts',
        'app.user_addresses',
        'app.user_details',
        'app.order_update_statuses',
        'app.order_statuses',
        'app.admins',
        'app.orders_products',
        'app.orders_shippings',
        'app.categories',
        'app.category_details',
        'app.products_categories',
        'app.colors'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ProductsImages') ? [] : ['className' => 'App\Model\Table\ProductsImagesTable'];
        $this->ProductsImages = TableRegistry::get('ProductsImages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductsImages);

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
