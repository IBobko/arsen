<?php

namespace RetailCrm\Tests;

use RetailCrm\Test\TestCase;

class ApiClientReferenceTest extends TestCase
{
    /**
     * @group integration
     * @dataProvider getListDictionaries
     * @param $name
     */
    public function testList($name)
    {
        $client = static::getApiClient();

        $method = $name . 'List';
        $response = $client->$method();

        $this->assertInstanceOf('RetailCrm\Response\ApiResponse', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue(isset($response[$name]));
        $this->assertTrue(is_array($response[$name]));
    }

    /**
     * @group integration
     * @dataProvider getEditDictionaries
     * @expectedException \InvalidArgumentException
     */
    public function testEditingException($name)
    {
        $client = static::getApiClient();

        $method = $name . 'Edit';
        $response = $client->$method(array());
    }

    /**
     * @group integration
     * @dataProvider getEditDictionaries
     */
    public function testEditing($name)
    {
        $client = static::getApiClient();

        $code = 'dict-' . strtolower($name) . '-' . time();
        $method = $name . 'Edit';
        $params = array(
            'code' => $code,
            'name' => 'Aaa',
        );
        if ($name == 'statuses') {
            $params['group'] = 'new';
        }

        $response = $client->$method($params);
        $this->assertEquals(201, $response->getStatusCode());

        $response = $client->$method(array(
            'code' => $code,
            'name' => 'Bbb',
        ));
        if ($response->getStatusCode() > 201) {
            print_r($response);
        }
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @group integration
     * @group site
     */
    public function testSiteEditing()
    {
        $name = 'sites';
        $client = static::getApiClient();

        $code = 'dict-' . strtolower($name) . '-' . time();
        $method = $name . 'Edit';
        $params = array(
            'code' => $code,
            'name' => 'Aaa',
        );

        $response = $client->$method($params);
        $this->assertEquals(400, $response->getStatusCode());

        if ($code = $client->getSite()) {
            $method = $name . 'Edit';
            $params = array(
                'code' => $code,
                'name' => 'Aaa' . time(),
            );

            $response = $client->$method($params);
            $this->assertEquals(200, $response->getStatusCode());
        }
    }

    public function getListDictionaries()
    {
        return array(
            array('deliveryServices'),
            array('deliveryTypes'),
            array('orderMethods'),
            array('orderTypes'),
            array('paymentStatuses'),
            array('paymentTypes'),
            array('productStatuses'),
            array('statusGroups'),
            array('statuses'),
            array('sites'),
        );
    }

    public function getEditDictionaries()
    {
        return array(
            array('deliveryServices'),
            array('deliveryTypes'),
            array('orderMethods'),
            array('orderTypes'),
            array('paymentStatuses'),
            array('paymentTypes'),
            array('productStatuses'),
            array('statuses'),
        );
    }
}
