This Laravel application is built using Blade, AlpineJS, and Laravel Sail. It implements the requested recursive duplication behavior within the provided structure.

Before running the application, seed the database to populate it with the appropriate data structure.

The `EpisodeSeeder` will generate the following structure:
```php
Episodes::factory(2)->has(
    Parts::factory(3)->has(
        Items::factory(5)->has(
            Blocks::factory(10)
        )
    )
)->create();
```

---

The duplication logic is implemented in the `duplicateRecursiveDownstream()` function (below), located in the `App\CanDuplicate` Trait, so it can be centralized and reused across all models requiring duplication.

```php
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
```

---

The duplication behavior can be extended to other models by:

1. Adding the `CanDuplicate` trait to the model.

2. Defining a `getDownstreamRelations()` method that returns the direct downstream relationships to be duplicated.

    
Example:
```php
    use App\CanDuplicate;

    class Episodes extends Model
    {
        use CanDuplicate;

        // Relationships
        public function parts()
        {
            return $this->hasMany('App\Models\Parts', 'episode_id');
        }

        // This is the method that is called by the CanDuplicate trait
        // Returns the downstream relationships of the model that should be duplicated
        public function getDownstreamRelations()
        {
            return ['parts'];
        }
```
```php
    use App\CanDuplicate;

    class Parts extends Model
    {
        use CanDuplicate;

        // Relationships
        public function items()
        {
            return $this->hasMany('App\Models\Items', 'part_id');
        }
        public function episode()
        {
            return $this->belongsTo('App\Models\Episodes');
        }

        // This is the method that is called by the CanDuplicate trait
        // Returns the downstream relationships of the model that should be duplicated
        public function getDownstreamRelations()
        {
            return ['items'];
        }
```

