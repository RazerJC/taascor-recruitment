<?php
return [
    'db' => [
        'url' => getenv('DATABASE_URL') ?: '',
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME') ?: 'taascor_recruitment2',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'port' => getenv('DB_PORT') ?: '5432',
    ],
    'site' => [
        'name' => 'TAASCOR Management & General Services Corp.',
        'logo_primary' => '/sitelogo-1.png',
        'logo_wordmark' => '/logo-le-reacteur.png',
    ],
    'companies' => [
        [
            'name' => 'SUMIDEN',
            'locations' => [
                'Ampere St. corner Main Ave., Light Industry and Science Park 1 (LISP 1), Barrio Diezmo, Cabuyao City, Laguna, 4025, Philippines',
            ],
        ],
        [
            'name' => 'FUJIFILM',
            'locations' => [
                'Unit A Winsouth 2 Building, #104 Lot 15 East Science Ave, Laguna Technopark, Binan, 4024 Laguna, Philippines',
            ],
        ],
        [
            'name' => 'SHINSEI',
            'locations' => [
                'Terelay Industrial Estate, Barangay Pittland, Cabuyao, Laguna, 4025 Philippines',
            ],
        ],
        [
            'name' => 'MULTIMIX',
            'locations' => [
                'Terelay Industrial Estate, Bo. Pittland, Provincial Rd, Cabuyao City, Laguna',
            ],
        ],
        [
            'name' => 'SHOPEE',
            'locations' => [
                'Batino Warehouse, Exit, Calamba, 4027 Laguna',
                'Fast Logistics, Brgy. Barandal, Calamba, Laguna',
                'San Jose Del Monte Hub, Key Tialo Bridge, SJDM, Bulacan',
                'Meycauayan Hub, Meycauayan, Bulacan',
                'Balagtas, Plaridel Bypass Rd, Plaridel Bulacan',
                'Bustos, Bulacan Near Liceo De Bethlehem',
            ],
        ],
        [
            'name' => 'LAZADA',
            'locations' => [
                'Diezmo Rd, Cabuyao',
                'San Antonio, San Pedro, Laguna',
                '#8 Muralla St, Meycauayan, Bulacan',
                'Washington Street, Talisay, 6045 Lalawigan ng Cebu',
            ],
        ],
        [
            'name' => 'LAZADA Rizal',
            'locations' => [
                '38 Provincial Road Beverly Hills Ave',
                '1264 Cableway Calumpang, Binangonan, Rizal',
                '7 Ritchie St, Santo Domingo, Cainta, 1900 Rizal',
                'Antipolo, Rizal',
                'Tanay, Rizal',
                'Manila E Rd, Taytay, 1920 Rizal',
            ],
        ],
        [
            'name' => 'OGICO',
            'locations' => [
                '10 Binary St., Light Industry & Science Park I-SEZ Brgy. Diezmo, Cabuyao, Laguna 4025',
            ],
        ],
        [
            'name' => 'CAI NIAO',
            'locations' => [
                'Calax Greenfield Toll Plaza, 4024 Biñan, Laguna',
            ],
        ],
        [
            'name' => 'LESLIES',
            'locations' => [
                'Calamba Plant: #108 Prosperity Ave., Carmelray Industrial Park 1, Brgy. Canlubang, Calamba, Laguna',
            ],
        ],
        [
            'name' => 'ABOITIZLAND',
            'locations' => [
                '23/F Ore Central Building, 9th cor. 31st Street, Bonifacio Global City, Taguig',
            ],
        ],
        [
            'name' => 'BI-CHAIN',
            'locations' => [
                'Fast Logistics Distriphil Silangan, Calamba, 4027 Laguna',
            ],
        ],
        [
            'name' => 'SIIX',
            'locations' => [
                'Carmelray Industrial Park 1, 108 Competence Dr, Calamba, 4028 Laguna',
            ],
        ],
        [
            'name' => 'MTC-TRANSPORT',
            'locations' => [
                'MTC Compound, Zone 1, Brgy. San Jose, Milaor, Camarines Sur, Philippines, 4413',
            ],
        ],
        [
            'name' => 'PRIME WORLWIDE PAPER PACKAGING CORP',
            'locations' => [
                '350 Tullahan Road, Sta Quiteria Caloocan City, Metro Manila',
            ],
        ],
        [
            'name' => 'WHISTLER STEEL',
            'locations' => [
                '39 National Highway, Barangay Tagapo, Sta. Rosa City, 4026 Laguna',
            ],
        ],
        [
            'name' => 'CENTRO MANUFACTURING CORP.',
            'locations' => [
                'Novaliches Plant (Plant 3 / Main Office): No. 2 Susano Road, Barrio Deparo, Novaliches, Caloocan City 1420, Metro Manila',
                'Marilao Plant (Plant 1): 91 M. Villarica Road, Loma de Gato, Marilao, Bulacan 3019',
                'Maguyam Plant (Plant 2): Lot 7, Block 5, Cavite Light Industrial Park (CLIP), Brgy. Maguyam, Silang, Cavite 4118',
            ],
        ],
        [
            'name' => 'CAVITE LIGHT INDUSTRIAL PARK',
            'locations' => [
                'Brgy. Maguyam, Silang, Cavite, Philippines, 4118',
            ],
        ],
        [
            'name' => 'GLOBAL MAXX',
            'locations' => [
                'Block 5, Lot 7, Cavite Light Industrial Park, Barangay Maguyam, Silang, Cavite, 4118 Philippines',
            ],
        ],
        [
            'name' => 'AUTO88 CORPORATION',
            'locations' => [
                'Lot 13 Block 5, Cavite Light Industrial Park, Maguyam, Silang, Cavite, 4118',
            ],
        ],
        [
            'name' => 'SEALED AIR',
            'locations' => [
                '2nd Floor, Universal Re Building, Paseo de Roxas, Legaspi Village, Makati City, Philippines',
            ],
        ],
        [
            'name' => 'WCL COLD STORAGE INC.',
            'locations' => [
                'Manila Harbour Centre, Blk 5 Lot 1, Tondo, Metro Manila, Philippines',
            ],
        ],
        [
            'name' => 'CYA INDUSTRIES INC.',
            'locations' => [
                '103 Mercedes Ave, Manila 1005 Metro Manila',
            ],
        ],
        [
            'name' => 'DELTA MILLING INDUSTRIES INC.',
            'locations' => [
                '102-104 E. Rodriguez Jr. Avenue, Ugong Norte, Libis, Quezon City, 1100/1800 Metro Manila, Philippines',
            ],
        ],
        [
            'name' => 'PASTURE 2 PLATE AGRIBUSINESS INC.',
            'locations' => [
                '1027 Sunrise Drive Brookside Hills, Cainta, Philippines',
            ],
        ],
        [
            'name' => 'EURO-MED LABORATORIES INC.',
            'locations' => [
                'Lot 4 Blk 7 Phase I, Welgao Bldg, First Cavite Industrial Estate (FCIE-SEZ), Brgy. Langkaan I, Dasmariñas, Cavite, Philippines',
            ],
        ],
        [
            'name' => 'YUANSHAN',
            'locations' => [
                'Lot 4 Blk 7 Phase I, First Cavite Industrial Estate (FCIE-SEZ), Brgy. Langkaan I, Dasmariñas, Cavite, Philippines',
            ],
        ],
    ],
];
