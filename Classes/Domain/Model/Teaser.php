<?php

namespace CHF\TeaserManager\Domain\Model;

/***
 *
 * This file is part of the "Teaser Manager" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Christian Fries <hello@christian-fries.ch>, CF Webworks
 *
 ***/

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Teaser extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var int
     */
    protected $hidden = 0;

    /**
     * @var string
     */
    protected $subtitle = '';

    /**
     * @var string
     */
    protected $linkText = '';

    /**
     * @var string
     */
    protected $link = '';

    /**
     * @var string
     */
    protected $text = '';

    /**
     * @var \DateTime
     */
    protected $date = null;

    /**
     * @var string
     */
    protected $icon = '';

    /**
     * @var string
     */
    protected $selectedIcon = '';

    /**
     * @var \LST\People\Domain\Model\Person
     * @Extbase\ORM\Lazy
     * @lazy
     */
    protected $person = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\LST\People\Domain\Model\Person>
     * @Extbase\ORM\Lazy
     * @lazy
     */
    protected $persons = null;

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $image = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $images = null;

    /**
     * @var string
     */
    protected $size = '';

    /**
     * @var \CHF\ColorManager\Domain\Model\Color
     * @Extbase\ORM\Lazy
     * @lazy
     */
    protected $color = null;

    /**
     * @var \CHF\TeaserManager\Domain\Model\TeaserType
     * @Extbase\ORM\Lazy
     * @lazy
     */
    protected $type = null;

    /**
     * @var string
     */
    protected $style = '';

    /**
     * @var string
     */
    protected $publicImageUrl = '';

    public function __construct()
    {
        $this->initializeObjectStorages();
    }

    public function initializeObjectStorages()
    {
        $this->persons = new ObjectStorage();
        $this->images = new ObjectStorage();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param int $hidden
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @return string
     */
    public function getLinkText()
    {
        return $this->linkText;
    }

    /**
     * @param string $linkText
     */
    public function setLinkText($linkText)
    {
        $this->linkText = $linkText;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getSelectedIcon()
    {
        return $this->selectedIcon;
    }

    /**
     * @param string $selectedIcon
     */
    public function setSelectedIcon($selectedIcon)
    {
        $this->selectedIcon = $selectedIcon;
    }

    /**
     * @return \LST\People\Domain\Model\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param \LST\People\Domain\Model\Person $person
     */
    public function setPerson(\LST\People\Domain\Model\Person $person)
    {
        $this->person = $person;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\LST\People\Domain\Model\Person>
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\LST\People\Domain\Model\Person> $persons
     */
    public function setPersons(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $persons)
    {
        $this->persons = $persons;
    }

    /**
     * @return \CHF\TeaserManager\Domain\Model\TeaserType type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \CHF\TeaserManager\Domain\Model\TeaserType $type
     */
    public function setType(\CHF\TeaserManager\Domain\Model\TeaserType $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getPlainText()
    {
        return trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($this->getText()))));
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     */
    public function setImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image = $image;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     */
    public function setImages(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $images)
    {
        $this->images = $images;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return \CHF\ColorManager\Domain\Model\Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param \CHF\ColorManager\Domain\Model\Color $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param string $style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

    /**
     * @return string
     */
    public function getPublicImageUrl()
    {
        return $this->publicImageUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setPublicImageUrl($baseUrl)
    {
        if ($this->getImage() !== null) {
            $this->publicImageUrl = $baseUrl . $this->getImage()->getOriginalResource()->getPublicUrl();
        }
    }
}
