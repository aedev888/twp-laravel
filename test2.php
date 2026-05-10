<?php
$p = \App\Models\Post::latest()->first();
$key = 'title';
$locale = app()->getLocale();
$v1 = $p->getTranslation($key, $locale, false);
$v2 = $p->getTranslation($key, 'en', false);
$v3 = array_values($p->getTranslations($key))[0] ?? null;
var_dump(['locale' => $locale, 'v1' => $v1, 'v2' => $v2, 'v3' => $v3]);
