<?php

namespace App\Transformers;

use Flugg\Responder\TransformBuilder;
use Flugg\Responder\Transformers\Transformer;
use Laravel\Sanctum\NewAccessToken;

class TokenTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param NewAccessToken $token
     *
     * @return array
     */
    public function transform(NewAccessToken $token)
    {
        return [
            'access_token' => $token->plainTextToken,
            'user'         => app(TransformBuilder::class)
                ->resource($token->accessToken->tokenable, new UserTransformer())
                ->transform()['data'] ?? [],
        ];

        return $result;
    }
}
