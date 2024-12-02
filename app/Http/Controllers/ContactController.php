<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // Método para almacenar un comentario
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'comment' => 'required|string',
            'email' => 'required|email', // Validar que el email sea correcto y no nulo
        ]);

        // Crear un nuevo comentario con los datos validados
        $contact = Contact::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $contact,
        ], 201);
    }

    // Método para obtener un comentario específico por ID
    public function getContact($id)
    {
        $contact = Contact::find($id); // Buscar el comentario por ID

        if (!$contact) {
            return response()->json([
                'status' => 'error',
                'message' => 'Comentario no encontrado',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $contact,
        ], 200);
    }

    // Método para obtener todos los comentarios
    public function getContacts()
    {
        $contacts = Contact::all(); // Obtener todos los comentarios

        return response()->json([
            'status' => 'success',
            'data' => $contacts,
        ], 200);
    }
}