<?php

namespace App\Entity;

interface CountableInterface
{
    public function __construct(string $name, ?int $month = null);

    public function getName(): string;

    public function getMonth(): int;

    public function incrementCount(): CountableInterface;

    public function getCount(): ?int;
}
