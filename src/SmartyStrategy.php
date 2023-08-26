<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer\Strategy\Smarty;

use Blue\Snappy\Renderer\Helper\Arguments;
use Blue\Snappy\Renderer\Renderer;
use Blue\Snappy\Renderer\Strategy;
use Blue\Snappy\Renderer\Strategy\Smarty\Internal\SmartyInternalTemplate;
use Blue\Snappy\Renderer\Strategy\Smarty\Internal\SmartyResource;
use Smarty;

class SmartyStrategy extends Strategy\Base\PipelineStrategy
{
    private Smarty $smarty;

    protected function init(): void
    {
        parent::init();
        $this->smarty = new Smarty();
        $this->smarty->enableSecurity();
        $this->smarty->template_class = SmartyInternalTemplate::class;
        $this->smarty->registerResource(SmartyResource::NAME, new SmartyResource());
    }

    public function addTemplateDir(string $dir, string $key = null): self
    {
        $this->smarty->addTemplateDir($dir, $key);
        return $this;
    }

    public function execute($view, Renderer $renderer, $data): string
    {
        if ($view instanceof SmartyTemplate) {
            $template = new SmartyInternalTemplate(
                template_resource: 'file:' . $view->getFile(),
                smarty: $this->smarty,
                renderer: $renderer
            );
            $template->templateId = $this->smarty->_getTemplateId($template->template_resource);
            if ($data instanceof Arguments) {
                $template->assign((array)$data);
            }
            $template->assign($view->getVars());
            return $template->fetch();
        }
        return $this->next($view, $renderer, $data);
    }
}