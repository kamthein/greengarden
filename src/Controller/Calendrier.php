<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Flux;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

class Calendrier
{
    public $days = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
    private $months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
    private ?int $month;
    private ?int $year;

    public function __construct(?int $month = null, ?int $year = null)
    {
        if($month === null)
        {
            $month = (int) date("m");
        }
        if($year === null)
        {
            $year = (int) date("Y");
        }
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * renvoie le premier jour du mois
     */
    public function getStartingDay(): DateTime {
        return new DateTime("{$this->year}-{$this->month}-01");
    }


    public function DatetoString() : string
    {
        return $this->months[$this->month - 1].' '.$this->year;
    }

    public function GetWeeks() : int
    {
        $start = new DateTime("{$this->year}-{$this->month}-01");
        $end = (clone $start)->modify('+ 1 month - 1 day');
        $weeks = (int) $end->format('W') - (int) $start->format('W')+1;
        if($weeks === null)
        {
            return (int) $start->format('W');
        }
        if($weeks<0)
        {
            return (int) $end->format('W')+1;
        }
        return $weeks;
    }




}