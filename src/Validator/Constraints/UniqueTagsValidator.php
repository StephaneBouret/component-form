<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueTagsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $tagNames = [];

        foreach ($value as $element) {
            // Vous devriez accéder au champ "name" de l'élément, supposons qu'il s'appelle "name"
            $tagName = $element->getName();

            if (in_array($tagName, $tagNames, true)) {
                $this->context->buildViolation($constraint->message)
                    ->atPath('[tags]')
                    ->addViolation();

                // Pas besoin de continuer à valider une fois qu'une violation a été détectée
                return;
            }

            $tagNames[] = $tagName;
        }
    }
}
