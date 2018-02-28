<?php

namespace kuaifu\openalipay\supports;


class Alipay
{
    /**
     * Config.
     */
    protected $config;

    /**
     * Alipay payload.
     *
     * @var array
     */
    protected $payload;

    /**
     *
     * @param  $config
     */
    public function __construct($config)
    {
        $this->config=$config;
        $this->payload = [
            'app_id'      => $config['app_id'],
            'method'      => '',
            'format'      => 'JSON',
            'charset'     => 'utf-8',
            'sign_type'   => 'RSA2',
            'version'     => '1.0',
            'return_url'  => $config['return_url'],
            'notify_url'  => $config['notify_url'],
            'timestamp'   => date('Y-m-d H:i:s'),
            'sign'        => '',
            'biz_content' => '',
            'app_auth_token'=>$config['app_auth_token'],
        ];
    }
    /**
     * Pay an order.
     *
     *
     * @param string $endpoint
     * @param array  $payload
     *
     * @return Collection
     */
    public function execute($method, array $payload)
    {
        $this->payload['method'] = $method;
        $this->payload['biz_content'] = json_encode($payload);
        $this->payload['sign'] = Support::generateSign($this->payload, $this->config['private_key']);
        return Support::requestApi($this->payload, $this->config['ali_public_key'],$this->config['gateway']);
    }
}