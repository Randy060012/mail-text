<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact');
    }

    public function sendEmail(Request $request)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'msg' => 'required|string',
        ]);

        // Préparer les détails de l'e-mail
        $details = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'msg' => $validated['msg'],
        ];

        // Tentative d'envoi de l'e-mail
        try {
            // Logique pour envoyer l'e-mail ou traiter le formulaire
            SendEmailJob::dispatch($details);
            return response()->json(['success' => 'Email envoyé avec succès!']);
        } catch (\Exception $e) {
            // Enregistrement de l'erreur dans les logs pour le diagnostic
            Log::error('Erreur lors de l\'envoi de l\'e-mail: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue. Veuillez réessayer.'], 500);
        }
    }

}
