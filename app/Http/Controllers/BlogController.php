<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    function blogs() : View {
        // index functie verwacht een view en als die is gevonden dan stuurt het door
        // naar de view 'blogs' met een object en de waarde ervan

        // pak het object History en laat de value daarvan zien. net zoals een textbox 
        // van $description = dit product is 50 euro. maar nu is het "my blogs"
        return view('blogs',["History" => "My blogs, yabadabadoo"]);
    }
    //
}
