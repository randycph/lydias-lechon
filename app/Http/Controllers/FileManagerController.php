<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function __construct()
    {
        Permission::module_init($this, 'file_manager');
    }

    public function index(Request $request)
    {
        return view('admin.files.index');
    }
}
