<?php

namespace RustemKaimolla\KazPostPayment\Payment;

/**
 * Сервис оплаты
 */
class Service
{
    protected string $sharedSecret;
    /**
     * Номер заказа (Объязательное поле)
     *
     * @var int
     */
    protected int $order;

    /**
     * сумма платежа (Объязательное поле)
     *
     * @var float
     */
    protected float $amount;

    /**
     * Валюта ISO 4217 (Объязательное поле)
     *
     * @var string
     */
    protected string $currency = 'KZT';

    /**
     * ID продавца (Объязательное поле)
     *
     * @var int
     */
    protected int $merchant;

    /**
     * ID терминала (Объязательное поле)
     *
     * @var int
     */
    protected int $terminal;

    /**
     * Язык виджеат оплаты, по умолчанию русский
     *
     * @var string
     */
    protected string $language = 'ru';

    /**
     * ID клиента
     *
     * @var int
     */
    protected int $clientId = 0;

    /**
     * Описание транзакции (Объязательное поле|внутреннее)
     *
     * @var string
     */
    protected string $desc = '';

    /**
     * Описание для вывода на странице оплаты
     *
     * @var string
     */
    protected string $descOrder = '';

    /**
     * Имя клиента
     *
     * @var string
     */
    protected string $name = '';

    /**
     * Электронная почта клиента
     *
     * @var string
     */
    protected string $email = '';

    /**
     * URL на который перенапрвляется клиент после оплаты
     *
     * @var string
     */
    protected string $backref = '';

    /**
     * Установить номер заказа
     *
     * @param int $order
     */
    public function setOrder(int $order): void
    {
        $this->order = $order;
    }

    /**
     * Установить сумму счета
     *
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * Установить валюту ISO 4217
     *
     * @param string $currency
     */
    public function setCurrency(string $currency = 'KZT'): void
    {
        $this->currency = $currency;
    }

    /**
     * Установить ID продавца
     *
     * @param int $merchant
     */
    public function setMerchant(int $merchant): void
    {
        $this->merchant = $merchant;
    }

    /**
     * Установить ID терминала
     *
     * @param int $terminal
     */
    public function setTerminal(int $terminal): void
    {
        $this->terminal = $terminal;
    }

    /**
     * Установить язык старницы оплаты
     *
     * @param string $language
     */
    public function setLanguage(string $language = 'ru'): void
    {
        $this->language = $language;
    }

    /**
     * Установить ID клиента
     *
     * @param int|null $clientId
     */
    public function setClientId(int $clientId = null): void
    {
        $this->clientId = $clientId ?? time();
    }

    /**
     * Установить описание заказа
     *
     * @param string $desc
     */
    public function setDesc(string $desc): void
    {
        $this->desc = $desc;
    }

    /**
     * Установить описание заказа(для клиента)
     *
     * @param string $descOrder
     */
    public function setDescOrder(string $descOrder): void
    {
        $this->descOrder = $descOrder;
    }

    /**
     * Установить имя клиента
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Установить почту клиента
     *
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Установить обратную ссылку после оплаты
     *
     * @param string $backref
     */
    public function setBackref(string $backref): void
    {
        $this->backref = $backref;
    }

    /**
     * Установить ключ доступа к API
     *
     * @param string $sharedSecret
     *
     * @return void
     */
    public function setSharedSecret(string $sharedSecret)
    {
        $this->sharedSecret = $sharedSecret;
    }

    /**
     * Возвращает массив параметров
     *
     * @return array
     */
    public function getParams(): array
    {
        return [
            'ORDER'      => $this->order,
            'AMOUNT'     => $this->amount,
            'CURRENCY'   => $this->currency,
            'MERCHANT'   => $this->merchant,
            'TERMINAL'   => $this->terminal,
            'NONCE'      => time(),
            'LANGUAGE'   => $this->language,
            'CLIENT_ID'  => $this->clientId,
            'DESC'       => $this->desc,
            'DESC_ORDER' => $this->descOrder,
            'NAME'       => $this->name,
            'EMAIL'      => $this->email,
            'BACKREF'    => $this->backref,

            'Ucaf_Flag'                => '',
            'Ucaf_Authentication_Data' => '',
            'test' => '',
        ];
    }

    /**
     * Возвращает подпись
     *
     * @param array $params
     * @param string $sharedSecret
     *
     * @return false|string
     */
    public function pSign(array $params, string $sharedSecret)
    {
        if (key_exists('NAME', $params)) {
            unset($params['NAME']);
        }

        if (key_exists('LANGUAGE', $params)) {
            unset($params['LANGUAGE']);
        }

        if (key_exists('WTYPE', $params)) {
            unset($params['WTYPE']);
        }

        if (key_exists('GETSTATUS', $params)) {
            unset($params['GETSTATUS']);
        }

        $dataForSign = sprintf("%s%s", $sharedSecret, implode(';', $params));

        return hash('sha512', $dataForSign);
    }

    /**
     * Возвращает необходимые параметры для запроса статуса заказа
     *
     * @return array
     */
    public function getCheckStatusParams(): array
    {
        $params = [
            'ORDER' => $this->order,
            'MERCHANT' => $this->merchant,
            'GETSTATUS' => 1,
        ];

        $params['P_SIGN'] = $this->pSign($params, $this->sharedSecret);

        return $params;
    }
}
