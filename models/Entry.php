<?php namespace NiaInteractive\Directory\Models;

use Carbon\Carbon;
use Model;
use Str;
use Winter\Storm\Database\Traits\SoftDelete;
use Winter\Storm\Database\Traits\Validation;
use Winter\Storm\Exception\ValidationException;

class Entry extends Model
{
    use Validation;
    use SoftDelete;

    public $table = 'niainteractive_directory_entries';

    protected $guarded = [];

    protected $casts = [
        'last_verified' => 'date',
    ];

    public $rules = [
        'name'        => 'required|between:2,120',
        'status'      => 'required|in:draft,active,stale,defunct',
        'listing'     => 'required|in:listed,reference',
        'website_url' => 'nullable|url|max:500',
    ];

    public $belongsTo = [
        'kind' => [Kind::class, 'key' => 'kind_id'],
    ];

    public $belongsToMany = [
        'categories' => [
            Category::class,
            'table'    => 'niainteractive_directory_category_entry',
            'key'      => 'entry_id',
            'otherKey' => 'category_id',
            'pivot'    => ['is_primary'],
        ],
        'tags' => [
            Tag::class,
            'table'    => 'niainteractive_directory_entry_tag',
            'key'      => 'entry_id',
            'otherKey' => 'tag_id',
            'pivot'    => ['evidence_url', 'evidence_level'],
        ],
    ];

    public $hasMany = [
        'incomingRelations' => [Relation::class, 'key' => 'target_entry_id'],
        'outgoingRelations' => [Relation::class, 'key' => 'source_entry_id'],
        'sources'           => [Source::class, 'key' => 'entry_id'],
    ];

    public function beforeValidate()
    {
        if (!$this->slug && $this->name) {
            $this->slug = Str::slug($this->name);
        }

        if ($this->slug) {
            $this->slug = Str::slug($this->slug);
        }

        if (!$this->sort_name && $this->name) {
            $this->sort_name = $this->name;
        }

        $clash = static::where('slug', $this->slug)
            ->when($this->id, function ($query) {
                $query->where('id', '!=', $this->id);
            })
            ->first();

        if ($clash) {
            throw new ValidationException(['slug' => 'That slug is already in use.']);
        }
    }

    public function beforeSave()
    {
        if ($this->status === 'active' && !$this->last_verified) {
            $this->last_verified = Carbon::today();
        }
    }

    public function getKindIdOptions()
    {
        return Kind::orderBy('sort_order')->pluck('name', 'id')->all();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeListed($query)
    {
        return $query->where('listing', 'listed');
    }

    /**
     * Entries that something else claims to replace.
     */
    public function scopeReplaceTargets($query)
    {
        return $query->whereIn('id', function ($sub) {
            $sub->select('target_entry_id')
                ->from('niainteractive_directory_relations')
                ->where('relation_type', 'replaces');
        });
    }

    /**
     * Entries that can replace this one.
     */
    public function alternatives()
    {
        $ids = Relation::where('target_entry_id', $this->id)
            ->where('relation_type', 'replaces')
            ->orderBy('rank')
            ->pluck('source_entry_id')
            ->all();

        return static::whereIn('id', $ids ?: [0])->orderBy('sort_name');
    }

    /**
     * Entries this one can replace.
     */
    public function replacedEntries()
    {
        $ids = Relation::where('source_entry_id', $this->id)
            ->where('relation_type', 'replaces')
            ->pluck('target_entry_id')
            ->all();

        return static::whereIn('id', $ids ?: [0])->orderBy('sort_name');
    }

    public function isStale()
    {
        if ($this->status === 'stale') {
            return true;
        }

        if (!$this->last_verified) {
            return false;
        }

        $days = (int) (Settings::get('stale_after_days', 365));

        return $this->last_verified->lt(Carbon::today()->subDays($days));
    }
}
