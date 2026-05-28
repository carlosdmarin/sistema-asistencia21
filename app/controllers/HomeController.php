<?php
// app/controllers/HomeController.php

class HomeController extends Controller 
{
    public function index(): void 
    {
    $this->view('home/landing');
    }
}