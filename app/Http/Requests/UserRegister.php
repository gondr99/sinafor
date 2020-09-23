<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegister extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'bail|required|email',  //if first validating is failed, second is ignore
            'password' => 'required|confirmed|min:8',
            'name' => 'required',
            'phone' =>'required|regex:/[0-9\-]+/i',
            'info' => 'required',
            'profile' => 'required|image|max:2048'
        ];

    }

    public function messages(){
        return [
            'email.required' => __('register.email_required'),
            'email.email' => __('register.email_type_error'),
            'password.required' => __('register.password_required'),
            'password.confirmed' => __('register.password_incorrect'),
            'password.min' => __('register.password_min'),
            'name.required' => __('register.name_required'),
            'phone.required' => __('register.phone_required'),
            'phone.regex' => __('register.phone_regex'),
            'info.required' => __('register.info_required'),
            'profile.required' => __('register.photo_required'),
            'profile.image' => __('register.photo_must_image'),
            'profile.max' => __('register.photo_must_2mb'),
        ];
    }
}
