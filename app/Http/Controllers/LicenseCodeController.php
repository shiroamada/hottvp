<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LicenseCodeController extends Controller
{
    /**
     * Display the license code list view.
     */
    public function index(Request $request): View
    {
        $licenseCodes = [
            (object)['id' => 100091, 'code' => 'THSUWUPUCKGR', 'type' => '90-day license code', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2025-08-21', 'created_time' => '2025-05-21 23:39:11'],
            (object)['id' => 98276, 'code' => 'LSXZIEKOCWZJ', 'type' => '365-day license code', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2025-07-31', 'created_time' => '2024-07-31 16:11:52'],
            (object)['id' => 96977, 'code' => 'RSXURFQCORPA', 'type' => '7-day license code', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2024-04-03', 'created_time' => '2024-03-27 14:39:49'],
            (object)['id' => 96781, 'code' => 'EDZOKOLLYDQC', 'type' => '7-day license code', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2024-03-16', 'created_time' => '2024-03-09 20:06:02'],
            (object)['id' => 96197, 'code' => 'WIKVPRZWSLWT', 'type' => '365-day license code', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2025-02-07', 'created_time' => '2024-02-08 18:48:39'],
            (object)['id' => 70881, 'code' => 'HEBSXIBEKVZM', 'type' => '365-day license code', 'status' => 'Used', 'remarks' => '道洪', 'expired_date' => '2023-08-15', 'created_time' => '2022-08-15 15:22:06'],
            (object)['id' => 29719, 'code' => 'HQHKJUJAWTMP', 'type' => '365-day license code', 'status' => 'Used', 'remarks' => '道洪', 'expired_date' => '2022-08-08', 'created_time' => '2021-08-08 14:52:39'],
        ];
        return view('license.list', compact('licenseCodes'));
    }
}
