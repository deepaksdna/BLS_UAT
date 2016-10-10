<?php
namespace App\Test\TestCase\Controller;

use App\Controller\CartProductsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\CartProductsController Test Case
 */
class CartProductsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.cart_products',
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
        'app.cart_shippings',
        'app.prods'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
