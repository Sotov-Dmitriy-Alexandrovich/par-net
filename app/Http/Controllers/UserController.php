<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Показать профиль пользователя (Личный кабинет)
     */
    public function show()
    {
        $user = Auth::user();

        // Подгружаем небольшую статистику для профиля
        $stats = [
            'total_attempts' => $user->quitAttempts()->count(),
            'total_clean_days' => $user->quitAttempts()->sum('total_clean_days'),
            'reasons_count' => $user->reasons()->count(),
            'triggers_count' => $user->triggers()->count(),
        ];

        return view('users.profile', compact('user', 'stats'));
    }

    /**
     * Обновить данные профиля (Имя, Аватар)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Максимум 2МБ
        ]);

        $data = ['name' => $request->name];

        // Если пользователь загрузил новую аватарку
        if ($request->hasFile('avatar_url')) {
            // Удаляем старую аватарку, если она была
            if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
                Storage::disk('public')->delete($user->avatar_url);
            }

            // Сохраняем новую в папку storage/app/public/avatars
            $path = $request->file('avatar_url')->store('avatars', 'public');
            $data['avatar_url'] = $path;
        }

        // Обновляем пользователя в БД
        $user->update($data);

        return back()->with('success', 'Профиль успешно обновлен!');
    }
}
