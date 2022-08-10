<?php

/*
 * The MIT License
 *
 * Copyright 2020 Leonid Kuzin(Dg_INC) <dg.inc.lcf@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace SimpleCMS\Core;
use BadMethodCallException;
use Exception;
use FilesystemIterator;
use Phar;
use UnexpectedValueException;

/**
 * Description of phar
 *
 * @author Leonid Kuzin(Dg_INC) <dg.inc.lcf@gmail.com>
 */
class Extension {
    protected string $pharPath;
    protected Phar|null $phar = null;
    protected mixed $metadata;

    /**
     * @throws Exception
     */
    public function __construct(string $path)
    {
        $this->pharPath = $path;

        try {
            $this->phar = new Phar($path, FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME);
        } catch (UnexpectedValueException $e) {
            die(sprintf(_('Could not open %s'), $e->getFile()));
        } catch (BadMethodCallException $e) {
            echo _('technically, this cannot happen');
        } catch (Exception $e) {
            echo sprintf(_('Unknown error: %s'), $e->getMessage());
        }

        if(!$this->phar->hasMetadata() && $this->phar != null) { throw new Exception(_("No metadata in file!"));}
    }


    public function isInternal():bool
    {
        $meta = $this->phar->getMetadata();
        return $meta["info"];
    }

    /**
     * @throws Exception
     */
    public function getInfo() :object
    {
        $meta = $this->phar->getMetadata();
        return $meta["info"];
    }

    /**
     * @throws Exception
     */
    public function getInstances() :array
    {
        static $inst = array();

        try {
            $meta = $this->phar->getMetadata();
            $instances = $meta["instances"];
            foreach ($instances as $instance) {
                foreach ($instance as $class => $function)
                {
                    $inst[$class] = $function;
                }
            }
        } catch (Exception $e) {
            throw new Exception(sprintf("blablabla: %s", $e->getMessage()));
        }

        return $inst;
    }
}
