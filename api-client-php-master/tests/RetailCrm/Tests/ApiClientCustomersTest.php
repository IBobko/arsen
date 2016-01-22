<?php

namespace RetailCrm\Tests;

use RetailCrm\Test\TestCase;

class ApiClientCustomersTest extends TestCase
{
    const FIRST_NAME = 'Иннокентий';

    /**
     * @group integration
     */
    public function testCustomersCreate()
    {
        $client = static::getApiClient();

        $externalId = 'c-create-' . time();

        $response = $client->customersCreate(array(
            'firstName' => self::FIRST_NAME,
            'externalId' => $externalId,
        ));
        $this->assertInstanceOf('RetailCrm\Response\ApiResponse', $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue(is_int($response->getId()));

        return array(
            'id' => $response->getId(),
            'externalId' => $externalId,
        );
    }

    /**
     * @group unit
     * @expectedException \InvalidArgumentException
     */
    public function testCustomersCreateExceptionEmpty()
    {
        $client = static::getApiClient();

        $response = $client->customersCreate(array());
    }

    /**
     * @group integration
     * @depends testCustomersCreate
     */
    public function testCustomersGet(array $ids)
    {
        $client = static::getApiClient();

        $response = $client->customersGet(678678678);
        $this->assertInstanceOf('RetailCrm\Response\ApiResponse', $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertFalse($response->success);

        $response = $client->customersGet($ids['id'], 'id');
        $customerById = $response->customer;
        $this->assertInstanceOf('RetailCrm\Response\ApiResponse', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->success);
        $this->assertEquals(self::FIRST_NAME, $response->customer['firstName']);

        $response = $client->customersGet($ids['externalId'], 'externalId');
        $this->assertEquals($customerById['id'], $response->customer['id']);

        return $ids;
    }

    /**
     * @group unit
     * @expectedException \InvalidArgumentException
     */
    public function testCustomersGetException()
    {
        $client = static::getApiClient();

        $response = $client->customersGet(678678678, 'asdf');
    }

    /**
     * @group integration
     * @depends testCustomersGet
     */
    public function testCustomersEdit(array $ids)
    {
        $client = static::getApiClient();

        $response = $client->customersEdit(
            array(
                'id' => 22342134,
                'lastName' => '12345',
            ),
            'id'
        );
        $this->assertInstanceOf('RetailCrm\Response\ApiResponse', $response);
        $this->assertEquals(404, $response->getStatusCode());

        $response = $client->customersEdit(array(
            'externalId' => $ids['externalId'],
            'lastName' => '12345',
        ));
        $this->assertInstanceOf('RetailCrm\Response\ApiResponse', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->success);

        $response = $client->customersEdit(array(
            'externalId' => 'c-edit-' . time(),
            'lastName' => '12345',
        ));
        $this->assertInstanceOf('RetailCrm\Response\ApiResponse', $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->success);
    }

    /**
     * @group unit
     * @expectedException \InvalidArgumentException
     */
    public function testCustomersEditExceptionEmpty()
    {
        $client = static::getApiClient();

        $response = $client->customersEdit(array(), 'asdf');
    }

    /**
     * @group unit
     * @expectedException \InvalidArgumentException
     */
    public function testCustomersEditException()
    {
        $client = static::getApiClient();

        $response = $client->customersEdit(array('id' => 678678678), 'asdf');
    }

    /**
     * @group integration
     */
    public function testCustomersList()
    {
        $client = static::getApiClient();

        $response = $client->customersList();
        $this->assertInstanceOf('RetailCrm\Response\ApiResponse', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue(isset($response['customers']));

        $response = $client->customersList(array(), 1, 300);
        $this->assertInstanceOf('RetailCrm\Response\ApiResponse', $response);
        $this->assertFalse(
            $response->isSuccessful(),
            'Pagination error'
        );

        $response = $client->customersList(array('maxOrdersCount' => 10), 1);
        $this->assertInstanceOf('RetailCrm\Response\ApiResponse', $response);
        $this->assertTrue(
            $response->isSuccessful(),
            'API returns customers list'
        );
    }

    /**
     * @group unit
     * @expectedException \InvalidArgumentException
     */
    public function testCustomersFixExternalIdsException()
    {
        $client = static::getApiClient();

        $response = $client->customersFixExternalIds(array());
    }

    /**
     * @group integration
     */
    public function testCustomersFixExternalIds()
    {
        $client = static::getApiClient();

        $response = $client->ordersCreate(array(
            'firstName' => 'Aaa111',
        ));
        $this->assertTrue(
            $response->isSuccessful(),
            'Order created'
        );

        $response = $client->ordersGet($response->id, 'id');
        $this->assertTrue(
            $response->isSuccessful(),
            'Order fetched'
        );

        $id = $response->order['customer']['id'];
        $externalId = 'asdf' . time();

        $response = $client->customersFixExternalIds(array(
            array('id' => $id, 'externalId' => $externalId)
        ));

        $this->assertTrue(
            $response->isSuccessful(),
            'Fixed customer ids'
        );

        $response = $client->customersGet($externalId);
        $this->assertTrue(
            $response->isSuccessful(),
            'Got customer'
        );
        $this->assertEquals(
            $id,
            $response->customer['id'],
            'Fixing of customer ids were right'
        );
        $this->assertEquals(
            $externalId,
            $response->customer['externalId'],
            'Fixing of customer ids were right'
        );
    }

    /**
     * @group unit
     * @expectedException \InvalidArgumentException
     */
    public function testCustomersUploadExceptionEmpty()
    {
        $client = static::getApiClient();

        $response = $client->customersUpload(array());
    }

    /**
     * @group integration
     */
    public function testCustomersUpload()
    {
        $client = static::getApiClient();

        $externalIdA = 'upload-a-' . time();
        $externalIdB = 'upload-b-' . time();

        $response = $client->customersUpload(array(
            array(
                'externalId' => $externalIdA,
                'firstName' => 'Aaa',
            ),
            array(
                'externalId' => $externalIdB,
                'lastName' => 'Bbb',
            ),
        ));
        $this->assertTrue(
            $response->isSuccessful(),
            'Got customer'
        );
        $this->assertEquals(
            $externalIdA,
            $response->uploadedCustomers[0]['externalId']
        );
        $this->assertEquals(
            $externalIdB,
            $response->uploadedCustomers[1]['externalId']
        );
    }
}
