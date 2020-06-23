<?php

@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Toolkit\Template;

Kirby::plugin('hananils/list-methods', [
    'translations' => [
        'en' => [
            'hananils.list-methods.conjunction' => 'and'
        ],
        'de' => [
            'hananils.list-methods.conjunction' => 'und'
        ]
    ],
    'usersMethods' => [
        'toList' => function ($field = 'username', $conjunction = false, $link = null) {
            $data = [];
            foreach ($this as $user) {
                if ($link !== null) {
                    $href = Str::template($link, [
                        'kirby' => kirby(),
                        'site' => site(),
                        'user' => $user
                    ]);

                    $data[] = Html::a($href, $user->$field());
                } else {
                    $data[] = $user->$field();
                }
            }

            return naturalList($data, $conjunction);
        }
    ],
    'pagesMethods' => [
        'toList' => function ($field = 'title', $conjunction = false, $link = null) {
            $data = [];
            foreach ($this as $page) {
                if ($link !== null) {
                    $href = Str::template($link, [
                        'kirby' => kirby(),
                        'site' => site(),
                        'page' => $page
                    ]);

                    $data[] = Html::a($href, $page->$field());
                } else {
                    $data[] = $page->$field();
                }
            }

            return naturalList($data, $conjunction);
        }
    ],
    'filesMethods' => [
        'toList' => function ($field = 'filename', $conjunction = false, $link = null) {
            $data = [];
            foreach ($this as $file) {
                if ($link !== null) {
                    $href = Str::template($link, [
                        'kirby' => kirby(),
                        'site' => site(),
                        'file' => $file
                    ]);

                    $data[] = Html::a($href, $file->$field());
                } else {
                    $data[] = $file->$field();
                }
            }

            return naturalList($data, $conjunction);
        }
    ]
]);

function naturalList($data, $conjunction = false)
{
    if ($conjunction === false) {
        return implode(', ', $data);
    }

    $last = array_pop($data);

    if ($data) {
        if ($conjunction === true) {
            $conjunction = ' ' . t('hananils.list-methods.conjunction') . ' ';
        }

        return implode(', ', $data) . $conjunction . $last;
    }

    return $last;
}
