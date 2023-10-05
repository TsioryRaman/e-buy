<?php

namespace App\Http\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{

    public function __construct(
        private bool $isDev,
        private string $manifest,
        private readonly RequestStack $requestStack
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset',[$this, 'asset'], ['is_safe'=>['html']])
        ];
    }

    public function asset(string $entry,?array $deps = []) {
        if($this->isDev){
            return $this->assetDev($entry,$deps);
        }
        return $this->assetProd($entry);
    }

    public function assetProd(string $entry): string {
        $data = json_decode(file_get_contents($this->manifest),true);
        $file = $data[$entry]['file'];
        $css = $data[$entry]['css'];
        // $imports = $data[$entry]['imports'];
        $html = <<<HTML
        <script type="module" src="/assets/{$file}" defer></script>
    HTML;
        foreach($css as $cssfile)
        {
            $html .= <<<HTML
        <link rel="stylesheet" media="screen" href="/assets/{$cssfile}" />
    HTML;
        }
        return $html;
    }

    public function assetDev(string $entry,?array $deps): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $uri = "http://{$request->getHost()}:3000";
        $html =  <<<HTML
<script type="module" src="${uri}/assets/@vite/client"></script>
HTML;
        if(in_array('react',$deps)){
            $html .= '<script type="module">
            import RefreshRuntime from '. $uri .'/assets/@react-refresh"
            RefreshRuntime.injectIntoGlobalHook(window)
            window.$RefreshReg$ = () => {}
            window.$RefreshSig$ = () => (type) => type
            window.__vite_plugin_react_preamble_installed__ = true
            </script>';
        }
        $html .= <<<HTML
        <script type="module" src="${uri}/assets/{$entry}" defer></script>
HTML;
        return $html;
    }
}