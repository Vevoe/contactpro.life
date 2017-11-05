<?php

namespace App\Http\Requests;

use App\Contact;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Auth is handled with route auth and ContactPolicy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              => 'required|max:255',
            'surname'           => 'required|max:255',
            'email'             => 'required|email|max:255',
            'phone'             => 'required|max:255',
            'customFields'      => 'nullable|array|max:5',
            'customFields.*'    => 'max:255'
        ];
    }
}
