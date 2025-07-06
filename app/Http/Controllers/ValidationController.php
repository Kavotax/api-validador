<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function validateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            // Retornamos un JSON con la info del error y código 400 (Bad Request)
            return response()->json([
                'valid' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $email = $request->input('email');
        $domain = substr(strrchr($email, "@"), 1);

        if (!checkdnsrr($domain, 'MX')) {
            return response()->json([
                'valid' => false,
                'reason' => 'Domain has no MX records'
            ]);
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
