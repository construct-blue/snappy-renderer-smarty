<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer\Strategy\Smarty\Internal;

use Blue\Snappy\Renderer\Renderer;
use Smarty;
use Smarty_Internal_Data;
use Smarty_Internal_Template;

/**
 * @internal
 * @property SmartyInternalTemplate $parent
 */
class SmartyInternalTemplate extends Smarty_Internal_Template
{
    public function __construct(
        $template_resource,
        Smarty $smarty,
        Smarty_Internal_Data $_parent = null,
        $_cache_id = null,
        $_compile_id = null,
        $_caching = null,
        $_cache_lifetime = null,
        $_isConfig = false,
        private readonly ?Renderer $renderer = null,
    ) {
        parent::__construct(
            $this->inferTemplateResourceType($template_resource),
            $smarty,
            $_parent,
            $_cache_id,
            $_compile_id,
            $_caching,
            $_cache_lifetime,
            $_isConfig
        );
    }


    private function inferTemplateResourceType(string $templateResource): string
    {
        [$name, $type] = SmartyResource::parseResourceName($templateResource, $this->smarty->default_resource_type);

        if ($type !== $this->smarty->default_resource_type) {
            return $templateResource;
        }

        if (str_ends_with($name, '.php')) {
            return SmartyResource::NAME . ':' . $name;
        }

        if (str_ends_with($name, '.tpl')) {
            return 'file:' . $name;
        }

        return $name;
    }

    public function _subTemplateRender(
        $template,
        $cache_id,
        $compile_id,
        $caching,
        $cache_lifetime,
        $data,
        $scope,
        $forceTplCache,
        $uid = null,
        $content_func = null
    ): void {
        parent::_subTemplateRender(
            $this->inferTemplateResourceType($template),
            $cache_id,
            $compile_id,
            $caching,
            $cache_lifetime,
            $data,
            $scope,
            $forceTplCache,
            $uid,
            $content_func
        );
    }

    /**
     * @return Renderer|null
     */
    public function getRenderer(): ?Renderer
    {
        if (isset($this->renderer)) {
            return $this->renderer;
        }
        if ($this->parent instanceof SmartyInternalTemplate) {
            return $this->parent->getRenderer();
        }
        return null;
    }
}