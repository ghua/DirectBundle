<?php

namespace Payweb\IndexBundle\Direct\Utils;

/**
 * Class FilterRequestToFormData
 *
 * @package Payweb\IndexBundle\Direct
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class FilterRequestToFormDataTransformer
{

    private $filterKeys = array('property', 'value');

    const PARTS_REGEXP = '/[\w\d_]+/';

    /**
     * @param array $filter
     *
     * @return array
     */
    public function transform($filter)
    {
        $result = array();

        foreach ($filter as $rule) {
            $this->checkFilterKeys($rule);
            $value = $rule['value'];

            if (is_string($value) and in_array($value, array("true", "false"))) {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }

            if (preg_match_all(self::PARTS_REGEXP, $rule['property'], $parts)) {
                $parts = $parts[0];
                foreach ($parts as $part) {
                    $value = array(array_pop($parts) => $value);
                }
            }

            $result = array_merge_recursive($result, $value);
        }

        return $result;
    }



    /**
     * @param array $rule
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    private function checkFilterKeys(array $rule)
    {
        $diff = array_diff($this->filterKeys, array_keys($rule));
        if (!empty($diff)) {
            throw new \InvalidArgumentException('The request does not contains a required keys: ' . implode(', ', $diff));
        }

        return true;
    }

}
