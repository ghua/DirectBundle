<?php

namespace Ext\DirectBundle\Tests\Response;

use Ext\DirectBundle\Router\Rule;
use Ext\DirectBundle\Response\FormLoad;
use Symfony\Component\Form\FormView;

/**
 * Class FormLoadTest
 *
 * @package Ext\DirectBundle\Tests\Response
 */
class FormLoadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Ext\DirectBundle\Response\ResponseFactory
     */
    private $responseFactory;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $rule = new Rule('alias', 'AcmeBundle_TestController');

        $controllerResolver = $this->getMockBuilder('Ext\DirectBundle\Router\ControllerResolver')
            ->disableOriginalConstructor()
            ->getMock();
        $controllerResolver->expects($this->any())
            ->method('getCurrentRule')
            ->will($this->returnValue($rule));

        $responseFactory = $this->getMockBuilder('Ext\DirectBundle\Response\ResponseFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $responseFactory->expects($this->any())
            ->method('getResolver')
            ->will($this->returnValue($controllerResolver));


        $this->responseFactory = $responseFactory;
    }

    /**
     * @return array
     */
    public function getFormView()
    {
        $root = new FormView();
        $root->vars = array('full_name' => 'root', 'block_prefixes' => array('form'));
        $text = new FormView($root);
        $text->vars = array('full_name' => 'root[text]', 'value' => 'text', 'block_prefixes' => array('form', 'text'));

        $collection = new FormView($root);
        $collection->vars = array('full_name' => 'root[collection]', 'block_prefixes' => array('form', 'collection'));

        $first = new FormView($collection);
        $first->vars = array('full_name' => 'root[collection][0]', 'value' => 'first', 'block_prefixes' => array('form', 'text'));
        $second = new FormView($collection);
        $second->vars = array('full_name' => 'root[collection][1]', 'value' => 'second', 'block_prefixes' => array('form', 'text'));

        $collection->children = array($first, $second);

        $checkbox = new FormView($root);
        $checkbox->vars = array(
            'full_name' => 'root[checkbox]', 'value' => '1',
            'block_prefixes' => array('form', 'checkbox'),
            'checked' => false
        );

        $root->children = array($text, $collection, $checkbox);

        return array(array($root));
    }

    /**
     * @param FormView $view
     *
     * @dataProvider getFormView
     */
    public function testGetNamesAndValues(FormView $view)
    {
        $response = new FormLoad();
        $response->setFactory($this->responseFactory);
        $response->setContent($view);
        $response->extract();

        $data = $response->getData();

        $this->assertArrayHasKey('root[text]', $data);
        $this->assertEquals('text', $data['root[text]']);

        $this->assertArrayHasKey('root[collection][]', $data);
        $this->assertEquals(array('first', 'second'), $data['root[collection][]']);

        $this->assertArrayHasKey('root[checkbox]', $data);
        $this->assertEquals('0', $data['root[checkbox]']);

    }

}
