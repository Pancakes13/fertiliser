<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Tests the index.
     * Loads all transactions and items in stock
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    
    /**
     * Test transactions
     * Loads all transactions
     *
     * @return void
     */
    public function test_transactions()
    {
        // Fix document root on tests;
        $_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__).'../../';
        $response = $this->get('/transactions');

        $response->assertStatus(200);
    }

    /**
     * Test applying a quantity
     * Loads all transactions and items in stock
     * Applies quantity passed
     *
     * @return void
     */
    public function test_apply_quantity()
    {
        // Fix document root on tests;
        $_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__).'../../';
        $response = $this->get('/23');

        $response->assertStatus(200);
    }

    /**
     * Test applying a quantity with invalid value 0
     *
     * @return void
     */
    public function test_apply_quantity_zero()
    {
        // Fix document root on tests;
        $_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__).'../../';
        $response = $this->get('/0');

        $response->assertStatus(500);
    }

    /**
     * Test applying a quantity with invalid value letters
     *
     * @return void
     */
    public function test_apply_quantity_invalid()
    {
        // Fix document root on tests;
        $_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__).'../../';
        $response = $this->get('/ea');

        $response->assertStatus(500);
    }
}
