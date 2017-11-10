<?php

namespace App\Http\Requests;

use App\Contact;
use Illuminate\Validation\Rule;
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
        $rules = [
            'name'              => 'required|max:255',
            'surname'           => 'required|max:255',
            'phone'             => 'required|numeric|max:9999999999',
            'customFields'      => 'nullable|array|max:5',
            'customFields.*'    => 'max:255'
        ];

        // Update on email validation with put requests, otherwise
        // just return the $rules
        switch ($this->method()) {
            case 'POST':
                return array_merge($rules, [
                    'email' => [
                        'required',
                        'email',
                        'max:255',
                        Rule::unique('contacts')
                            ->where('user_id', $this->user()->id)
                    ]
                ]);
                break;
            case 'PUT':
                return array_merge($rules, [
                    'email' => [
                        'required',
                        'email',
                        'max:255',
                        Rule::unique('contacts')
                            ->ignore($this->contact->id)
                            ->where('user_id', $this->user()->id)
                    ]
                ]);
                break;
            
            default:
                return $rules;
                break;
        }
    }
}
