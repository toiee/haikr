<?php
namespace Toiee\haik\Themes;

class LocalRepository implements ThemeRepositoryInterface {

    protected $manager;

    /**
     * path to theme repository
     */
    protected $path;

    public function __construct(ThemeManager $manager, $path)
    {
        $this->path = $path;
    }

    /**
     * theme $name is exists?
     * @params string $name theme name
     * @return boolean
     */
    public function exists($name)
    {
        $theme_dir = $this->getPath($name);
        $config_path = $this->getPath($name) . '/config.php';
        if (is_dir($theme_dir) && file_exists($config_path))
        {
            return true;
        }
        return false;
    }

    /**
     * get Theme interface by theme name
     * @params string $name theme name
     * @return ThemeInterface
     * @throws InvalidArgumentException when $name was not exist
     */
    public function get($name)
    {
        if ($this->exists($name))
        {
            return new Theme($this->manager, $this->getConfig());
        }
        
        throw new \InvalidArgumentException("This {$name} theme was not exist");
    }

    /**
     * get all plugin list
     * @return array of plugin id
     */
    public function getAll()
    {
        return array();
    }
    
    public function getPath($name)
    {
        return str_finish($this->path, '/') . $name;
    }

    public function getConfig($name)
    {
        $config = array();
        $config_path = $this->getPath($name) . '/config.php';
        if ($this->exists($name))
        {
            $config = include($config_path);
        }
        if (array_key_exists('name', $config))
        {
            $config['name'] = $name;
        }
        return $this->parseConfig($config);
    }

    public function parseConfig($config)
    {
        return $config;
    }
}