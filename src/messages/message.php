<?php
return [
    'sourcePath' => __DIR__ . '/../',
    'messagePath' => __DIR__,
    'languages' => [
        'en',
    ],
    'translator' => 'Yii::t',
    'sort' => false,
    'overwrite' => true,
    'removeUnused' => false,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
        '/tests',
        '/vendor',
    ],
    'format' => 'php',
];
