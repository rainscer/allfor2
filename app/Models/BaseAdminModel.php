<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;

class BaseAdminModel extends Model
{

    /*
     *
     */
    public static $rules = [];

    /*
     *
     */
    public $additionalItems = [
        'name'  => 'name'
    ];

    /*
     *
     */
    public $defaultFields = [];

    /*
     *
     */
    public static $rulesForContactForm = [
        'name'      => 'required|max:255',
        'company'   => 'required|max:255',
        'telephone' => 'required|max:255',
        'email'     => 'required|email',
        'message'   => 'required'
    ];
    /*
     *
     */
    public static $lazyLoadList = [];

    /*
     *
     */
    public static $locations = [
        'usa'       => 'USA',
        'canada'    => 'Canada',
        'caribbean' => 'Caribbean',
        's-america' => 'South America',
        'africa'    => 'Africa',
        'europe'    => 'Europe',
        'm-east'    => 'Middle East',
        'russia'    => 'Russia (Asia)',
        'oceania'   => 'Oceania'
    ];

    /*
     *
     */
    public static $destinationIcons = [
        'ad',
        'ae',
        'af',
        'ai',
        'al',
        'am',
        'ao',
        'aq',
        'ar',
        'as',
        'at',
        'au',
        'aw',
        'ax',
        'az',
        'ba',
        'bb',
        'be',
        'bd',
        'bf',
        'bg',
        'bh',
        'bi',
        'bj',
        'bl',
        'bm',
        'bn',
        'bo',
        'bq',
        'br',
        'bs',
        'bt',
        'bv',
        'bw',
        'by',
        'bz',
        'ca',
        'cc',
        'cd',
        'cf',
        'cg',
        'ch',
        'ci',
        'ck',
        'cl',
        'cm',
        'cn',
        'co',
        'cr',
        'cu',
        'cv',
        'cw',
        'cx',
        'cy',
        'cz',
        'de',
        'dj',
        'dk',
        'dm',
        'do',
        'dz',
        'ec',
        'ee',
        'eg',
        'eh',
        'er',
        'es',
        'et',
        'eu',
        'fi',
        'fj',
        'fk',
        'fm',
        'fo',
        'fr',
        'ga',
        'gb',
        'gd',
        'ge',
        'gf',
        'gg',
        'gh',
        'gi',
        'gl',
        'gm',
        'gn',
        'gp',
        'gq',
        'gr',
        'gs',
        'gt',
        'gu',
        'gw',
        'gy',
        'hk',
        'hm',
        'hr',
        'ht',
        'hu',
        'id',
        'ie',
        'il',
        'im',
        'in',
        'io',
        'iq',
        'ir',
        'is',
        'it',
        'je',
        'jm',
        'jo',
        'jp',
        'ke',
        'kg',
        'kh',
        'ki',
        'km',
        'kn',
        'kp',
        'kr',
        'kw',
        'ky',
        'kz',
        'la',
        'lb',
        'lc',
        'li',
        'lk',
        'lr',
        'ls',
        'lt',
        'lu',
        'lv',
        'ly',
        'ma',
        'mc',
        'md',
        'me',
        'mf',
        'mg',
        'mh',
        'mk',
        'ml',
        'mm',
        'mn',
        'mo',
        'mp',
        'mq',
        'mr',
        'ms',
        'mt',
        'mu',
        'mv',
        'mw',
        'mx',
        'my',
        'mz',
        'na',
        'nc',
        'ne',
        'nf',
        'ng',
        'ni',
        'nl',
        'no',
        'np',
        'nr',
        'nu',
        'nz',
        'om',
        'pa',
        'pe',
        'pf',
        'pg',
        'ph',
        'pk',
        'pl',
        'pm',
        'pn',
        'pr',
        'ps',
        'pt',
        'pw',
        'py',
        'qa',
        're',
        'ro',
        'rs',
        'ru',
        'rw',
        'sa',
        'sb',
        'sc',
        'sd',
        'se',
        'sg',
        'sh',
        'si',
        'sj',
        'sk',
        'sl',
        'sm',
        'sn',
        'so',
        'sr',
        'ss',
        'st',
        'sv',
        'sx',
        'sy',
        'sz',
        'tc',
        'td',
        'tf',
        'tg',
        'th',
        'tj',
        'tk',
        'tl',
        'tm',
        'tn',
        'to',
        'tr',
        'tt',
        'tv',
        'tw',
        'tz',
        'ua',
        'ug',
        'um',
        'un',
        'us',
        'uy',
        'uz',
        'va',
        'vc',
        've',
        'vg',
        'vi',
        'vn',
        'vu',
        'wf',
        'ws',
        'ye',
        'yt',
        'za',
        'zm',
        'zw'
    ];

