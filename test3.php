<?php
$p = \App\Models\Post::latest()->first();
var_dump($p->getTranslated('title'));
