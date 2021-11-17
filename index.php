<?php

use Kirby\Toolkit\Template;

Kirby::plugin('hananils/list-methods', [
    'translations' => [
        'en' => [
            'hananils.list-methods.conjunction' => 'and',
        ],
        'de' => [
            'hananils.list-methods.conjunction' => 'und',
        ],
    ],
    'collectionMethods' => [
        'toList' => function ($conjunction = false) {
            return naturalList($this->toArray(), $conjunction);
        },
    ],
    'usersMethods' => [
        'toList' => function (
            $field = 'username',
            $conjunction = false,
            $link = null,
            $method = null
        ) {
            $data = [];

            foreach ($this as $user) {
                $text = $user->$field();

                if ($method !== null && method_exists($text, $method)) {
                    $text = $text->$method();
                }

                if ($link !== null) {
                    $href = Str::template($link, [
                        'kirby' => kirby(),
                        'site' => site(),
                        'user' => $user,
                    ]);

                    $data[] = '<a href="' . $href . '">' . $text . '</a>';
                } else {
                    $data[] = $text;
                }
            }

            return naturalList($data, $conjunction);
        },
    ],
    'pagesMethods' => [
        'toList' => function (
            $field = 'title',
            $conjunction = false,
            $link = null,
            $method = null
        ) {
            $data = [];

            foreach ($this as $page) {
                $text = $page->$field();

                if ($method !== null && method_exists($text, $method)) {
                    $text = $text->$method();
                }

                if ($link !== null) {
                    $href = Str::template($link, [
                        'kirby' => kirby(),
                        'site' => site(),
                        'page' => $page,
                    ]);

                    $data[] = '<a href="' . $href . '">' . $text . '</a>';
                } else {
                    $data[] = $text;
                }
            }

            return naturalList($data, $conjunction);
        },
    ],
    'filesMethods' => [
        'toList' => function (
            $field = 'filename',
            $conjunction = false,
            $link = null,
            $method = null
        ) {
            $data = [];

            foreach ($this as $file) {
                $text = $file->$field();

                if ($method !== null) {
                    $text = $text->$method();
                }

                if ($link !== null) {
                    $href = Str::template($link, [
                        'kirby' => kirby(),
                        'site' => site(),
                        'file' => $file,
                    ]);

                    $data[] = '<a href="' . $href . '">' . $text . '</a>';
                } else {
                    $data[] = $text;
                }
            }

            return naturalList($data, $conjunction);
        },
    ],
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
