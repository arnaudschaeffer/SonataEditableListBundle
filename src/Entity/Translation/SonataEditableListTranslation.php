<?php

namespace Aschaeffer\SonataEditableListBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="sonata_list__translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx_list", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class SonataEditableListTranslation extends AbstractPersonalTranslation {

    /**
     * Convenient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale, $field, $value)
    {
        parent::__construct($locale, $field, $value);
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SonataEditableList", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="code", onDelete="CASCADE")
     */
    protected $object;
}