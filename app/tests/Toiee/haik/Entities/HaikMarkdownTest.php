<?php
use Toiee\haik\Entities\HaikMarkdown;

class HaikMarkdownTest extends TestCase {

    public function setupPluginRepositoryInterface()
    {
        App::bind('PluginRepositoryInterface', function(){
            $mock = Mockery::mock('Toiee\haik\Repositories\PluginRepositoryInterface');
            $plugin = App::make('PluginInterface');
            $mock->shouldReceive('exists')
                 ->once()
                 ->andReturn(true);
            $mock->shouldReceive('load')
                 ->once()
                 ->andReturn($plugin);
            return $mock;
        });
    }
    
    public function testEmptyElementSuffix()
    {
        $parser = new HaikMarkdown;
        $this->assertEquals('>', $parser->empty_element_suffix);
    }
    
    public function testCodeClassPrefix()
    {
        $parser = new HaikMarkdown;
        $this->assertEquals('', $parser->code_class_prefix);
    }
    
    public function testDoHaikLinks()
    {
        $parser = new HaikMarkdown;

        $tests = array(
            'toppage' => array(
                'markdown' => '[[FrontPage]]',
                'assert'   => '<p><a href="http://localhost:8000" title="FrontPage">FrontPage</a></p>',
            ),
            'otherpage' => array(
                'markdown' => '[[Contact]]',
                'assert'   => '<p><a href="http://localhost:8000/Contact" title="Contact">Contact</a></p>',
            ),
            'toppage#hash' => array(
                'markdown' => '[[FrontPage#hash]]',
                'assert'   => '<p><a href="http://localhost:8000/#hash" title="FrontPage">FrontPage</a></p>',
            ),
            'otherpage#hash' => array(
                'markdown' => '[[Contact#hash]]',
                'assert'   => '<p><a href="http://localhost:8000/Contact#hash" title="Contact">Contact</a></p>',
            ),
            '>toppage' => array(
                'markdown' => '[[Top>FrontPage]]',
                'assert'   => '<p><a href="http://localhost:8000" title="Top">Top</a></p>',
            ),
            '>otherpage' => array(
                'markdown' => '[[Touch me!>Contact]]',
                'assert'   => '<p><a href="http://localhost:8000/Contact" title="Touch me!">Touch me!</a></p>',
            ),
            '#hashonly' => array(
                'markdown' => '[[#hash]]',
                'assert'   => '<p><a href="#hash">hash</a></p>',
            ),
            '>#hashonly' => array(
                'markdown' => '[[Alias>#hash]]',
                'assert'   => '<p><a href="#hash" title="Alias">Alias</a></p>',
            ),
            'url' => array(
                'markdown' => '[[http://www.google.com]]',
                'assert' => '<p><a href="http://www.google.com">http://www.google.com</a></p>',
            ),
            '>url' => array(
                'markdown' => '[[Google>http://www.google.com]]',
                'assert' => '<p><a href="http://www.google.com" title="Google">Google</a></p>',
            ),
        );
        
        foreach ($tests as $key => $data)
        {
            $this->assertEquals($data['assert'], trim($parser->transform($data['markdown'])));
        }
        
    }
    
    public function testCallInlinePluginsWithAllVariations()
    {
        $this->setupPluginRepositoryInterface();

        App::bind('PluginInterface', function(){
            $mock = Mockery::mock('Toiee\haik\Entities\PluginInterface');
            $mock->shouldReceive('inline')
                 ->andReturn('<span>inline plugin</span>');
            return $mock;
        });
    
        $parser = new HaikMarkdown;
        
        $tests = array(
            'plugin_name_only' => array(
                'markdown' => '&plugin;',
                'assert'   => '<p><span>inline plugin</span></p>',
            ),
            'plugin_name_and_params' => array(
                'markdown' => '&plugin(param1,param2);',
                'assert'   => '<p><span>inline plugin</span></p>',
            ),
            'plugin_name_and_body' => array(
                'markdown' => '&plugin{body};',
                'assert'   => '<p><span>inline plugin</span></p>',
            ),
            'plugin_name_and_params_and_body' => array(
                'markdown' => '&plugin(param1,param2){body};',
                'assert'   => '<p><span>inline plugin</span></p>',
            ),
        );
        
        foreach ($tests as $key => $data)
        {
            $this->assertEquals($data['assert'], trim($parser->transform($data['markdown'])));
        }
    }

