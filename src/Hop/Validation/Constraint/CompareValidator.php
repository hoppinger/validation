<?php
/**
 * This file is part of hoppinger/validation
 *
 * (c) Hoppinger BV <info@hoppinger.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hop\Validation\Constraint;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Form\Util\PropertyPath;

class CompareValidator extends ConstraintValidator
{
    /**
     * {@inheritDoc}
     */
    public function validate($object, Constraint $constraint)
    {
        if (null === $object) {
            return;
        }

        $value   = $this->getValue($object, $constraint->field);

        $compare = $this->getValue($object, $constraint->compare_with);

        if (null === $value || null === $compare) {
            return;
        }

        switch ($constraint->operator) {
            case $constraint::OP_GT:
                $b = $value > $compare;
                break;
            case $constraint::OP_GTE:
                $b = $value >= $compare;
                break;
            case $constraint::OP_LT:
                $b = $value < $compare;
                break;
            case $constraint::OP_LTE:
                $b = $value <= $compare;
                break;
            case $constraint::OP_EQ:
                $b = $value == $compare;
                break;
            case $constraint::OP_NEQ:
                $b = $value != $compare;
                break;
        }

        if (false === $b) {
            $this->context->addViolationAtSubPath($constraint->field, $constraint->message);
        }
    }

    protected function getValue($object, $field)
    {
        $path = new PropertyPath($field);

        return $path->getValue($object);
    }
}
