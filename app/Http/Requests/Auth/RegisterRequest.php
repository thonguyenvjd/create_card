<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email'             => 'required|email|unique:users,email',
            'name'              => 'required',
            'password'          => 'required|min:8',
            'confirm_password'  => 'required|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'              => 'このメールアドレスは既に使用されています。',
            'email.email'               => '有効なメールアドレスを入力してください。',
            'email.required'            => 'メールアドレスを入力してください。',
            'name.required'             => '名前を入力してください。',
            'password.required'         => 'パスワードを入力してください。',
            'password.min'              => 'パスワードは8文字以上で入力してください。',
            'confirm_password.same'     => 'パスワードと確認用パスワードが一致しません。',
            'confirm_password.required' => '確認用パスワードを入力してください。',
        ];
    }
}
