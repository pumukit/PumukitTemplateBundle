<?php

namespace Pumukit\TemplateBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Pumukit\TemplateBundle\Repository\TemplateRepository")
 */
class Template
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Field(type="bool")
     */
    private $hide = false;

    /**
     * @MongoDB\Field(type="date")
     */
    private $createdAt;

    /**
     * @MongoDB\Field(type="date")
     */
    private $updatedAt;

    /**
     * @MongoDB\Field(type="string")
     * @MongoDB\UniqueIndex(order="asc")
     */
    private $name = '';

    /**
     * @MongoDB\Field(type="raw")
     */
    private $text = ['en' => ''];

    private $locale = 'en';

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setHide(bool $hide): void
    {
        $this->hide = $hide;
    }

    public function getHide(): bool
    {
        return $this->hide;
    }

    public function isHide(): bool
    {
        return $this->hide;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setText(string $text, ?string $locale = null): void
    {
        if (null === $locale) {
            $locale = $this->locale;
        }
        $this->text[$locale] = $text;
    }

    public function getText(?string $locale = null): string
    {
        if (null === $locale) {
            $locale = $this->locale;
        }
        if (!isset($this->text[$locale])) {
            return '';
        }

        return $this->text[$locale];
    }

    public function setI18nText(array $text): void
    {
        $this->text = $text;
    }

    public function getI18nText(): array
    {
        return $this->text;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