    public function testCallInlinePluginWithParams()
    {
        $this->setupPluginRepositoryInterface();

        App::bind('PluginInterface', function(){
            $mock = Mockery::mock('Toiee\haik\Entities\PluginInterface');
            $mock->shouldReceive('inline')
                 ->with(array('param1','param2'), '')
                 ->andReturn('<span>inline plugin</span>');
            return $mock;
        });

        $parser = new HaikMarkdown;

        $markdown = '&plugin(param1,param2);';
        $assert   = '<p><span>inline plugin</span></p>';

        $this->assertEquals($assert, trim($parser->transform($markdown)));
    }

    public function testCallInlinePluginWithBody()
    {
        $this->setupPluginRepositoryInterface();

        App::bind('PluginInterface', function(){
            $mock = Mockery::mock('Toiee\haik\Entities\PluginInterface');
            $mock->shouldReceive('inline')
                 ->with(array(), 'body')
                 ->andReturn('<span>inline plugin</span>');
            return $mock;
        });

        $parser = new HaikMarkdown;

        $markdown = '&plugin{body};';
        $assert   = '<p><span>inline plugin</span></p>';

        $this->assertEquals($assert, trim($parser->transform($markdown)));
    }

    public function testCallInlinePluginWithParamsAndBody()
    {
        $this->setupPluginRepositoryInterface();

        App::bind('PluginInterface', function(){
            $mock = Mockery::mock('Toiee\haik\Entities\PluginInterface');
            $mock->shouldReceive('inline')
                 ->with(array('param1', 'param2'), 'body')
                 ->andReturn('<span>inline plugin</span>');
            return $mock;
        });

        $parser = new HaikMarkdown;

        $markdown = '&plugin(param1,param2){body};';
        $assert   = '<p><span>inline plugin</span></p>';

        $this->assertEquals($assert, trim($parser->transform($markdown)));
    }

    public function testCallInlinePluginWithParamsContainsDoubleQuotes()
    {
        $this->setupPluginRepositoryInterface();

        App::bind('PluginInterface', function(){
            $mock = Mockery::mock('Toiee\haik\Entities\PluginInterface');
            $mock->shouldReceive('inline')
                 ->with(array('param,1', 'param2,'), '')
                 ->andReturn('<span>inline plugin</span>');
            return $mock;
        });

        $parser = new HaikMarkdown;

        $markdown = '&plugin("param,1","param2,");';
        $assert   = '<p><span>inline plugin</span></p>';

        $this->assertEquals($assert, trim($parser->transform($markdown)));
    }

    public function testCallInlinePluginWithParamsContainsEscapedDoubleQuotes()
    {
        $this->setupPluginRepositoryInterface();

        App::bind('PluginInterface', function(){
            $mock = Mockery::mock('Toiee\haik\Entities\PluginInterface');
            $mock->shouldReceive('inline')
                 ->with(array('param"1"', 'param2'), '')
                 ->andReturn('<span>inline plugin</span>');
            return $mock;
        });

        $parser = new HaikMarkdown;

        $markdown = '&plugin("param""1""","param2");';
        $assert   = '<p><span>inline plugin</span></p>';

        $this->assertEquals($assert, trim($parser->transform($markdown)));

        $markdown = '&plugin(param"1",param2);';
        $assert   = '<p><span>inline plugin</span></p>';

        $this->assertEquals($assert, trim($parser->transform($markdown)));
    }

    // !TODO: 具体クラス（例：DecoPlugin）でテストする
/*
    public function testCallNestedInlinePlugins()
    {
        $this->setupPluginRepositoryInterface();

        App::bind('PluginInterface', function(){
            $mock = Mockery::mock('Toiee\haik\Entities\PluginInterface');
            $mock->shouldReceive('inline')
                 ->once()
                 ->with(array(), '<span>inline plugin</span>')
                 ->andReturn('<span>inline plugin</span>');
            return $mock;
        });

        $parser = new HaikMarkdown;

        $markdown = '&plugin{&plugin{body};};';
        $assert   = '<p><span>inline plugin</span></p>';

        $this->assertEquals($assert, trim($parser->transform($markdown)));
    }
*/
}