    /**
     * @param $id
     * @return array
     */
    public static function getRulesForEdit($id)
    {

        return static::$rules;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotActive($query)
    {
        return $query;
    }

    /**
     * @param int $hours
     * @param bool $onlyActive
     * @param bool $onlyInActive
     * @return mixed
     */
    public function getModelEntries($hours = 0, $onlyActive = false, $onlyInActive = false)
    {
        if($this->timestamps) {
            $records = static::latest();
        }else{
            $records = new static();
        }

        if($hours) {

            $date = Carbon::now()
                ->subHours($hours)
                ->toDateTimeString();

            $records = $records->where('created_at', '>=', $date);
        }

        if($onlyActive){
            $records = $records->active();
        }

        if($onlyInActive){
            $records = $records->notActive();
        }

        if(count(static::$lazyLoadList)){
            $records = $records->with(static::$lazyLoadList);
        }

        return $records->paginate(20);
    }

    /**
     * @param $input
     * @return mixed
     */
    public function getFilteredModelEntries($input)
    {
        $records = static::latest();

        $list = $this->additionalItems;

        foreach ($list as $key => $item) {
            if(isset($input[$key])) {
                if(static::getNameColumn() === $key){
                    $records = $records->where($item,'like', '%' . trim(htmlentities($input[$key])) . '%');
                }else {
                    $records = $records->where($item, trim(htmlentities($input[$key])));
                }
            }
        }

        if(array_has($input, 'active') && $input['active'] != 'all'){
            $records = $records->where($this->getColumnForActive(), $input['active']);
        }

        return $records->paginate(20)->appends($input);
    }

    /**
     * @return bool
     */
    public function setDefaults()
    {
        foreach ($this->defaultFields as $field) {
            $this->{$field} = false;
        }

        return true;
    }


    /**
     * @return mixed|string
     */
    public function getName()
    {
        return isset($this->name) ? $this->name : '';
    }

    /**
     * @return string
     */
    public function getColumnForActive()
    {
        return 'active';
    }

    /**
     *
     */
    public function setActivated()
    {
        $this->{$this->getColumnForActive()} = true;
        $this->save();
    }

    /**
     *
     */
    public function setDeactivated()
    {
        $this->{$this->getColumnForActive()} = false;
        $this->save();
    }

    /**
     * @return string
     */
    public static function getNameColumn()
    {
        return 'name';
    }


    /**
     * @param $query
     * @param null $userId
     * @return mixed
     */
    public function scopeForUser($query, $userId = null)
    {
        return $query->where('user_id', Auth::id());
    }

    /**
     * @param $list
     * @return mixed
     */
    protected static function transArray($list)
    {
        foreach ($list as $key => $item) {
            $list[$key] = trans($item);
        }

        return $list;
    }

    /**
     * @param $slug
     * @param $title
     * @param null $id
     * @return string
     */
    public static function checkSlugUnique($slug, $title, $id = null)
    {
        $slug = $slug ? str_slug($slug) : str_slug($title);

        if($id) {
            $slugExist = static::where('slug', $slug)
                ->where('id', '<>', $id)
                ->first();
        }else{
            $slugExist = static::where('slug', $slug)
                ->first();
        }

        if($slugExist){

            $slugCount = 1;
            $slugGenerated = $slug;

            while($slugCount > 0)
            {
                $slugGenerated = $slugGenerated . '-' . rand(0, 100);

                $slugCount = static::where('slug', $slugGenerated)->count();
            }

            return $slugGenerated;
        }

        return $slug;
    }
}