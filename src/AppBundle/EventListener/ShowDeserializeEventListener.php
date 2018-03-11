<?php
namespace AppBundle\EventListener;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

class ShowDeserializeEventListener implements EventSubscriberInterface
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    static public function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.pre_deserialize', 'method' => 'onPreDeserialize'),
        );
    }

    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        $data = $event->getData();

        if(is_array($data) && isset($data['author']) && (int)$data['author']) {
            $data['author'] = $this->em->getRepository(User::class)->findOneBy(['id' => $data['author']]);
        } else {
            dump($event); die;
        }

        $event->setData($data);
    }
}