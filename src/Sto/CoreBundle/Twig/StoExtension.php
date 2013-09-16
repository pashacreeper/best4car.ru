<?php

namespace Sto\CoreBundle\Twig;

class StoExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'sto_extension';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('url_to_local', array($this, 'urlToLocalFilter')),
            new \Twig_SimpleFilter('working_time_days_number_to_array', array($this, 'workingTimeDaysNumberToArray')),
            new \Twig_SimpleFilter('working_time_days_array_to_string', array($this, 'workingTimeDaysArrayToString')),
        );
    }

    public function urlToLocalFilter($url)
    {
        return str_replace(['https://', 'http://', $_SERVER['HTTP_HOST']], '', $url);
    }

    public function workingTimeDaysNumberToArray($days)
    {
        $response = [];
        foreach ([1, 2, 4, 8, 16, 32, 64] as $day) {
            $response[] = ($day & $days) > 0;
        }

        return $response;
    }

    public function workingTimeDaysArrayToString($days)
    {
        $labels = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
        $result = '';

        foreach ($days as $key => $value) {
            if (!$value) {
                continue;
            }
            $previousKey = $key - 1;
            $nextKey = $key + 1;
            if (!isset($days[$previousKey]) || !$days[$previousKey]) {
                if (!empty($result)) {
                    $result .= ', ';
                }
                $result .= $labels[$key];
            } elseif (isset($days[$nextKey]) && !$days[$nextKey]) {
                $result .= ' - ' . $labels[$key];
            }
        }

        return $result;
    }
}
