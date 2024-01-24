<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueTags extends Constraint
{
    public string $message = 'Vous ne pouvez pas sélectionner le même tag !';

    public function __construct(string $message = null)
    {
        parent::__construct($message);
        $this->message = $message ?? $this->message;
    }

    public function validatedBy()
    {
        return static::class . 'Validator';
    }
}
