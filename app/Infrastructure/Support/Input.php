<?php

declare(strict_types=1);

namespace App\Infrastructure\Support;

use Hyperf\Validation\Request\FormRequest;

/**
 * @see https://hyperf.wiki/3.1/#/en/validation?id=form-request-validation
 */
class Input extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
