<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Do List'
        ];
        
        return view('layouts/main', $data);
    }
}