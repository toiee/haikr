<?php
namespace Toiee\haik\Plugins\Panel;

use Toiee\haik\Plugins\Plugin;

class PanelPlugin extends Plugin {
  
    /**
     * convert call via HaikMarkdown {#plugin-name}
     * @params array $params
     * @params string $body when :::\n...\n::: was set
     * @return string converted HTML string
     * @throws RuntimeException when unimplement
     */
    public function convert($params = array(), $body = '')
    {
        $base_class = 'panel';
        $prefix = $base_class.'-';
        $type = 'default';

        return '<div class="haik-plugin-panel '.$base_class.' '.$prefix.$type.'">'
             . '<div class="panel-body">'.$body.'</div></div>';
    }
}