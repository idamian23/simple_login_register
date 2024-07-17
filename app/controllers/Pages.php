<?php

class Pages extends Controller
{
    public function __construct()
    {

    }

    // Load Homepage
    public function index()
    {
        //Set Data
        $data = ['title' => 'Welcome To User Access App with PHP'];

        // Load homepage/index view
        $this->view('pages/index', $data);
    }

    public function about()
    {
        //Set Data
        $data = ['title' => 'User access App with PHP', 'version' => '1.0.0'];

        // Load about view
        $this->view('pages/about', $data);
    }
}