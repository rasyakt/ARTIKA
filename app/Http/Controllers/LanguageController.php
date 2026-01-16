<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Ubah bahasa aplikasi
     *
     * @param Request $request
     * @param string $lang
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change(Request $request, $lang)
    {
        $supported = array_keys(config('app.supported_languages', ['id' => 'Indonesian', 'en' => 'English']));

        // Validasi bahasa yang didukung
        if (!in_array($lang, $supported)) {
            $lang = 'id';
        }

        // Simpan ke session
        session(['language' => $lang]);

        // Set aplikasi locale
        App::setLocale($lang);

        // Redirect kembali ke halaman sebelumnya
        return redirect()->back();
    }
}
