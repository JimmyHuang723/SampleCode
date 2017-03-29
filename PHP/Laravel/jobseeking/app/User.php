<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    public $guarded = ["id","created_at","updated_at"];

    public static function findRequested()
    {
        $query = User::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('name') and $query->where('name','like','%'.\Request::input('name').'%');
        \Request::input('email') and $query->where('email','like','%'.\Request::input('email').'%');
        \Request::input('password') and $query->where('password','like','%'.\Request::input('password').'%');
        \Request::input('remember_token') and $query->where('remember_token','like','%'.\Request::input('remember_token').'%');
        \Request::input('created_at') and $query->where('created_at',\Request::input('created_at'));
        \Request::input('updated_at') and $query->where('updated_at',\Request::input('updated_at'));
        
        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(5);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email',
            'password' => 'required|string|max:255',
            'remember_token' => 'string|max:100',
        ];

        // no list is provided
        if(!$attributes)
            return $rules;

        // a single attribute is provided
        if(!is_array($attributes))
            return [ $attributes => $rules[$attributes] ];

        // a list of attributes is provided
        $newRules = [];
        foreach ( $attributes as $attr )
            $newRules[$attr] = $rules[$attr];
        return $newRules;
    }

}
