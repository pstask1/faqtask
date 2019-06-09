<?php

$ajax = true;

/**
 * Class InixUpdateClientTest
 */
class InixUpdateClientTest extends PrestaShopPHPUnit
{
    /**
     * @var string
     */
    public $api_url = 'http://the.service/';
    /**
     * @var string
     */
    public $api_uri = 'api/';
    /** @var  Inixframe */
    public $inixframe;
    /** @var  InixUpdateClient */
    public $client;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        putenv('UPDATE_SERVICE_URL=' . $this->api_url);
        // init the autoloaders
        $this->inixframe = Module::getInstanceByName('inixframe');
        $this->client    = new InixUpdateClient(Inix2Config::get('IWFRAME_CLIENT_TOKEN'));
    }

    /**
     *
     */
    public function testUpdateClientInit()
    {
        $client_token = Inix2Config::get('IWFRAME_CLIENT_TOKEN');

        $shop = new Shop((int) Configuration::get('PS_SHOP_DEFAULT'));

        $this->assertEquals($this->api_url, $this->client->service_url);
        $this->assertEquals($this->api_url . $this->api_uri, $this->client->service_api_url);
        $this->assertEquals($client_token, $this->client->getClientToken());
        $this->assertEquals($shop->domain, $this->client->getShopDomain());
    }


    /**
     *
     */
    public function testRegister()
    {
        $this->client->setShopDomain(Tools::passwdGen() . '.com');

        $result = $this->client->register(Tools::passwdGen() . '@' . Tools::passwdGen(4) . '.com',
            Tools::passwdGen(10));

        $this->assertEquals('ok', $this->client->getStatus());
        $this->assertArrayHasKey('client_token', $result);

        $this->client->setClientToken($result['client_token']);

    }


    /**
     *
     */
    public function testUpdateCheckOldRequest()
    {
        $module_data = array(
            'name'      => 'affiliateproiw',
            'version'   => '1.0.0',
            'status'    => true,
            'installed' => false,
            'author'    => 'presta-apps'
        );
        $result      = $this->client->checkUpdate(array($module_data));

        $this->assertEquals('ok', $this->client->getStatus());
        $this->assertArrayHasKey('affiliateproiw', $result);
        $this->assertArrayHasKey('changelogs', $result['affiliateproiw']);
        $this->assertEquals('needupdate', $result['affiliateproiw']['status']);

    }

    /**
     *
     */
    public function testUpdateCheckNewRequest()
    {
        $module_data = array(
            'name'        => 'affiliateproiw',
            'version'     => '1.0.0',
            'status'      => true,
            'installed'   => false,
            'author'      => 'presta-apps',
            'dist_chanel' => 'presta-apps'
        );
        $result      = $this->client->checkUpdate(array($module_data));

        $this->assertEquals('ok', $this->client->getStatus());
        $this->assertArrayHasKey('affiliateproiw', $result);
        $this->assertArrayHasKey('changelogs', $result['affiliateproiw']);
        $this->assertEquals('needupdate', $result['affiliateproiw']['status']);

    }

    /**
     *
     */
    public function testUpdateCheckUpToDate()
    {
        $module_data = array(
            'name'        => 'affiliateproiw',
            'version'     => '9.1.5',
            'status'      => true,
            'installed'   => false,
            'author'      => 'presta-apps',
            'dist_chanel' => 'presta-apps'
        );
        $result      = $this->client->checkUpdate(array($module_data));

        $this->assertEquals('ok', $this->client->getStatus());
        $this->assertArrayHasKey('affiliateproiw', $result);

        $this->assertEquals('latest', $result['affiliateproiw']['status']);

    }


    /**
     *
     */
    public function testFetchOldRequest()
    {
        $result = $this->client->fetch('affiliateproiw', 'presta-apps');

        $this->assertEquals('ok', $this->client->getStatus());
        $this->assertArrayHasKey('archive', $result);


    }

    /**
     *
     */
    public function testFetchNewRequest()
    {

        $result = $this->client->fetch('affiliateproiw', 'presta-apps', 'presta-apps');

        $this->assertEquals('ok', $this->client->getStatus());
        $this->assertArrayHasKey('archive', $result);


    }


    /**
     *
     */
    public function testModuleInstall()
    {
        $obj          = new stdClass();
        $obj->name    = 'affiliateproiw';
        $obj->version = '9.1.5';
        $response     = $this->client->moduleInstall($obj);

        $this->assertEquals(true, is_array($response));
    }


    /**
     *
     */
    public function testModuleUninstall()
    {
        $obj          = new stdClass();
        $obj->name    = 'affiliateproiw';
        $obj->version = '9.1.5';
        $response     = $this->client->moduleUninstall($obj);

        $this->assertEquals(true, is_array($response));
    }

    /**
     *
     */
    public function testModuleUpdate()
    {
        $obj          = new stdClass();
        $obj->name    = 'affiliateproiw';
        $obj->version = '1.0.0';
        $response     = $this->client->moduleUpdate($obj, '1.1.5');

        $this->assertEquals(true, is_array($response));
    }
}
