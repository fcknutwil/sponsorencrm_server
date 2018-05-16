<?php

namespace ch\fcknutwil;


use ch\fcknutwil\api\model\DashboardEntry;
use org\maesi\DB;

class DashboardBuilder
{
    public static $OVERVIEW = 1;
    public static $DETAIL = 2;

    private $type;
    private $year;
    private $result;

    private function __construct() {
        $this->type = self::$OVERVIEW;
    }

    public static function builder()
    {
        return new DashboardBuilder();
    }

    public function withType($type) {
        $this->type = $type;
        return $this;
    }

    public function withYear($year) {
        $this->year = $year;
        return $this;
    }

    public function build() {
        $this->validate();
        switch ($this->type) {
            case self::$OVERVIEW:
                $this->createOverview();
                break;
            case self::$DETAIL:
                $this->createDetail();
        }
        return $this->result;
    }

    private function validate() {
        if($this->type != self::$OVERVIEW && $this->type != self::$DETAIL) {
            throw new \Exception("Dashboard Type '$this->type' wird nicht unterstuetzt!" );
        }
        if($this->type == self::$DETAIL && (!is_numeric($this->year) || $this->year < 1900 || $this->year > 2099)) {
            throw new \Exception("Jahr '$this->year' ist ungueltig" );
        }
    }

    private function createOverview() {
        $res = DB::instance()->fetchRowMany(
            'select se.von, se.bis, e.betrag, e.seebli, e.zahlung from sponsor_engagement se inner join engagement e on se.fk_engagement = e.id');

        foreach ($res as $dbEntry) {
            $now = new \DateTime();
            $maxYear = $now->format('Y') + 3;
            $fromYear = \DateTime::createFromFormat('Y-m-d', $dbEntry['von'])->format('Y');
            $toYear = \DateTime::createFromFormat('Y-m-d', $dbEntry['bis'])->format('Y');

            if($fromYear == $toYear || $dbEntry['zahlung'] == 'onetime') {
                $this->add($fromYear, $dbEntry['betrag'], $dbEntry['seebli'], $dbEntry['zahlung']);
            } else {
                for($year = $fromYear; $year <= min($toYear, $maxYear); $year++) {
                    $betrag = $this->calculateProRata($year, $dbEntry['von'], $dbEntry['bis'], $dbEntry['betrag']);
                    $this->add($year, $betrag, $dbEntry['seebli'], $dbEntry['zahlung']);
                }
            }
        }
    }

    private function createDetail() {
        $res = DB::instance()->fetchRowMany("
              SELECT IF(s.typ='company', s.name, CONCAT(s.vorname, ' ', s.name)) AS sponsor, e.name AS engagement, e.betrag, e.zahlung, se.von, se.bis 
              FROM sponsor AS s 
              INNER JOIN sponsor_engagement AS se ON s.id=se.fk_sponsor 
              INNER JOIN engagement AS e ON se.fk_engagement=e.id 
              WHERE YEAR(se.von) <= :year AND YEAR(se.bis) >= :year 
              ORDER BY zahlung, sponsor
          ", ['year' => $this->year]);
        foreach ($res as &$entry) {
            $proRata = $this->calculateProRata($this->year, $entry['von'], $entry['bis'], $entry['betrag']);
            if($proRata != $entry['betrag']) {
                $entry['betragProRata'] = $proRata;
            }
        }
        $this->result = $res;
    }

    private function calculateProRata($year, $von, $bis, $betrag) {
        $from = \DateTime::createFromFormat('Y-m-d', $von);
        $to = \DateTime::createFromFormat('Y-m-d', $bis);
        if($year == $from->format('Y')) {
            $daysOfYear = $this->countDaysOfYear($from);
            $betrag = $betrag / $daysOfYear * ($daysOfYear - $from->format('z'));
        } else if($year == $toYear = $to->format('Y')) {
            $daysOfYear = $this->countDaysOfYear($to);
            $betrag = $betrag / $daysOfYear * $to->format('z');
        }
        return $betrag;
    }

    private function countDaysOfYear(\DateTime $date) {
        if($date->format('L')) {
            return 365;
        } else {
            return 364;
        }
    }

    private function add($year, $betrag, $seebli, $zahlung) {
        if(!array_key_exists($year, $this->result)) {
            $this->result[$year] = new DashboardEntry();
        }

        $this->result[$year]->add($betrag, $seebli, $zahlung);
    }
}