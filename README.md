# HopCompareConstraintBundle

Symfony2 bundle which adds a validator constraint to compare two properties.

(For now only compatible with Symfony 2.1+)

## Installation

The recommended way to install this bundle is with [Composer](http://getcomposer.org/). 
Just add `hoppinger/compare-constraint-bundle` to your composer.json by running:

``` bash
$ php composer.phar require hoppinger/compare-constraint-bundle
```

Now you only need to enable the bundle in your `AppKernel.php`:

``` php
// app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
        /* ... */
            new Hop\CompareConstraintBundle\HopCompareConstraintBundle(),
        /* ... */    
        );
	}

```

## Usage

You can use the constraint just like every other [Symfony constraint](http://symfony.com/doc/current/book/validation.html#constraints).

### Using annotations

``` php
// src/Acme/DemoBundle/Entity/Foo.php

/**
 * @Hop\CompareConstraintBundle\Validator\Compare(field="bar",compare_with="crux",operator="gt",message="Bar should be greater than crux")
 */
class Foo
{
	protected $bar;
	
	protected $crux;
	
	public function getBar()
	{
		return $this->bar;
	}
	
	public function getCrux()
	{
		return $this->crux;
	}
	
}
```

### Using YML

``` yaml
# src/Acme/DemoBundle/Resources/config/validation.yml
Acme\DemoBundle\Entity\Foo:
    constraints:
        - Hop\CompareConstraintBundle\Validator\Compare:
            field: bar
            compare_with: crux
            operator: gt
            message: Bar should be greater than crux
            
```           

## Reference

### Options

The `Compare` constraint takes 4 arguments, of which all are required.

 * `field` 

The field name of your object which is compared.  
  
 * `compare_with`

The field name of your object with which `field` is compared with.

 * `operator`

The operator to use. See below.

 * `message`
 
The message that will be shown if the constraint doesn't validate.    

### Valid operators

 * Compare::OP_GT (`gt`)

Constraint matches if `field` is `greater than` `compare_with`
 
 * Compare::OP_GTE (`gte`)
 
Constraint matches if `field` is `greater than or equal to` `compare_with`
 
 * Compare::OP_LT (`lt`)
 
Constraint matches if `field` is `less than ` `compare_with`
 
 * Compare::OP_LTE (`lte`)
 
Constraint matches if `field` is `less than or equal to` `compare_with`
 
 * Compare::OP_EQ (`eq`)
 
Constraint matches if `field` is `equal to` `compare_with`
 
 * Compare::OP_NEQ (`neq`)
 
Constraint matches if `field` is `not equal to` `compare_with`

## License

HopCompareConstraintBundle is licensed under the MIT license.
 