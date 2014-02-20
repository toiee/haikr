<?php
namespace Toiee\haik\Link;

use Toiee\haik\Providers\SiteManagerInterface;

class PageResolver implements LinkResolverInterface
{

    protected $site_manager;

    public function __construct(SiteManagerInterface $site_manager)
    {
        $this->site_manager = $site_manager;
    }

    /**
     * return url
     * @params string $link page name or url or ...
     * @return string converted url
     * @throws LinkNotResolveException when page is not found
     */
    public function resolve($link)
    {
        if ($this->site_manager->pageExists($link))
        {
            return $this->site_manager->url($link);
        }

        throw new LinkNotResolveException;    
    }
}