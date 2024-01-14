<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    // protected $appends = ['image_asset', 'course_information', 'receipt_information'];

    protected $hidden = [
        'password',
    ];

    public function otps(): MorphMany
    {
        return $this->morphMany(Otp::class, 'verifiable');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id', 'id');
    }

    public function courses()
    {
        return $this->hasManyThrough(Course::class, Enrollment::class, 'student_id', 'id');
    }

    public function handledBy()
    {
        return $this->hasMany(Enrollment::class, 'handled_by', 'id');
    }

    public function enrolledBy()
    {
        return $this->hasMany(Enrollment::class, 'enrolled_by', 'id');
    }

    public function certificateHandledBy()
    {
        return $this->hasMany(Enrollment::class, 'certificate_handled_by', 'id');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'student_id', 'id');
    }

    public function getImageAssetAttribute()
    {
        return $this->image ? asset($this->image) : null;
    }

    public function getCourseInformationAttribute()
    {
        return $this->courses?->pluck('id', 'title')->all();

    }

    public function getReceiptInformationAttribute()
    {
        return $this->receipts?->pluck('id', 'type', 'total', 'paid', 'due')->all();

    }
}
