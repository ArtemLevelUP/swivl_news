<?php
namespace AcmeNewsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AcmeNewsBundle\Entity\Post;

class LoadPostData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 50; ++$i) {
            $post = new Post();
            $post->setTitle(sprintf('Тестовая новость №%d', $i))
                ->setAnnounce(sprintf('Краткое описание %d-й новости', $i))
                ->setDescription(sprintf('Полный текст %d-й новости', $i))
                ->publish();

            $manager->persist($post);
            $manager->flush();
        }
    }
}
