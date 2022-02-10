<?php
namespace App\Services\Payment\Providers;



use App\Services\Payment\Contracts\AbstractProviderInterface;


class ZarinpalProvider extends AbstractProviderInterface 
{
    public function pay()
    {
        return 'ZarinpalProvider';
    }

    public function verify()
    {
        
    }
}
