<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuideController extends Controller
{
    /**
     * Главная страница гайда
     */
    public function index()
    {
        return view('guide.index');
    }

    /**
     * Отдельная секция гайда
     */
    public function section($slug)
    {
        $sections = [
            'intro' => 'Вместо предисловия',
            'why-quit' => 'Зачем тебе это всё?',
            'why-vape' => 'Зачем вы парите?',
            'why-fails' => 'Почему "просто перестать" не работает',
            'preparation' => 'Подготовка',
            'methods' => 'Способы бросить',
            'relapse' => 'Что делать, если сорвался',
        ];

        if (!array_key_exists($slug, $sections)) {
            abort(404);
        }

        return view('guide.section', [
            'slug' => $slug,
            'title' => $sections[$slug],
        ]);
    }
}
