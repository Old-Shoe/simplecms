<?php

namespace Core;

use Smarty;
use SmartyException;

class Template
{
    private Smarty $smarty;

    public function __construct()
    {
        $this->smarty = new Smarty();
        $this->smarty->setCaching(true);
        $this->smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $this->smarty->setTemplateDir(SIMPLECMS_ROOT_DIR . '/web/templates');
        $this->smarty->setCacheDir(SIMPLECMS_ROOT_DIR . '/web/cache');
        $this->smarty->setConfigDir(SIMPLECMS_ROOT_DIR . '/web/config');
        $this->smarty->setCompileDir(SIMPLECMS_ROOT_DIR . '/web/compile');
        //$this->smarty->setPluginsDir();
        //$this->smarty->testInstall();
    }
    public function assign(array $data):void
    {
        foreach ($data as $key => $value)
        {
            $this->smarty->assign($key,$value);
        }
    }

    /**
     * @throws SmartyException
     */
    public function fetch(string $template): string|bool
    {
        return $this->smarty->fetch($template);
    }
}
