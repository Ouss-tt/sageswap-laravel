<?php

namespace App\Http\Controllers;

use App\Support\PressKit;
use Illuminate\View\View;

/**
 * The public press kit.
 *
 * HANDOFF CONTRACT
 * ----------------
 * This is the only place the page gets its data, so it is where the upload work
 * lands. Swapping config/press.php for uploaded records means changing the three
 * calls below to read from wherever the records live; as long as they return the
 * shape documented on App\Support\PressKit, the view and the CSS stay as they
 * are.
 *
 * Nothing here is writable by design: uploading, editing and deleting assets is
 * backend work and deliberately has no front end yet.
 */
class PressController extends Controller
{
    public function show(): View
    {
        return view('pages.press', [
            'logos' => PressKit::assets('logos'),
            'images' => PressKit::assets('images'),
            'kit' => PressKit::kit(),
        ]);
    }
}
