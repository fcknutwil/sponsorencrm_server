<?php

namespace ch\fcknutwil;


use ch\fcknutwil\api\model\DashboardEntry;
use org\maesi\DB;

class DashboardBuilder
{

    private $arr;

    private function __construct()
    {
        $res = DB::instance()->fetchRowMany(
            'select se.von, se.bis, e.betrag, e.seebli, e.zahlung from sponsor_engagement se inner join engagement e on se.fk_engagement = e.id');

        foreach ($res as $dbEntry) {
            $from = \DateTime::createFromFormat('Y-m-d', $dbEntry['von']);
            $fromYear = $from->format('Y');
            $to = \DateTime::createFromFormat('Y-m-d', $dbEntry['bis']);
            $toYear = $to->format('Y');

            if($fromYear == $toYear || $dbEntry['zahlung'] == 'onetime') {
                $this->add($fromYear, $dbEntry['betrag'], $dbEntry['seebli'], $dbEntry['zahlung']);
            } else {
                for($year = $fromYear; $year <= $toYear && $year <=2030; $year++) {
                    $betrag = $dbEntry['betrag'];
                    if($year == $fromYear) {
                        $daysOfYear = $this->countDaysOfYear($from);
                        $betrag = $dbEntry['betrag'] / $daysOfYear * ($daysOfYear - $from->format('z'));
                    } else if($year == $toYear) {
                        $daysOfYear = $this->countDaysOfYear($to);
                        $betrag = $dbEntry['betrag'] / $daysOfYear * $to->format('z');
                    }
                    $this->add($year, $betrag, $dbEntry['seebli'], $dbEntry['zahlung']);
                }
            }
        }
    }

    private function countDaysOfYear(\DateTime $date) {
        if($date->format('L')) {
            return 365;
        } else {
            return 364;
        }
    }

    private function add($year, $betrag, $seebli, $zahlung) {
        if(!array_key_exists($year, $this->arr)) {
            $this->arr[$year] = new DashboardEntry();
        }

        $this->arr[$year]->add($betrag, $seebli, $zahlung);
    }

    public static function build()
    {
        $builder = new DashboardBuilder();
        return $builder->arr;
    }
}