<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\UserList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserInfo;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'email',
        'password',
        'image',
        'date_of_birth',
        'academic_year',
        'acc_status',
        'profile_completed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
        public function scoreSelectUserName($query){
            return $query->select('user.id', 'first_name', 'last_name');
        }



    public function Userinfo() {
        return $this->hasOne(UserInfo::class, 'user_id', 'id');
    }

    public function lists(){
        return $this->belongsToMany(UserList::class, 'user_list_items', 'user_id', 'list_id')->withTimestamps();
    }

    public function scoreOnScoreboard(){

       return $this->hasOne(Scoreboard::class, 'user_id');
    }

    //--Pjesa e Kursit--//
    
    public function createdCourses(){
        return $this->hasMany(Course::class, 'created_by');

    }
    public function updatedCourses(){
        return $this->hasMany(Course::class, 'updated_by');

    }
    public function createdCourseMaterials(){
        return $this->hasMany(CourseMaterial::class, 'created_by');
    }

    public function updatedCourseMaterials(){
        return $this->hasMany(CourseMaterial::class, 'updated_by');
    }

}
