<?php namespace Xitara\Core\Models;

use Model;
use Str;
use Xitara\Core\Models\Menu;

/**
 * CustomMenu Model
 */
class CustomMenu extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'xitara_core_custommenus';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = ['links'];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function beforeSave()
    {
        /**
         * update code from xitara_core_menus
         */
        $success = Menu::where('code', 'xitara.custommenulist.' . $this->slug)
            ->update([
                'code' => 'xitara.custommenulist.' . Str::slug($this->name),
                'name' => $this->name,
            ]);

        $this->slug = Str::slug($this->name);
    }

    public function beforeDelete()
    {
        /**
         * delete code from xitara_core_menus
         */
    }
}
