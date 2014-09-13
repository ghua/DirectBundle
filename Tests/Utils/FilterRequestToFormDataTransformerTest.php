<?php

namespace Ext\DirectBundle\Tests\Utils;
use Ext\DirectBundle\Utils\FilterRequestToFormDataTransformer;

/**
 * Class FilterRequestToFormDataTransformerTest
 *
 * @package Ext\DirectBundle\Tests\Utils
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class FilterRequestToFormDataTransformerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function getFilter()
    {

        $collection = array('form_name' => array(
            'collection' => array('first' => 'first_value', 'second' => 'second_value')
        ));

        return array(
            array(
                array(
                array('property' => 'form_name[collection][first]', 'value' => 'first_value'),
                array('property' => 'form_name[collection][second]', 'value' => 'second_value'),
            ), $collection));
    }

    /**
     * @param array $filter
     * @param array $result
     *
     * @dataProvider getFilter
     */
    public function testTransform(array $filter, array $result)
    {
        $t = new FilterRequestToFormDataTransformer;
        $this->assertEquals($result, $t->transform($filter));
    }

}
