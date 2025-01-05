<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait CanDuplicate
{
    public function duplicateRecursiveDownstream(Model $model = null, array &$processed = [])
    {
        $model = $model ?? $this; // Default to the current model if none provided (First call)
        $modelKey = get_class($model).':'.$model->getKey();

        // Prevent duplicate processing
        if (in_array($modelKey, $processed)) {
            return null; // Already processed this model
        }

        $processed[] = $modelKey; // Mark as processed

        // Used DB::transaction to ensure that that if any part of the duplication process fails (ex. a database error), all changes will be rolled back, maintaining data integrity.
        // This is especially important since we are duplicating multiple models and their relations, as it ensures that the duplication is an all-or-nothing operation.
        return DB::transaction(function() use ($model){
            try {
                // Clone the model
                $newModel = $model->replicate();
                $newModel->push(); // Save the duplicated model

                foreach($model->getDownstreamRelations() as $relatedModels){
                    $rel = $model->$relatedModels;

                    if($rel instanceof \Illuminate\Database\Eloquent\Collection){
                        foreach($rel as $relatedModel){
                            // Recursively duplicate related models (downstream)
                            $newRelatedModel = $this->duplicateRecursiveDownstream($relatedModel);
                            $foreignKey = $model->$relatedModels()->getForeignKeyName();
                            $newRelatedModel->$foreignKey = $newModel->id;
                            $newRelatedModel->push();

                            // Assuming Laravel Media Library is used for media management and media is supposed to be duplicated
                            // If models other than Blocks have media, and only the media from Blocks is meant to be duplicated, conditions can be added here to handle this (ex. trough get_class())
                            // Did not implement Laravel Media Library here as media handling is not part of the original question

                            // Copy media from the original model to the duplicated model
                            // $relatedModel->getMedia()->each(function($media) use ($newRelatedModel){
                            //     $media->copy($newRelatedModel, 'duplicated-media');
                            // });
                        }
                    }
                }

                return $newModel;
            } catch (\Exception $e) {
                // log the error
                \Log::error("Duplication failed: " . $e->getMessage());
                throw $e; // Re-throw the exception to trigger rollback
            }
        });
    }
}
