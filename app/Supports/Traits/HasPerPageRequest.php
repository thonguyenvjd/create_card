<?php

namespace App\Supports\Traits;

use Illuminate\Http\Request;

trait HasPerPageRequest
{
    /**
     * @return string|int|null
     */
    public function getPerPage()
    {
        $requestInstance = request();

        return $requestInstance instanceof Request
            ? $requestInstance->get('per_page')
            : null;
    }

    public function getOffset()
    {
        return (request('page', 1) - 1) * request('per_page', 50);
    }
}
