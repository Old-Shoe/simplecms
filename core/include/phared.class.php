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

namespace Core\Include;
use BadMethodCallException;
use Exception;
use FilesystemIterator;
use Phar;
use stdClass;
use UnexpectedValueException;

/**
 * Description of phar
 *
 * @author Leonid Kuzin(Dg_INC) <dg.inc.lcf@gmail.com>
 */
class Phared {
    protected Phar|null $phar = null;
    protected array $metadata;

    /**
     * @throws Exception
     */
    public function __construct(string $path)
    {
        try {
            $this->phar = new Phar($path .'.phar', FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME);
        } catch (UnexpectedValueException $e) {
            die(sprintf(_('Could not open %s'), $e->getFile()));
        } catch (BadMethodCallException $e) {
            echo _('technically, this cannot happen');
        } catch (Exception $e) {
            echo sprintf(_('Unknown error: %s'), $e->getMessage());
        }

        if(!$this->phar->hasMetadata() && $this->phar != null) {
            throw new Exception(_("No metadata in file!"));
        } else {
            $this->metadata = $this->phar->getMetadata();
        }
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function getAlias(): null|string
    {
        return $this->phar->getAlias();
    }

    /**
     * @throws Exception
     */
    public function verify(string $sign): bool
    {
        $file_sign = $this->phar->getSignature();
        if($file_sign === false){
            throw new Exception('Signature not found!');
        }
        switch ($file_sign['hash'])
        {
            case 'MD5':
                if($file_sign['hash_type'] === $sign)
                {
                    return true;
                }
                break;
            case 'OPENSSL':
                break;
        }
        return false;
    }

    public function isInternal(): bool
    {
        return (boolean)$this->metadata['info']['internal'];
    }

    /**
     * @throws Exception
     */
    public function getInfo(): array
    {
        return (array)$this->metadata['info'];
    }

    /**
     * @throws Exception
     */
    public function getInstances(): array
    {
        static $inst = array();

        $instances = $this->metadata["instances"];
        foreach ($instances as $instance) {
            foreach ($instance as $class => $function)
            {
                $inst[$class] = $function;
            }
        }

        return $inst;
    }
}
