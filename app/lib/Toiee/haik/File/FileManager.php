<?php
namespace Toiee\haik\File;

class FileManager {

    const IDENTIFIER_REGEX = '/\A[0-9a-zA-Z][0-9a-zA-Z_-]*[0-9a-zA-Z]\z/';
    const AUTO_IDENTIFIER_SEED = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const AUTO_IDENTIFIER_MIN_LENGTH = 4;
    const AUTO_IDENTIFIER_TRY_LIMIT = 10000;

    /**
     * FileRepositoryInterface
     */
    protected $files;

    /**
     * Available storage drivers
     */
    protected $storageDrivers = array();

    /**
     * Instance of last saved
     */
    protected $lastSaved = null;


    public function __construct(FileRepositoryInterface $repository)
    {
        $this->files = $repository;
    }

    public function listGet($page = 1)
    {
        return $this->files->listGet($page);
    }

    public function listByType($type, $page = 1)
    {
        return $this->files->listByType($type, $page);
    }

    public function listStarred($page = 1)
    {
        return $this->files->listStarred($page);
    }

    public function fileExists($identifier)
    {
        return $this->files->exists($identifier);
    }

    public function fileGet($identifier)
    {
        return $this->files->retrieve($identifier);
    }

    /**
     * Get the image file
     *
     * @param string $identifier file identifier
     * @return FileInterface|null the file or NULL, when cannot retrieve image
     */
    public function imageGet($identifier)
    {
        $file = $this->files->retrieve($identifier);
        if ($file->getType() === 'image') return $file;
    }

    /**
     * Copy file
     *
     * @param string $identifier file identifier
     * @return FileInterface|false copied file when copy failed return false
     */
    public function fileCopy($identifier)
    {
        $target_file = $this->fileGet($identifier);
        $copied_file = $target_file->replicate();
        $new_identifier = $this->createIdentifier();
        if ($this->getStorageDriver($target_file->getStorage())->copy($target_file, $new_identifier))
        {
            if ($copied_file->setIdentifier($new_identifier)->save())
            {
                \Event::fire('file.save', array($copied_file));
                return $copied_file;
            }
        }
        return false;
    }

    /**
     * Get file content.
     * When file not found then system delete the file record and return false.
     *
     * @param string $identifier
     * @return string|false file content or false
     */
    public function fileGetContent($identifier)
    {
        $file = $this->fileGet($identifier);

        try {
            return $this->getStorageDriver($file->getStorage())->get($file);
        }
        catch (Exception $c)
        {
            $file->delete();
            return false;
        }
    }

    /**
     * Save the file to repository
     *
     * @param FileInterface $file
     * @return boolean succession
     */
    public function fileSave($file)
    {
        if ($file->save())
        {
            \Event::fire('file.save', array($file));
            return true;
        }
        else
        {
            false;
        }
    }

    /**
     * Save file content.
     *
     * @param FileInterface $file Prepared FileInterface object
     * @param string $content
     * @return boolean when success return true
     */
    public function fileSaveContent($file, $content = '')
    {
        if ($file->exists())
        {
            $identifier = $file->getIdentifier();
            $file = $this->fileGet($identifier);
            if ($this->getStorageDriver($file->getStorage())->save($file, $content))
            {
                $file->touch();
                return true;
            }
        }
        else
        {
            // When file is set identifier, use it.
            $identifier = trim($file->getIdentifier());
            if ($identifier === '' OR $this->files->exists($identifier))
            {
                $identifier = $this->createIdentifier();
            }

            if ($this->getStorageDriver()->save($identifier, $content))
            {
                $file->setIdentifier($identifier);
                return $this->fileSave($file);
            }
        }
        return false;
    }

    /**
     * Save a file uploaded with auto identifier
     *
     * @param $name input name of uploaded file
     * @return boolean succession
     */
    public function fileSaveUploaded()
    {
        $name = \Config::get('file.upload.name', 'file');
        $uploaded_file = Input::file($name);

        $mime_type = $uploaded_file->getMimeType();
        $file_type = App::make('FileTypeResolver')->getType($mime_type);
        // !TODO: when image type then get image size as `000x000`
        $dimensions = null;

        $file_data = array(
            'original_name' => $uploaded_file->getClientOriginalName(),
            'mime_type'     => $mime_type,
            'file_type'     => $file_type,
            'size'          => $uploaded_file->getSize(),
            'dimensions'    => $dimensions,
        );

        $file = $this->fileCreate('', $file_data);

        return $this->fileSave($file);
    }

