<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdvancedValidationController extends Controller
{
    public function validateEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'valid' => false,
                'reason' => $e->validator->errors()->first('email')
            ], 422);
        }
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->input('email');
        $domain = substr(strrchr($email, "@"), 1);

        $validSyntax = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        $validMx = checkdnsrr($domain, 'MX');

        $smtpCheck = false;
        $reason = '';

        if ($validSyntax && $validMx) {
            // Obtener registro MX principal
            $mxRecords = [];
            getmxrr($domain, $mxRecords);
            $mxHost = $mxRecords[0] ?? null;

            if ($mxHost) {
                // Intentar conexión al servidor SMTP (puerto 25)
                $connection = @fsockopen($mxHost, 25, $errno, $errstr, 5);

                if ($connection) {
                    $from = 'kavotax@yandex.com';
                    $to = $email;

                    $this->getSmtpResponse($connection); // leer banner inicial

                    $this->sendSmtpCommand($connection, "HELO mi-api.com");
                    $this->getSmtpResponse($connection);

                    $this->sendSmtpCommand($connection, "MAIL FROM:<$from>");
                    $this->getSmtpResponse($connection);

                    $this->sendSmtpCommand($connection, "RCPT TO:<$to>");
                    $rcptResponse = $this->getSmtpResponse($connection);

                    if (strpos($rcptResponse, '250') === 0 || strpos($rcptResponse, '450') === 0) {
                        $smtpCheck = true;
                        $reason = 'SMTP check passed';
                    } else {
                        $reason = "SMTP check failed: $rcptResponse";
                    }

                    $this->sendSmtpCommand($connection, "QUIT");
                    fclose($connection);
                } else {
                    $reason = "SMTP check failed: could not connect to mail server ($errstr)";
                }
            } else {
                $reason = 'SMTP check failed: no MX hosts found';
            }
        } else {
            if (!$validSyntax) {
                $reason = 'Invalid email syntax';
            } elseif (!$validMx) {
                $reason = 'Domain has no MX records';
            }
        }

        return response()->json([
            'email' => $email,
            'valid_syntax' => $validSyntax,
            'valid_mx' => $validMx,
            'smtp_check' => $smtpCheck,
            'reason' => $reason
        ]);
    }

    private function sendSmtpCommand($connection, $command)
    {
        fputs($connection, $command . "\r\n");
    }

    private function getSmtpResponse($connection)
    {
        $data = '';
        while ($str = fgets($connection, 515)) {
            $data .= $str;
            if (substr($str, 3, 1) == ' ') { break; }
        }
        return trim($data);
    }
}
