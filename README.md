# kuaifu-alipay

见过最简单对接支付宝开发平台所有接口的SDk

**！！请先熟悉支付宝说明文档[传送门](https://docs.open.alipay.com/api)！！**

欢迎 Star，欢迎 PR！

QQ交流群：725192850

## 特点
- 开发者不需要关注的细节
- 根据支付宝、微信最新 API 开发而成
- 高度抽象的类，免去各种拼json与xml的痛苦
- 符合 PSR 标准，你可以各种方便的与你的框架集成
- 方法使用更优雅，小白都能使用

## 运行环境
- PHP 5.5+
- composer

## 使用说明
加密方式为RAS2
```php
<?php
namespace app\index\controller;
use kuaifu\openalipay\supports\Alipay;
class Index
{
    protected $config = [
        'app_id' => '2016072800113002',
        'notify_url' => 'https://www.51fubei.com/',
        'return_url'=>'https://www.51fubei.com/',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwdYXW4mSRhFcrqj95gZkjw4gcLb/UDunM03nrXJDiQMMvp1zPHXqJK4A8v+8QN7O7IW/Ni5CkbjeAc2gXIqhSV2sV9ozPMl/Sa7JfN94TVzZism6Dsbvm7Hk9LgRVttn0P2vaZwg7CjKjBot9O7ja3ZuiBofrLVR645Bz865AoGt3n6YMmlLLLc6FHFX89XovnMuqTlqW1yYLCm5hwBDNOke24+aWntjkJy/NcOp1IJB/zy1PsleZnVYCk/bihxzsVaMeNGkZ94dfQVR9sK3Pv5/ruMkbBgY+KPj9qmIdnxWcVshpFIECh/z+aHRvWVHvVK9nSzZJsG3w9/2Z7a/9QIDAQAB',
        'private_key' => 'MIIEpQIBAAKCAQEAuzFjpTlDLCBfhX+qnzp9Bj2k59iUHNKeIDbuwP9Esdgk0CI1U6E7De5NOInZBoLqAqMaPXkh6xo+sov9T9lXFyos6Yw1Y7iOx9+/ub0X3N6iZlKKeP5N4/wlLzznGg09KN03wF+fJ5t/Iua96Z/UaukxcpjK5VlsRTTGB4y73lXCa1vF7oc8qU3zD87AMbzybpboScmKlUuOLx/2va1iL3pS3ElHu6WD9FXMhGPj2I9PRtaj7P54gvrq24Sv+SCXwr0jCtKJ7p2kWFJxAx6IemBgCP/cBa2U4yRw6C+T3RyolHzl5791OqMerBF4ASHDQbgPAhXpDTKao9ypk+JDGQIDAQABAoIBAQCiw1b5GsklXSCxW0hoGtsKyh7Q2KgwR5HmQN1oQozgdifMMXJcvRw0CLhsKK/j0YDXuineuaycV72cUcx32Wq4YZublqX1h0veztZOEfTlv09F2Q8+FSB005EhebsYE9rR5UfMpVglgDcTib4nnSl9rDWqhuAke5SAuFpXxi60KjClWwiM0m2aOgZAni0P/Ee7cln79GQwmqtMWYS2BSWyJesRv8TVHGEf0fZoYRPcJxMX4fudxTw1IlZuyS+cMjJLXiod4qzdmxt0q4CC5/joLDe1otLSrbiPxC+E3a31EgMC/edYNKgdY/JlAXNh/W9hpROUvsImAL2RTk8jMtgBAoGBAN0unSzTpaNY6pJx3TpGEP+18gVFcz7PzVZ/iMC5LBKSXO8d9zIbTcIW3puUOVWezIKQGFzjEKpzXsimS+mufzHRH68fIPxzH4kBmt+QIAiyLuJ2HHTKh4qat6LsmH2vW5viHUtG+b4U/6+dgdMEDZVL8UasBeIeQza+lXYCyh/hAoGBANipDQdC8NzJy35BagpCdeVRILYZg1P6sHGpUMOWROEaBpSNJGASaR/LDOy/5rfI3EjRrOcdw5EPb82+el9DeGXGachTSWwg/vuIbTic5dz5OnCpvBU6NjLVnJJs9Q6MD4GNBxwFK6O681RHAWR/Nqz50VNOwZx1OsavbDexbmo5AoGBAMhh6Mep35CDh9CxE8otnEzum5sm9mfbf55z4IMpv8H7k/KWZVOh5OqIKZktylvFiGZpAYl1ta2W5DJIZtII4vdRDLFtNEdCTTmkmJkeS8dVFqDYfKNasT1jOieCzgxgCFDLo9qFLNGG2LZTrkBslZnCuY5bXuEipaY9N884a8JBAoGBAKYaVMawZ4CdpMkS1X0wHrrES9PQE4MaupMpP6XzWrNJBKp22uBcvQOG5E3NgdB8yYV6WpiFlR1tPEX8Nk06qN8+EnNmhu/DDDlDCplQkFmtKUSenHeZvVuTsQoBRo70ih7ERCMTQ7Wu6kY+ea7WaNCMZhL8WirdJUaVzt2TxhbhAoGAGbxcjdl0WHmBeakiHfWEJkBWnS7lcU77N/MfQ55dIBdVydfOhxepNsyk7AEIPz7M8b09GMMo3w2CrjdjqMoWCV1o8UuajmS1jcbF9dicw89pRNlqkzybOFi5NPDTQSVIKN++6g+FphuCCeVSBR7J4CkUaECvNUkglqQZ/LoKihw=',
        'gateway'=>'https://openapi.alipaydev.com/gateway.do',//统一次参数判断用什么环境，目前是沙箱环境。正式环境：https://openapi.alipay.com/gateway.do
        'app_auth_token'=>''
    ];
    
    public function pay(){
        $order = [
            'out_trade_no' => time().'',
            'total_amount' => 0.01,
            'subject'      => '刷卡支付',
            'auth_code' => '284337153128733732',
            'scene'  => 'bar_code',
        ];
        $alipay=new Alipay($this->config);
        $alipay->execute('alipay.trade.pay',$order);//第一个参数为支付宝开放平台中调用方法名称，第二个参数为业务参数
    }
}

```

## 代码贡献
欢迎各位吐槽以及fork


