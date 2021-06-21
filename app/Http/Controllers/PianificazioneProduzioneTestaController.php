<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PianificazioneProduzioneTestaController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function creaPianificazioneForm() {
        return view('pianificazioneproduzione.creapianificazioneform');
    }
}
