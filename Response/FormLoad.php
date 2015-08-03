<?php

namespace Ext\DirectBundle\Response;

use Symfony\Component\Form\FormView;

/**
 * Class FormLoad
 *
 * @package Ext\DirectBundle\Response
 */
class FormLoad extends Response
{

    const ARRAY_REGEX = '/^(.+)\[\d+\]$/';

    /**
     * @param FormView $view
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setContent($view)
    {
        if (!($view instanceof FormView)) {
            throw new \InvalidArgumentException('setContent($view) must be instance of FormView');
        }

        $this->setData(
            $this->compileResult(
                $this->getNamesAndValues($view)
            )
        );

        return $this;
    }

    /**
     * @param FormView $view
     *
     * @return array
     */
    private function getNamesAndValues(FormView $view)
    {
        $result = array();
        foreach ($view as $child) {
            $hasChildren = 0 < count($child);
            if ($hasChildren) {
                $result = array_merge($result, $this->getNamesAndValues($child));
            } else {
                $value = $child->vars['value'];

                if (in_array('checkbox', $child->vars['block_prefixes']) && !$child->vars['checked']) {
                    $value = 0;
                }

                if (is_string($value) && preg_match('/^[1-9]\d*$/', $value)) {
                    $value = (int) $value;
                }

                if (is_array($value)) {
                    $value = array_map(function($v) {
                        if (is_string($v) && preg_match('/^[1-9]\d*$/', $v)) {
                            return (int) $v;
                        }

                        return $v;
                    }, $value);
                }

                $result[$child->vars['full_name']] = $value;
            }
        }

        return $result;
    }

    /**
     * @param array $result
     *
     * @return array
     */
    protected function compileResult($result)
    {
        foreach ($result as $key => $value) {
            if (preg_match(self::ARRAY_REGEX, $key, $match) && count($match) === 2) {
                $newKey = $match[1] . '[]';

                if (!isset($result[$newKey])) {
                    $result[$newKey] = array($value);
                } else {
                    $result[$newKey][] = $value;
                }

                unset($result[$key]);
            }
        }

        return $result;
    }

}
