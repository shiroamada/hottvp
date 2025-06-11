<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrialCodeController extends Controller
{
    public function index()
    {
        $trialCodes = collect([
            (object)['id' => 96910, 'code' => 'KNZCGUZKXDU', 'status' => 'Used', 'remarks' => 'me', 'expired_date' => '2024-03-22', 'created_time' => '2024-03-21 13:47:47'],
            (object)['id' => 96909, 'code' => 'BEPLWWDMDOMDO', 'status' => 'New', 'remarks' => 'me', 'expired_date' => '', 'created_time' => '2024-03-21 13:47:47'],
            (object)['id' => 96908, 'code' => 'HXEBQUMDYBV', 'status' => 'New', 'remarks' => 'me', 'expired_date' => '', 'created_time' => '2024-03-21 13:47:47'],
            (object)['id' => 96907, 'code' => 'BQLYQIXSRPOE', 'status' => 'New', 'remarks' => 'me', 'expired_date' => '', 'created_time' => '2024-03-21 13:47:47'],
            (object)['id' => 96906, 'code' => 'WKBCMXIZDROG', 'status' => 'New', 'remarks' => 'me', 'expired_date' => '', 'created_time' => '2024-03-21 13:47:47'],
            (object)['id' => 96903, 'code' => 'IHCQGDPGIGIY', 'status' => 'Used', 'remarks' => 'Alice', 'expired_date' => '2024-03-21', 'created_time' => '2024-03-20 00:01:29'],
            (object)['id' => 96902, 'code' => 'MXAXRPTYJSMD', 'status' => 'New', 'remarks' => 'Alice', 'expired_date' => '', 'created_time' => '2024-03-20 00:01:29'],
            (object)['id' => 96901, 'code' => 'SPAOMGSPXWCU', 'status' => 'New', 'remarks' => 'Alice', 'expired_date' => '', 'created_time' => '2024-03-20 00:01:29'],
            (object)['id' => 96900, 'code' => 'XLWFQIVTKHCV', 'status' => 'New', 'remarks' => 'Alice', 'expired_date' => '', 'created_time' => '2024-03-20 00:01:29'],
            (object)['id' => 96899, 'code' => 'UZQNVWZDQHU', 'status' => 'New', 'remarks' => 'Alice', 'expired_date' => '', 'created_time' => '2024-03-20 00:01:29'],
            (object)['id' => 93773, 'code' => 'VLIULOGCEPSR', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2023-10-19', 'created_time' => '2023-10-18 15:16:26'],
            (object)['id' => 93772, 'code' => 'ZTIQRIFYYFZD', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2023-10-22', 'created_time' => '2023-10-18 15:16:26'],
            (object)['id' => 93771, 'code' => 'UJKYVFWTPHON', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2023-11-03', 'created_time' => '2023-10-18 15:16:26'],
        ]);
        return view('trial.list', compact('trialCodes'));
    }
}
