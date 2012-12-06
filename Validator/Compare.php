<?php
/**
 * This file is part of HopValidationBundle
 *
 * (c) Hoppinger BV <info@hoppinger.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hop\ValidationBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 */
class Compare extends Constraint
{
    const OP_GT  = 'gt';
    const OP_GTE = 'gte';
    const OP_LT  = 'lt';
    const OP_LTE = 'lte';
    const OP_EQ  = 'eq';
    const OP_NEQ = 'neq';

    public $field;
    public $compare_with;
    public $operator;
    public $message;

    public function __construct($options)
    {
        parent::__construct($options);

        if (!in_array($this->operator, $this->getValidOperators())) {
            throw new ConstraintDefinitionException(sprintf('The option "operator" must be one of %s', json_encode($this->getValidOperators())));
        }
        foreach (array('field', 'compare_with') as $property) {

            if (empty($this->{$property})) {
                throw new MissingOptionsException(sprintf('The option "%s" can\'t be empty', $property), array($property));
            }

            if (!is_scalar($this->{$property})) {
                throw new ConstraintDefinitionException(sprintf('The option "%s" must be scalar', $property));
            }
        }
    }

    /**
     * Returns all valid operators
     *
     * @return array
     */
    protected function getValidOperators()
    {
        return array(
            self::OP_GT,
            self::OP_GTE,
            self::OP_LT,
            self::OP_LTE,
            self::OP_EQ,
            self::OP_NEQ,
        );
    }

     /**
     * {@inheritDoc}
     */
    public function getRequiredOptions()
    {
        return array(
            'field',
            'compare_with',
            'operator',
            'message',
       );
    }

    /**
     * {@inheritDoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
