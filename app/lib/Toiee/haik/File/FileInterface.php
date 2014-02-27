<?php
namespace Toiee\haik\File;

interface FileInterface {

    /**
     * get identifier
     *
     * @return string file identifier
     */
    public function getIdentifier();

    /**
     * set identifier
     *
     * @param string $identifier file identifier
     */
    public function setIdentifier($identifier);

    /**
     * get file name
     *
     * @return string file name
     */
    public function getName();

    /**
     * Mark star the file
     *
     * @param boolean $star
     */
    public function star($star = true);

    /**
     * Is the file private?
     *
     * @return boolean is the file private?
     */
    public function isPrivate();
}