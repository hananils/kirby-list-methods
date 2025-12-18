<?php

use Kirby\Cms\App as Kirby;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\Str;

Kirby::plugin('hananils/list-methods', [
    'translations' => [
        'en' => [
            'hananils.list-methods.conjunction' => 'and',
            'hananils.list-methods.since' => 'since'
        ],
        'de' => [
            'hananils.list-methods.conjunction' => 'und',
            'hananils.list-methods.since' => 'seit'
        ]
    ],
    'collectionMethods' => [
        'toList' => function ($conjunction = false) {
            return naturalList($this->toArray(), $conjunction);
        },
        'toNumericList' => function ($field = 'date', $period = true) {
            $numbers = [];

            foreach ($this as $item) {
                $numbers = $this->get($field, $item);

                foreach ($numbers as $index => $number) {
                    if (preg_match('/^\d{4}-\d{2}-\d{2}/', (string) $number)) {
                        $numbers[$index] = intval(
                            substr((string) $number, 0, 4)
                        );
                    } else {
                        $numbers[$index] = intval($number);
                    }
                }
            }
            return numericList($numbers, $period);
        }
    ],
    'fieldMethods' => [
        'toList' => function ($field, $conjunction = false, $link = null) {
            $data = Str::split($field->value);

            if ($link !== null) {
                $type = $field->parent()::CLASS_ALIAS;
                $linked = [];

                foreach ($data as $value) {
                    $href = Str::template($link, [
                        'kirby' => kirby(),
                        'site' => site(),
                        'page' => $type === 'page' ? $field->parent() : null,
                        'user' => $type === 'user' ? $field->parent() : null,
                        'file' => $type === 'file' ? $field->parent() : null,
                        'value' => $value
                    ]);

                    $linked[] = '<a href="' . $href . '">' . $value . '</a>';
                }

                $data = $linked;
            }

            return naturalList($data, $conjunction);
        },
        'toNumericList' => function ($field, $period = false, $link = null) {
            $numbers = Str::split($field->value);

            if ($link !== null) {
                $type = $field->parent()::CLASS_ALIAS;
                $linked = [];

                foreach ($numbers as $value) {
                    $href = Str::template($link, [
                        'kirby' => kirby(),
                        'site' => site(),
                        'page' => $type === 'page' ? $field->parent() : null,
                        'user' => $type === 'user' ? $field->parent() : null,
                        'file' => $type === 'file' ? $field->parent() : null,
                        'value' => $value
                    ]);

                    $linked[] = '<a href="' . $href . '">' . $value . '</a>';
                }

                $numbers = $linked;
            }

            return numericList($numbers, $period);
        }
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
                        'user' => $user
                    ]);

                    $data[] = '<a href="' . $href . '">' . $text . '</a>';
                } else {
                    $data[] = $text;
                }
            }

            return naturalList($data, $conjunction);
        }
    ],
    'userMethods' => [
        'asList' => function ($fields = [], $conjunction = false) {
            $data = [];

            foreach ($fields as $field) {
                $data[] = $this->content()->get($field)->value();
            }

            return naturalList($data, $conjunction);
        },
        'asNumericList' => function ($fields = [], $conjunction = false) {
            $data = [];

            foreach ($fields as $field) {
                $data[] = $this->content()->get($field)->value();
            }

            return numericList($data, $conjunction);
        }
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
                        'page' => $page
                    ]);

                    $data[] = '<a href="' . $href . '">' . $text . '</a>';
                } else {
                    $data[] = $text;
                }
            }

            return naturalList($data, $conjunction);
        }
    ],
    'pageMethods' => [
        'asList' => function ($fields = [], $conjunction = false) {
            $data = [];

            foreach ($fields as $field) {
                $data[] = $this->content()->get($field)->value();
            }

            return naturalList($data, $conjunction);
        },
        'asNumericList' => function ($fields = [], $conjunction = false) {
            $data = [];

            foreach ($fields as $field) {
                $data[] = $this->content()->get($field)->value();
            }

            return numericList($data, $conjunction);
        }
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
                        'file' => $file
                    ]);

                    $data[] = '<a href="' . $href . '">' . $text . '</a>';
                } else {
                    $data[] = $text;
                }
            }

            return naturalList($data, $conjunction);
        }
    ]
]);

function naturalList(
    $data,
    $conjunction = false,
    string $prefix = '',
    string $suffix = ''
) {
    // Remove empty items, trim values
    $data = array_map(function ($item) use ($prefix, $suffix) {
        $item = trim($item);

        if ($prefix && !str_starts_with($item, $prefix)) {
            $item = $prefix . $item;
        }

        if ($suffix && !str_starts_with($item, $suffix)) {
            $item = $item . $suffix;
        }

        return $item;
    }, array_filter($data));

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

function numericList($data, $period = true)
{
    $list = '';

    if (!is_array($data)) {
        return $list;
    }

    // Remove empty items, trim values
    $data = array_map('trim', array_filter($data));

    $numbers = [];
    foreach ($data as $value) {
        $numbers[] = intval(strip_tags($value));
    }

    ksort($numbers);

    if ($period === true) {
        $year = date('Y');

        if (kirby()->language()) {
            $locale = kirby()->language()->locale();
        } else {
            $locale = option('locale');
        }

        if ($year - count($numbers) + 1 === reset($numbers)) {
            return I18n::translate(
                'hananils.list-methods.since',
                'since',
                $locale
            ) .
                ' ' .
                end($data);
        }
    }

    foreach ($numbers as $index => $number) {
        $number = intval($number);
        $previous = null;
        $next = null;

        if (isset($numbers[$index - 1])) {
            $previous = intval($numbers[$index - 1]);
        }

        if (isset($numbers[$index + 1])) {
            $next = intval($numbers[$index + 1]);
        }

        if ($previous === $number - 1 && $next === $number + 1) {
            continue;
        }

        if ($previous && $previous !== $number - 1) {
            $list .= ', ';
        } elseif ($previous) {
            $list .= '–';
        }

        $list .= $data[$index];
    }

    return $list;
}
