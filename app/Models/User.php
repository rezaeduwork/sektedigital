<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'phone',
    'password',
    'role',
    'photo',
    'gender',
    'birthdate',
    'address',
    'balance'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  public function store()
  {
    return $this->hasOne('App\Models\Store');
  }
  public function carts()
  {
    return $this->hasMany('App\Models\Cart');
  }
  public function transactions()
  {
    return $this->hasMany('App\Models\Transaction');
  }
  public function activities()
  {
    return $this->hasMany('App\Models\UserLog');
  }
  public function payments()
  {
    return $this->hasMany('App\Models\Payment');
  }
  public function balances()
  {
    return $this->hasMany('\App\Models\UserBalance');
  }
  public function banks()
  {
    return $this->hasMany('\App\Models\UserBank');
  }

  // HELPER
  public function reloadBalance()
  {
    $this->balance = $this->balances()->whereIn('type', ['fund', 'store_fund', 'withdraw'])->whereIn('status', ['pending', 'success'])->sum('amount');
    $this->save();
  }
  public function canManageBinshopsBlogPosts()
  {
    // Enter the logic needed for your app.
    // Maybe you can just hardcode in a user id that you
    //   know is always an admin ID?

    if (in_array($this->email, ['admin@gmail.com'])) {

      // return true so this user CAN edit/post/delete
      // blog posts (and post any HTML/JS)

      return true;
    }

    // otherwise return false, so they have no access
    // to the admin panel (but can still view posts)

    return false;
  }
}
