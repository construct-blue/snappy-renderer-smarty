<?php

declare(strict_types=1);

return fn(string $heading, string $paragraph) => <<<HTML
<h1>$heading</h1>
<p>$paragraph</p>
HTML;