    /**
     * Save URL as a file
     *
     * @param string $url for save
     * @return boolean succession
     */
    public function urlSaveAsFile($url)
    {
        $mime_type = App::make('MimeTypeResolver')->getTypeByContent($url);
        $file_type = App::make('FileTypeResolver')->getType($mime_type);
        $size = strlen($url);

        $file_data = array(
            'original_name' => '',
            'mime_type'     => $mime_type,
            'file_type'     => $file_type,
            'size'          => $size,
            'storage'       => 'db',
            'value'         => $url,
        );

        $file = $this->fileCreate('', $file_data);

        return $this->fileSave($file);
    }

    public function fileSaveByUrl($url)
    {
        // !TODO: Get file by URL and save FileInterface objeect
    }

    /**
     * Create empty file for save
     *
     * @param string $identifier
     * @return FileInterface
     */
    public function fileCreate($identifier = '', $options = array())
    {
        $file = $this->files->factory($identifier);
        $file->setIdentify($this->createIdentifier($file));
        foreach ($options as $key => $value)
        {
            $file->$key = $value;
        }
        return $file;
    }

    public function fileDelete($identifier)
    {
        if ($this->fileExists($identifier))
        {
            $file = $this->fileGet($identifier);
            if ($this->getStorageDriver($file->getStorage())->delete($file))
            {
                return $file->delete();
            }
        }
        return false;
    }
    public function access($identifier)
    {
        $file = $this->fileGet($identifier);
        $this->getStorageDriver($file->getStorage())->passthru($file->getIdentifier());
        exit;
    }

    public function download($identifier)
    {
        $file = $this->fileGet($identifier);
        $this->getStorageDriver($file->getStorage())->download($file->getIdentifier());
    }

    protected function createIdentifier(FileInterface $file = null)
    {
        do
        {
            $identifier = $this->generateIdentifier($file);
        }
        while ($this->files->exists($identifier));
        return $identifier;
    }
    
    protected function generateIdentifier(FileInterface $file = null)
    {
        $identifier = '';
        $seed_length = strlen(self::AUTO_IDENTIFIER_SEED);
        $length = $this->getAutoIdentifierLength();
        for ($i = 0; $i < $length; $i++)
        {
            $index = rand(0, $seed_length - 1);
            $identifier .= substr(self::AUTO_IDENTIFIER_SEED, $index, 1);
        }
        return $identifier;
    }
    
    protected function getAutoIdentifierLength()
    {
        static $call_count = 0,
               $increment = 0;
        $call_count++;
        if ($call_count > self::AUTO_IDENTIFIER_TRY_LIMIT)
        {
            $increment++;
            $call_count = 0;
        }
        return self::AUTO_IDENTIFIER_MIN_LENGTH + $increment;
    }

    protected function validateIdentifier($identifier)
    {
        return preg_match(self::IDENTIFIER_REGEX, $identifier);
    }

    public function setLastSaved($file)
    {
        $this->lastSaved = $file;
    }

    public function getLastSaved()
    {
        return $this->lastSaved;
    }

    protected function getStorageDriver($storage = '')
    {
        $storage = $storage ? $storage : \Config::get('file.storage');

        if (array_key_exists($storage, $this->storageDrivers))
        {
            return $this->storageDrivers[$storage];
        }
        return $this->createStorageDriver($storage);
    }

    protected function storageDrivers()
    {
        return $this->storageDrivers;
    }

    protected function createStorageDriver($storage)
    {
        $method_name = camel_case('create_' . $storage . '_storage_driver');
        if (method_exists($this, $method_name))
        {
            return $this->$method_name();
        }
        return null;
    }

    protected function createLocalStorageDriver()
    {
        return $this->storageDrivers['local'] = \App::make('LocalStorage');
    }

    protected function createDatabaseStorageDriver()
    {
        // TODO: create DatabaseStorage
        return null;
    }

    protected function createS3StorageDriver()
    {
        // !TODO: create S3Storage
        return null;
    }

}
