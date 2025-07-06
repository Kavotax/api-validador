<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function validateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->input('email');
        $domain = substr(strrchr($email, "@"), 1);

        if (!checkdnsrr($domain, 'MX')) {
            return response()->json(['valid' => false, 'reason' => 'Domain has no MX records']);
        }

        return response()->json(['valid' => true]);
    }

    public function validatePhone(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        $phone = $request->input('phone');

        if (preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
            return response()->json(['valid' => true]);
        } else {
            return response()->json(['valid' => false, 'reason' => 'Invalid phone format']);
        }
    }
}
