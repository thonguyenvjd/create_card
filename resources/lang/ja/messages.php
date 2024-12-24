<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes'                  => [
        //Group
        'name'                    => 'リスト名',

        //Sentence
        'name_sentence'           => 'ネーム',
        'content_sentence'        => '内容',

        //Template
        'name_template'           => 'Myテンプレート名',

        //Recipient
        'name_recipients'          => '氏名',
        'situation'                => '状態',
        'email'                    => 'E-Mail',
    ],

    'unique'                      => '入力したリスト名は既に使用されています。別の名前に変更してください。',
    'required'                    => ':attributeを入力してください。',
    'regex'                       => ':attributeは正しい形式で入力してください。',
];
