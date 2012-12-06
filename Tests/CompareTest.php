<?php
/**
 * This file is part of HopCompareConstraintBundle
 *
 * (c) Hoppinger BV <info@hoppinger.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hop\CompareConstraintBundle\Tests;

use Hop\CompareConstraintBundle\Validator\Compare;
use Hop\CompareConstraintBundle\Validator\CompareValidator;

class CompareValidatorTest_Class
{
    public function getOne()
    {
        return 1;
    }

    public function getTwo()
    {
        return 2;
    }

    public function getNull()
    {
        return null;
    }
}

class CompareTest extends \PHPUnit_Framework_TestCase
{
    protected $context;
    protected $validator;

    protected function setUp()
    {
        $this->context   = $this->getMock('Symfony\Component\Validator\ExecutionContext', array(), array(), '', false);
        $this->validator = new CompareValidator();
        $this->validator->initialize($this->context);
    }

    protected function tearDown()
    {
        $this->context   = null;
        $this->validator = null;
    }

    public function testNullIsValid()
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate(null, new Compare(array(
            'field'        => 'foo',
            'compare_with' => 'bar',
            'operator'     => Compare::OP_EQ,
            'message'      => 'Foo',
        )));
    }

    /**
     * @dataProvider getValidOperatorTests
     */
    public function testOperatorsValid(array $options)
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate(new CompareValidatorTest_Class(), new Compare($options));
    }

    /**
     * @expectedException Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidOperatorException()
    {
       $this->validator->validate(null, new Compare(array(
            'field'        => 'foo',
            'compare_with' => 'bar',
            'operator'     => 'foo_bar',
            'message'      => 'Foo',
       )));
    }

    /**
     * @dataProvider getValuesNullValidTests
     */
    public function testValuesNullValid(array $options)
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate(new CompareValidatorTest_Class(), new Compare($options));
    }

    public function testMessageIsSet()
    {
        $this->context->expects($this->once())
            ->method('addViolation')
            ->with('Foo')
           ;

        $this->validator->validate(new CompareValidatorTest_Class(), new Compare(array(
            'field'        => 'one',
            'compare_with' => 'two',
            'operator'     => Compare::OP_GT,
            'message'      => 'Foo',
        )));
    }

    /**
     * @dataProvider getInvalidOperatorTests
     */
    public function testOperatorsInvalid(array $options)
    {
        $this->context->expects($this->once())
            ->method('addViolation');

        $this->validator->validate(new CompareValidatorTest_Class(), new Compare($options));
    }

    /**
     * @expectedException Symfony\Component\Validator\Exception\MissingOptionsException
     * @dataProvider getInvalidFieldDefinitionEmptyTests
     */
    public function testInvalidFieldDefinitionEmptyException(array $options)
    {
        new Compare($options);
    }

    /**
     * @expectedException Symfony\Component\Validator\Exception\ConstraintDefinitionException
     * @dataProvider getInvalidFieldDefinitionScalerTests
     */
    public function testInvalidFieldDefinitionScalerException(array $options)
    {
        new Compare($options);
    }

    public function getInvalidFieldDefinitionScalerTests()
    {
        return array(
            array(array(
                'field'        => array('foo'),
                'compare_with' => 'bar',
                'operator'     => Compare::OP_EQ,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'bar',
                'compare_with' => array('foo'),
                'operator'     => Compare::OP_EQ,
                'message'      => 'Foo',
            )),
        );
    }

    public function getInvalidFieldDefinitionEmptyTests()
    {
         return array(
            array(array(
                'field'        => '',
                'compare_with' => 'bar',
                'operator'     => Compare::OP_EQ,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'bar',
                'compare_with' => '',
                'operator'     => Compare::OP_EQ,
                'message'      => 'Foo',
            )),
        );
    }

    public function getInvalidOperatorTests()
    {
        return array(
            array(array(
                'field'        => 'one',
                'compare_with' => 'two',
                'operator'     => Compare::OP_GT,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'one',
                'compare_with' => 'one',
                'operator'     => Compare::OP_GT,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'one',
                'compare_with' => 'two',
                'operator'     => Compare::OP_GTE,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'two',
                'compare_with' => 'one',
                'operator'     => Compare::OP_LT,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'one',
                'compare_with' => 'one',
                'operator'     => Compare::OP_LT,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'two',
                'compare_with' => 'one',
                'operator'     => Compare::OP_LTE,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'one',
                'compare_with' => 'two',
                'operator'     => Compare::OP_EQ,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'one',
                'compare_with' => 'one',
                'operator'     => Compare::OP_NEQ,
                'message'      => 'Foo',
            )),
        );
    }

    public function getValidOperatorTests()
    {
        return array(
            array(array(
                'field'        => 'two',
                'compare_with' => 'one',
                'operator'     => Compare::OP_GT,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'two',
                'compare_with' => 'one',
                'operator'     => Compare::OP_GTE,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'one',
                'compare_with' => 'one',
                'operator'     => Compare::OP_GTE,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'one',
                'compare_with' => 'two',
                'operator'     => Compare::OP_LT,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'one',
                'compare_with' => 'two',
                'operator'     => Compare::OP_LTE,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'one',
                'compare_with' => 'one',
                'operator'     => Compare::OP_LTE,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'one',
                'compare_with' => 'one',
                'operator'     => Compare::OP_EQ,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'one',
                'compare_with' => 'two',
                'operator'     => Compare::OP_NEQ,
                'message'      => 'Foo',
            )),
        );
    }

    public function getValuesNullValidTests()
    {
        return array(
            array(array(
                'field'        => 'null',
                'compare_with' => 'one',
                'operator'     => Compare::OP_EQ,
                'message'      => 'Foo',
            )),
            array(array(
                'field'        => 'one',
                'compare_with' => 'null',
                'operator'     => Compare::OP_EQ,
                'message'      => 'Foo',
            )),
        );
    }
}
