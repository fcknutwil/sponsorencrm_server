<?php
namespace ch\fcknutwil;


use org\maesi\DB;

class SponsorEngagementAblauf
{

    public static function create() {
        $ids = DB::instance()->fetchColumnMany('
            SELECT se.id
            FROM sponsor_engagement AS se
            LEFT JOIN sponsor_engagement_ablauf AS sea ON se.id=sea.fk_sponsor_engagement
            WHERE se.bis < DATE_ADD(NOW(), INTERVAL 1 YEAR) AND sea.id IS NULL;
        ');
        foreach ($ids as $id) {
            $seaId = DB::instance()->insert('sponsor_engagement_ablauf', ["fk_sponsor_engagement" => $id]);
            echo "Neuer Eintrag (id=$seaId) für Spsonsor Engagement (id=$id) erstellt";
        }
    }
}