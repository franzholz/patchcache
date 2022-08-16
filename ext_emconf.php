<?php

########################################################################
# Extension Manager/Repository config file for ext: "patchcache"
########################################################################

$EM_CONF[$_EXTKEY] = [
    'title' => 'Patches for the Cache',
    'description' => 'Collection of patches for the cache and the link generation',
    'category' => 'fe',
    'author' => 'Franz Holzinger',
    'author_email' => 'franz@ttproducts.de',
    'state' => 'beta',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'author_company' => '',
    'version' => '0.1.3',
    'constraints' => [
        'depends' => [
            'php' => '7.0.0-7.99.99',
            'typo3' => '9.5.0-9.5.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
