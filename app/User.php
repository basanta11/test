<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'gender', 'status',
        'phone', 'address', 'house_number', 'image', 'image_url', 'school_name','citizen_number','symbol_number',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permission)
    {
        return !$this->role->permissions->where('title', $permission)->isEmpty();
    }

    public function hasAnyPermission($permissions)
    {
        return !$this->role->permissions->whereIn('title', $permissions)->isEmpty();
    }

    public function hasRole($role)
    {
        return $this->role->title == $role;
    }

    public function hasAnyRole($roles)
    {
        return in_array($this->role->title, $roles) ;
    }

    public function student_detail()
    {
        return $this->hasOne(StudentDetail::class);
    }

    public function student_reading()
    {
        return $this->hasOne(StudentReading::class);
    }

    public function created_user()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function sets()
    {
        return $this->belongsToMany(Set::class);
    }

    public function class_teacher()
    {
        return $this->hasMany(Section::class);
    }

    public function homework_user()
    {
        return $this->hasOne(HomeworkUser::class);
    }
    
    
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function chats()
    {
        return $this->belongsToMany(Group::class)->where('type', 0);
    }

    public function canJoinRoom($groupid)
    {
        return !$this->chats->where('id', $groupid)->isEmpty();
    }

    public function course_details()
    {
        return $this->hasMany(CourseDetail::class);
    }

    public function user_section_behaviours()
    {
        return $this->hasMany(UserSectionBehaviour::class);
    }

}
