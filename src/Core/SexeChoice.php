<?php

namespace App\Core;
interface SexeChoice
{
    const choice = [
        'Femme'=>'Femme',
        'Homme'=>'Homme',
        'Autre'=>'Autre',
        'Ne se prononce pas'=>'Ne se prononce pas'
    ];

    const heure = [
        '8H'=>'8H00',
        '9h'=>'9H00',
        '10H'=>'10H00',
        '11H'=>'11H00',
        '12H'=>'12H00',
        '13H'=>'13H00',
        '14H'=>'14H00',
        '15H'=>'15H00',
        '16H'=>'16H00',
        '17H'=>'17H00',
        '18H'=>'18H00',
    ];

    const reglee =[
        'Oui'=>1,
        'Non'=>0
    ];
}