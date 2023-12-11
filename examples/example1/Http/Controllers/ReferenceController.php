<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    public function newReference(Request $request, $modelName)
    {
        $data = $request->all();
        $modelName = '\\App\\Models\\' . $modelName;
        $reference = $modelName::create($data);

        return $reference;
    }
}

