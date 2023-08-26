<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer\Strategy\Smarty\Internal;

use Blue\Snappy\Renderer\Helper\Arguments;
use Smarty_Internal_Resource_Php;
use Smarty_Internal_Template;
use Smarty_Template_Source;
use SmartyException;

/**
 * @internal
 */
class SmartyResource extends Smarty_Internal_Resource_Php
{
    public const NAME = 'renderer';

    public function renderUncompiled(Smarty_Template_Source $source, Smarty_Internal_Template $_template): void
    {
        if (!$source->exists) {
            throw new SmartyException(
                "Unable to load template '{$source->type}:{$source->name}'" .
                ($_template->_isSubTpl() ? " in '{$_template->parent->template_resource}'" : '')
            );
        }

        if ($_template instanceof SmartyInternalTemplate && $_template->getRenderer()) {
            echo $_template->getRenderer()->render(
                include $source->filepath,
                $_template->getRenderer()->args($_template->getTemplateVars())
            );
        }
    }
}