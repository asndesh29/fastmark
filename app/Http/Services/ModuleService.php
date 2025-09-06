<?php

namespace App\Http\Services;

use App\Models\Module;

class ModuleService 
{
    public function index() 
    {
        
    }

    public function list() 
    {
        
    }

    public function store() 
    {
        
    }

    public function getById($id) 
    {
        return Module::find($id);
    }

    public function show($id) 
    {
        return $this->getById($id);
    }

    public function update($id) 
    {
        
    }

    public function destroy() 
    {
        
    }
}