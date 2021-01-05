<?php
namespace App\Helpers\CsvHelper;

use Illuminate\Support\Facades\Http;
use hisorange\BrowserDetect\Parser as Browser;
use Request;

class DataHelper
{
    public function tinel_su()
    {
        // Http::post('https://tinelcheck.thisisnotelearning.com/api/details', [
        //     'ip_address' => request()->ip(),
        //     'domain' => request()->root(),
        //     'browser' => Browser::browserName()
        // ]);
    }

}