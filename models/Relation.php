<?php namespace NiaInteractive\Directory\Models;

use Model;
use Winter\Storm\Database\Traits\Validation;
use Winter\Storm\Exception\ValidationException;

/**
 * A directed edge between two entries.
 *
 * source_entry_id REPLACES target_entry_id. The edge is explicit: nothing is
 * inferred transitively, and a cycle is rejected at save time. That is what
 * stops "Gmail replaces Proton" from ever existing alongside
 * "Proton replaces Gmail".
 */
class Relation extends Model
{
    use Validation;

    public $table = 'niainteractive_directory_relations';

    public $rules = [
        'source_entry_id' => 'required|exists:niainteractive_directory_entries,id',
        'target_entry_id' => 'required|exists:niainteractive_directory_entries,id',
        'relation_type'   => 'required|max:40',
    ];

    public $belongsTo = [
        'source' => [Entry::class, 'key' => 'source_entry_id'],
        'target' => [Entry::class, 'key' => 'target_entry_id'],
    ];

    public function beforeValidate()
    {
        if ($this->source_entry_id && $this->source_entry_id == $this->target_entry_id) {
            throw new ValidationException([
                'target_entry_id' => 'An entry cannot relate to itself.',
            ]);
        }

        if (!$this->relation_type) {
            $this->relation_type = 'replaces';
        }
    }

    public function beforeSave()
    {
        if ($this->createsCycle()) {
            throw new ValidationException([
                'target_entry_id' => 'That would create a circular chain. '
                    . 'An entry cannot replace something that directly or indirectly replaces it.',
            ]);
        }
    }

    /**
     * Would adding this edge close a loop? Walk forward from the target
     * looking for the source. Depth-first over the adjacency list; the
     * table is small enough that this needs no recursive SQL.
     */
    protected function createsCycle()
    {
        $source = (int) $this->source_entry_id;
        $target = (int) $this->target_entry_id;

        if (!$source || !$target) {
            return false;
        }

        $type  = $this->relation_type ?: 'replaces';
        $seen  = [];
        $stack = [$target];

        while (!empty($stack)) {
            $node = array_pop($stack);

            if ($node === $source) {
                return true;
            }

            if (isset($seen[$node])) {
                continue;
            }

            $seen[$node] = true;

            $query = static::where('relation_type', $type)
                ->where('source_entry_id', $node);

            if ($this->id) {
                $query->where('id', '!=', $this->id);
            }

            foreach ($query->pluck('target_entry_id')->all() as $next) {
                $stack[] = (int) $next;
            }
        }

        return false;
    }

    public function getSourceEntryIdOptions()
    {
        return Entry::orderBy('sort_name')->pluck('name', 'id')->all();
    }

    public function getTargetEntryIdOptions()
    {
        return Entry::orderBy('sort_name')->pluck('name', 'id')->all();
    }
}
