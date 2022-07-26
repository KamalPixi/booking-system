<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddAdminController extends Controller {
    public function addAirPre() {
        $active = [
            'menu' => 'add',
            'sub_menu' => 'add-pre-air',
        ];
        return view('admin.add.add-pre-air', compact('active'));
    }
}
