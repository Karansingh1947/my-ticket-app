<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,inprogress,completed,onhold',
            'image' => 'nullable|image|max:2048', // Optional image upload
            'assigned_to' => 'nullable|exists:users,id',
        ];
    }

}
