<?php


namespace App\EventListener;

use Vich\UploaderBundle\Event\Event;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use App\Entity\Recette;

class ImageUploadListener
{

    /**
     * @var CacheManager
     */
    private $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    public function onVichUploaderPreRemove(Event $event)
    {
        $entity = $event->getObject();

        if(!$entity instanceof Recette) {
            return;
        }

        $this->cacheManager->remove($event->getMapping()->getUriPrefix() . '/' . $entity->getImageNom());
    }

}