<?php


namespace Payweb\IndexBundle\Direct\Utils;
use Symfony\Component\Form\FormTypeInterface;

/**
 * Class LoadGridOptions
 *
 * @package Payweb\IndexBundle\Direct\Utils
 */
class LoadGridOptions
{

    /**
     * @var FormTypeInterface
     */
    private $formType;

    /**
     * @var array
     */
    private $formOptions;

    /**
     * @var object
     */
    private $entity;

    /**
     * @var array
     */
    private $filter;

    /**
     * @var array
     */
    private $sort;

    /**
     * @var array
     */
    private $ignore;

}
