<?php

namespace App\Payment;

use PHPUnit\Framework\TestCase;
use RustemKaimolla\KazPostPayment\Payment\Service;

class ServiceTest extends TestCase
{
    public function testGetParams()
    {
        $service = new Service();
        $service->setOrder(801195120);
        $service->setAmount(1.23);
        $service->setMerchant(92050004);
        $service->setTerminal(92050004);
        $service->setClientId(12);
        $service->setDescOrder('2rbina');
        $service->setName('NAME OF CLIENT');
        $service->setEmail('rustem@kazinsys.kz');
        $service->setBackref('https://www.google.kz/?#q=kazinsys.kz');
        $service->setDesc('2');

        $params = [
            'ORDER'      => 801195120,
            'AMOUNT'     => 1.23,
            'CURRENCY'   => 'KZT',
            'MERCHANT'   => 92050004,
            'TERMINAL'   => 92050004,
            'NONCE'      => time(),
            'LANGUAGE'   => 'ru',
            'CLIENT_ID'  => 12,
            'DESC'       => '2',
            'DESC_ORDER' => '2rbina',
            'NAME'       => 'NAME OF CLIENT',
            'EMAIL'      => 'rustem@kazinsys.kz',
            'BACKREF'    => 'https://www.google.kz/?#q=kazinsys.kz',

            'Ucaf_Flag'                => '',
            'Ucaf_Authentication_Data' => '',
            'test' => '',
        ];

        $this->assertEquals($params, $service->getParams());
    }

    public function testPSign()
    {
        $service = new Service();
        $params = [
            'ORDER'      => 801195120,
            'AMOUNT'     => 1.23,
            'CURRENCY'   => 'KZT',
            'MERCHANT'   => 92050004,
            'TERMINAL'   => 92050004,
            'NONCE'      => '1646814916',
            'LANGUAGE'   => 'ru',
            'CLIENT_ID'  => 12,
            'DESC'       => '2',
            'DESC_ORDER' => '2rbina',
            'NAME'       => 'NAME OF CLIENT',
            'EMAIL'      => 'rustem@kazinsys.kz',
            'BACKREF'    => 'https://www.google.kz/?#q=kazinsys.kz',

            'Ucaf_Flag'                => '',
            'Ucaf_Authentication_Data' => '',
            'test' => '',
        ];

        $this->assertEquals(
            '1102c8fd35de3d0dfadfd72934c874a2ad2d2dc3dcf16a8af7e1e926eabb7e78f9874b9525d1b9d041e400b661a19c65c21d619e40c07547b3cf453abec83972',
            $service->pSign($params, '01234567890123456789012')
        );
    }

    public function testGetCheckStatusParams()
    {
        $service = new Service();

        $service->setOrder(801195120);
        $service->setMerchant(92050004);
        $service->setSharedSecret('01234567890123456789012');

        $params = [
            "ORDER" => 801195120,
            "MERCHANT" => 92050004,
            "GETSTATUS" => 1,
            "P_SIGN" => "01ef24b8736034bfa7346a4bb706e8151dcbc51f48a76c9ed24a76317cfd4bd8f96d1fd4e4410a46fcc4f88a546518af46ebcb102f234ec7b4ef0c2eee435b4a",
        ];

        $this->assertEquals($params, $service->getCheckStatusParams());
    }
}
