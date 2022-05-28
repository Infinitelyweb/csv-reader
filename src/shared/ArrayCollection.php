<?php
declare(strict_types=1);

namespace Saswo\CsvReader\shared;

class ArrayCollection
{
    private array $collection = [];
    private ?string $waring = null;
    private ?string $error = null;

    public function addItem(mixed $value, ?string $key = null): void
    {
        if ($key === null) {
            $this->collection[] = $value;
        } else {
            if (isset($this->collection[$key])) {
                $this->waring = sprintf('key %s overwritten', $key);
            }
            $this->collection[$key] = $value;
        }
    }

    public function deleteItem(string $key): bool
    {
        if (isset($this->collection[$key])) {
            unset($this->collection[$key]);
            return true;
        }

        $this->error = sprintf('no delete: key %s does not exists', $key);
        return false;
    }

    public function getItem(string $key): mixed
    {
        if (isset($this->collection[$key])) {
            return $this->collection[$key];
        }

        $this->error = sprintf('no get: key %s does not exists', $key);
        return null;
    }

    public function getAll(): array
    {
        return $this->collection;
    }

    public function getLastError(): ?string
    {
        return $this->error;
    }

    public function getLastWarning(): ?string
    {
        return $this->waring;
    }
}
