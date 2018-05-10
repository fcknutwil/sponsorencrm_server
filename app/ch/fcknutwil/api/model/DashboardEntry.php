<?php
namespace ch\fcknutwil\api\model;

class DashboardEntry implements \JsonSerializable
{

    private $fck;
    private $seebliPlus;

    public function __construct()
    {
        $this->fck->annual = 0;
        $this->fck->onetime = 0;
        $this->seebliPlus->annual = 0;
        $this->seebliPlus->onetime = 0;
    }

    public function add($value, bool $seebli, $payment)
    {
        $fld = $seebli ? 'seebliPlus' : 'fck';
        $this->$fld->$payment += $value;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}