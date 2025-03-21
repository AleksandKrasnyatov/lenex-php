<?php
/**
 * This file is part of the lenex-php package.
 *
 * The Lenex file format is created by Swimrankings.net
 *
 * (c) Leon Verschuren <lverschuren@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace leonverschuren\Lenex\Model;

class Facility
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $city;

    /** @var string */
    protected $nation;

    /** @var string */
    protected $state;

    /** @var string */
    protected $street;

    /** @var string */
    protected $street2;

    /** @var int */
    protected $zip;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getNation(): ?string
    {
        return $this->nation;
    }

    public function setNation(string $nation): void
    {
        $this->nation = $nation;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getStreet2(): ?string
    {
        return $this->street2;
    }

    public function setStreet2(string $street2): void
    {
        $this->street2 = $street2;
    }

    public function getZip(): ?int
    {
        return $this->zip;
    }

    public function setZip(int $zip): void
    {
        $this->zip = $zip;
    }

}
