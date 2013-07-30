<?php
/**
 * @package AHS\FacebookNewscoopBundle
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace AHS\FacebookNewscoopBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Facebook informations entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="plugin_facebook_informations")
 */
class Facebook 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="article", nullable=true)
     * @var int
     */
    private $article;

    /**
     * @ORM\Column(type="integer", name="language", nullable=true)
     * @var int
     */
    private $language;

    /**
     * @ORM\Column(type="string", name="title", nullable=true)
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="text", name="description", nullable=true)
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="text", name="url", nullable=true)
     * @var string
     */
    private $url;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @var string
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean", name="is_active")
     * @var boolean
     */
    private $is_active;

    public function __construct() {
        $this->setCreatedAt(new \DateTime());
        $this->setIsActive(true);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get article id
     *
     * @return string
     */
    public function getArticle()
    {
        return $this->title;
    }

    /**
     * Set article id
     *
     * @param integer $article
     * @return integer
     */
    public function setArticle($article)
    {
        $this->article = $article;
        
        return $article;
    }

    /**
     * Get language id
     *
     * @return integer
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set language id
     *
     * @param integer $language
     * @return integer
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        
        return $language;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return string
     */
    public function setTitle($title)
    {
        $this->title = $title;
        
        return $title;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return string
     */
    public function setDescription($description)
    {
        $this->description = $description;
        
        return $this;
    }

    /**
     * Get article url from Facebook
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set article url from Facebook
     *
     * @param string $url
     * @return string
     */
    public function setUrl($url)
    {
        $this->url = $url;
        
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set status
     *
     * @param boolean $is_active
     * @return boolean
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
        
        return $this;
    }

    /**
     * Get create date
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set create date
     *
     * @param datetime $created_at
     * @return datetime
     */
    public function setCreatedAt(\DateTime $created_at)
    {
        $this->created_at = $created_at;
        
        return $this;
    }
}