<?php

/**
 * This file is part of the Clover to Html package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CloverToHtml;

class LineIterator implements \Iterator
{
    /**
     * @var array
     */
    private $lines = array();
    /**
     * @var \SplFileObject
     */
    private $file;
    /**
     * @var integer
     */
    private $position;

    /**
     * Construct.
     *
     * @param string $filePath
     * @param array  $lines
     */
    public function __construct($filePath, array $lines)
    {
        $this->file  = new \SplFileObject($filePath);
        $this->lines = $lines;
    }

    /* (non-PHPdoc)
     * @see Iterator::rewind()
     */
    public function rewind(): void
    {
        $this->file->rewind();
        $this->position = 0;
    }

    /* (non-PHPdoc)
     * @see Iterator::current()
     */
    public function current()
    {
        $type      = 'unkown';
        $isCovered = false;

        if (isset($this->lines[$this->position])) {
            $type      = $this->lines[$this->position]['type'];
            $isCovered = $this->lines[$this->position]['isCovered'];
        }

        return array('type' => $type, 'isCovered' => $isCovered, 'content' => $this->file->fgets());
    }

    /* (non-PHPdoc)
     * @see Iterator::key()
     */
    public function key()
    {
        return $this->position;
    }

    /* (non-PHPdoc)
     * @see Iterator::next()
     */
    public function next(): void
    {
        ++$this->position;
    }

    /* (non-PHPdoc)
     * @see Iterator::valid()
     */
    public function valid(): bool
    {
        return !$this->file->eof();
    }

    /**
     * @return number
     */
    public function count()
    {
        $file = new \SplFileObject($this->file->getPathname());

        $file->seek(PHP_INT_MAX);
        return $file->key() + 1;
    }
}
