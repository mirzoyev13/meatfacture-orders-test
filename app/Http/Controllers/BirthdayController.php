<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\BirthdayRequest;

class BirthdayController extends Controller
{
    public function update(BirthdayRequest $request)
    {
        // В целом можно было через whereIn и findOfFail написать вместо if, но так просто нагляднее.
        // Например так если логировать не нужно было бы
//        User::whereIn('id', array_keys($birthdays))->get()->each(function ($user) use ($birthdays) {
//            $user->birthdate = $birthdays[$user->id];
//            $user->save();
//        });

        $birthdays = $request->birthdays;
        $errors = [];

        foreach ($birthdays as $userId => $date) {
            $user = User::find($userId);
            if (!$user) {
                Log::error("User not found: ID $userId");
                $errors[$userId] = 'User not found';
                continue;
            }

            $user->birthdate = $date;
            $user->save();
        }

        return response()->json([
            'message' => count($errors)
                ? 'Ошибка в обновлении нескольких пользователей'
                : 'Даты успешно обновлены',
            'errors' => $errors,
        ]);

    }
}
