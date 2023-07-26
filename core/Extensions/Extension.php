<?php declare(strict_types=1);
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

namespace Core\Extensions;

use DirectoryIterator;
use Exception;
use PDOException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use UnexpectedValueException;
use xPDO\xPDO;

class Extension extends Log
{
    protected ?xPDO $connection = null;
    private ?Phared $ext = null;
    private ?string $phar_alias = null;

    public function __construct(string $alias)
    {
        parent::__construct();
        $this->phar_alias =& $alias;
        try {
            $pdo = new xPDOConstruct();
            $this->connection = $pdo->get('primary');
            $this->connection->connect();

            /*if($this->exist()) {
                $this->ext = new Phared(SIMPLECMS_EXT_DIR. $this->phar_alias);
            }*/
            //$this->ext = new Phared(SIMPLECMS_EXT_DIR. $alias);

        } catch (RuntimeException $exception) {
            $this->logger->error(sprintf('%s: %c',$exception->getMessage(), $exception->getCode()));
            throw new RuntimeException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        } catch (ContainerExceptionInterface|NotFoundExceptionInterface $exception) {
            $this->logger->error(sprintf('%s: %c',$exception->getMessage(), $exception->getCode()));
        } catch (Exception $e) {
        }
        return $this;
    }

    final public function install(): bool
    {
        if($this->is_installed()) {
            return false;
        }
        $sql = 'INSERT INTO extensions (`name`, `file_name`, `author`, `version`, `internal`, `alias`, `license`) VALUES (:name,:file_name,:author,:version,:internal,:alias,:license)';

        try {
            $this->connection->beginTransaction();

            $info = $this->ext->getInfo();
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':name', $info['name']);
            $stmt->bindValue(':alias', $info['alias']);
            $stmt->bindValue(':file_name', $this->phar_alias);
            $stmt->bindValue(':author', $info['author']);
            $stmt->bindValue(':version', $info['version']);
            $stmt->bindValue(':internal', $info['internal']);
            $stmt->bindValue(':license', $info['license']);
            $stmt->execute();
            $stmt->closeCursor();
            $this->connection->commit();
        } catch (PDOException $exception) {
            $this->connection->rollback();
            $this->logger->error(sprintf('%s: %d',$exception->getMessage(), $exception->getCode()));
        }

        return true;
    }

    final public function uninstall(): bool
    {
        if(!$this->is_installed()) {
            return false;
        }

        $sql = 'DELETE FROM extensions WHERE alias=? OR file_name=?';

        try {
            $this->connection->beginTransaction();

            $info = $this->ext->getInfo();
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$info['alias'], $this->phar_alias]);
            $stmt->closeCursor();
            $this->connection->commit();
        } catch (PDOException $exception) {
            $this->connection->rollback();
            $this->logger->error(sprintf('%s: %d',$exception->getMessage(), $exception->getCode()));
        }
        return true;
    }

    final public function is_installed(): bool {
        $sql = 'SELECT * FROM extensions WHERE alias=?'; #TODO: (Important) Reformat SQL request

        $result = null;
        try {
            $this->connection->beginTransaction();

            $info = $this->ext->getInfo();
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$info['alias']]);

            $stmt->fetchAll();
            $result = $stmt->rowCount();

            $stmt->closeCursor();
            $this->connection->commit();
        } catch (PDOException|Exception $exception) {
            $this->connection->rollback();
            $this->logger->error(sprintf('%s: %d',$exception->getMessage(), $exception->getCode()));
        }

        return $result == 1;
    }

    public function is_enabled(string $phar): void
    {

    }

    public function exist(): bool
    {
        try {
            $dir = new DirectoryIterator(SIMPLECMS_EXT_DIR);

            if($dir->valid()) {
                foreach ($dir as $fileinfo) {
                    if ($fileinfo->isFile() && ($fileinfo->getExtension() == 'phar')) {

                        $ext = new Phared(SIMPLECMS_EXT_DIR. $fileinfo->getFilename());
                        if(hash_equals($this->phar_alias, $ext->getAlias())) {
                            return true;
                        }
                    }
                }
            }
        } catch (UnexpectedValueException|RuntimeException $exception) {
            $this->logger->error(sprintf('%s: %d', $exception->getMessage(), $exception->getCode()));
        }
        return false;
    }

    public function enable(string $phar): void
    {

    }
    public function disable(string $phar): void
    {

    }
    public function download(string $phar): void
    {

    }
    public function remove(string $phar): void
    {

    }
    public function update(string $phar): void
    {

    }
    public function downgrade(string $phar): void
    {

    }
}