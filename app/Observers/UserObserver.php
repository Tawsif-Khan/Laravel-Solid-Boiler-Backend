<?php

namespace App\Observers;

use App\Enums\User\Role;
use App\Mail\SendPassword;
use App\Models\User;
use App\Traits\HasSID;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserObserver
{
    use HasSID;

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $password = Str::random(8);

        $user->sid = match ($user->role) {
            Role::SuperAdmin->value => $this->sid(postfix: self::SUPER_ADMIN_POSTFIX, id: $user->id),
            Role::Admin->value => $this->sid(postfix: self::ADMIN_POSTFIX, id: $user->id),
            Role::Executive->value => $this->sid(postfix: self::EXECUTIVE_POSTFIX, id: $user->id),
            Role::Teacher->value => $this->sid(postfix: self::TEACHER_POSTFIX, id: $user->id),
            Role::Student->value => $this->sid(postfix: self::STUDENT_POSTFIX, id: $user->id),
            default => $this->sid(postfix: 'O', id: $user->id)
        };

        $user->password = Hash::make($password);
        $user->save();

        Mail::to($user->email)->send(new SendPassword(
            password: $password,
            name: $user->name,
            email: $user->email
        ));
    }
}
