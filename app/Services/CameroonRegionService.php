<?php

namespace App\Services;

class CameroonRegionService
{
    public static function getRegions()
    {
        return [
            'Adamaoua' => 'Adamaoua',
            'Centre' => 'Centre',
            'Est' => 'Est',
            'Extrême-Nord' => 'Extrême-Nord',
            'Littoral' => 'Littoral',
            'Nord' => 'Nord',
            'Nord-Ouest' => 'Nord-Ouest',
            'Ouest' => 'Ouest',
            'Sud' => 'Sud',
            'Sud-Ouest' => 'Sud-Ouest'
        ];
    }

    public static function getDepartmentsByRegion()
    {
        return [
            'Adamaoua' => [
                'Djérem' => 'Djérem',
                'Faro-et-Déo' => 'Faro-et-Déo',
                'Mayo-Banyo' => 'Mayo-Banyo',
                'Mbéré' => 'Mbéré',
                'Vina' => 'Vina'
            ],
            'Centre' => [
                'Haute-Sanaga' => 'Haute-Sanaga',
                'Lékié' => 'Lékié',
                'Mbam-et-Inoubou' => 'Mbam-et-Inoubou',
                'Mbam-et-Kim' => 'Mbam-et-Kim',
                'Méfou-et-Afamba' => 'Méfou-et-Afamba',
                'Méfou-et-Akono' => 'Méfou-et-Akono',
                'Mfoundi' => 'Mfoundi',
                'Nyong-et-Kéllé' => 'Nyong-et-Kéllé',
                'Nyong-et-Mfoumou' => 'Nyong-et-Mfoumou',
                'Nyong-et-So\'o' => 'Nyong-et-So\'o'
            ],
            'Est' => [
                'Boumba-et-Ngoko' => 'Boumba-et-Ngoko',
                'Haut-Nyong' => 'Haut-Nyong',
                'Kadey' => 'Kadey',
                'Lom-et-Djérem' => 'Lom-et-Djérem'
            ],
            'Extrême-Nord' => [
                'Diamaré' => 'Diamaré',
                'Logone-et-Chari' => 'Logone-et-Chari',
                'Mayo-Danay' => 'Mayo-Danay',
                'Mayo-Kani' => 'Mayo-Kani',
                'Mayo-Sava' => 'Mayo-Sava',
                'Mayo-Tsanaga' => 'Mayo-Tsanaga'
            ],
            'Littoral' => [
                'Moungo' => 'Moungo',
                'Nkam' => 'Nkam',
                'Sanaga-Maritime' => 'Sanaga-Maritime',
                'Wouri' => 'Wouri'
            ],
            'Nord' => [
                'Bénoué' => 'Bénoué',
                'Faro' => 'Faro',
                'Mayo-Louti' => 'Mayo-Louti',
                'Mayo-Rey' => 'Mayo-Rey'
            ],
            'Nord-Ouest' => [
                'Boyo' => 'Boyo',
                'Bui' => 'Bui',
                'Donga-Mantung' => 'Donga-Mantung',
                'Menchum' => 'Menchum',
                'Mezam' => 'Mezam',
                'Momo' => 'Momo',
                'Ngo-Ketunjia' => 'Ngo-Ketunjia'
            ],
            'Ouest' => [
                'Bamboutos' => 'Bamboutos',
                'Haut-Nkam' => 'Haut-Nkam',
                'Hauts-Plateaux' => 'Hauts-Plateaux',
                'Koung-Khi' => 'Koung-Khi',
                'Menoua' => 'Menoua',
                'Mifi' => 'Mifi',
                'Mino' => 'Mino',
                'Ndé' => 'Ndé'
            ],
            'Sud' => [
                'Dja-et-Lobo' => 'Dja-et-Lobo',
                'Mvila' => 'Mvila',
                'Océan' => 'Océan',
                'Vallée-du-Ntem' => 'Vallée-du-Ntem'
            ],
            'Sud-Ouest' => [
                'Fako' => 'Fako',
                'Koupé-Manengouba' => 'Koupé-Manengouba',
                'Lebialem' => 'Lebialem',
                'Manyu' => 'Manyu',
                'Meme' => 'Meme',
                'Ndian' => 'Ndian'
            ]
        ];
    }

    public static function getDepartments($region = null)
    {
        $departments = self::getDepartmentsByRegion();

        if ($region && isset($departments[$region])) {
            return $departments[$region];
        }

        // Return all departments if no specific region
        $allDepartments = [];
        foreach ($departments as $regionDepts) {
            $allDepartments = array_merge($allDepartments, $regionDepts);
        }

        return $allDepartments;
    }
}