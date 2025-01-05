<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function duplicate(Request $r)
    {
        $model = $r->modelClass::whereId($r->modelId)->first();

        // Check if the object is a model and if it uses the CanDuplicate trait
        // Calls the duplicateRecursiveDownstream method
        if ($model instanceof \Illuminate\Database\Eloquent\Model && $this->hasTrait($model, 'App\CanDuplicate')) {
            $model->duplicateRecursiveDownstream();
        }
    }

    // Check if the object has a trait
    private function hasTrait($object, $traitName)
    {
        $reflection = new \ReflectionObject($object);
        return in_array($traitName, $reflection->getTraitNames());
    }
}
