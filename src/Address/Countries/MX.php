<?php

namespace Angle\Utilities\Address\Countries;

use Angle\Utilities\Address\Country;

class MX extends Country
{
    /**
     * @inheritDoc
     *
     * ISO 3166-2:MX is the entry for Mexico in ISO 3166-2, part of the ISO 3166 standard published by the International Organization for Standardization (ISO),
     * which defines codes for the names of the principal subdivisions (e.g., provinces or states) of all countries coded in ISO 3166-1.
     * Currently for Mexico, ISO 3166-2 codes are defined for 31 states and 1 capital. The capital of the country Mexico City has special status equal to the states.
     * Each code consists of two parts, separated by a hyphen. The first part is MX, the ISO 3166-1 alpha-2 code of Mexico. The second part is three letters.
     *
     * @var array
     */
    protected static $regions = array(
        'MX-CMX' => 'Ciudad de México',
        'MX-AGU' => 'Aguascalientes',
        'MX-BCN' => 'Baja California',
        'MX-BCS' => 'Baja California Sur',
        'MX-CAM' => 'Campeche',
        'MX-CHP' => 'Chiapas',
        'MX-CHH' => 'Chihuahua',
        'MX-COA' => 'Coahuila',
        'MX-COL' => 'Colima',
        'MX-DIF' => 'Distrito Federal', // deprecated: substituted by MX-CMX
        'MX-DUR' => 'Durango',
        'MX-GUA' => 'Guanajuato',
        'MX-GRO' => 'Guerrero',
        'MX-HID' => 'Hidalgo',
        'MX-JAL' => 'Jalisco',
        'MX-MEX' => 'Estado de México',
        'MX-MIC' => 'Michoacán',
        'MX-MOR' => 'Morelos',
        'MX-NAY' => 'Nayarit',
        'MX-NLE' => 'Nuevo León',
        'MX-OAX' => 'Oaxaca',
        'MX-PUE' => 'Puebla',
        'MX-QUE' => 'Querétaro',
        'MX-ROO' => 'Quintana Roo',
        'MX-SLP' => 'San Luis Potosí',
        'MX-SIN' => 'Sinaloa',
        'MX-SON' => 'Sonora',
        'MX-TAB' => 'Tabasco',
        'MX-TAM' => 'Tamaulipas',
        'MX-TLA' => 'Tlaxcala',
        'MX-VER' => 'Veracruz',
        'MX-YUC' => 'Yucatán',
        'MX-ZAC' => 'Zacatecas',
    );
}