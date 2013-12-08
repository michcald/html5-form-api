<?php

namespace Michael\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
    	foreach ($this->getData() as $data) {
    		$article = new \Michael\AppBundle\Entity\Article();
    		$article->setTitle($data['title'])
    			->setText($data['text'])
    			->setPublished($data['published'])
    			->setViews($data['views']);
    		$manager->persist($article);
    	}

    	$manager->flush();
    }

    private function getData()
    {
    	return array(
    		array(
    			'title' => 'Title1',
    			'text' => 'This is the text',
    			'published' => false,
    			'views' => rand(0, 100),
    		),
    		array(
    			'title' => 'Title2',
    			'text' => 'This is the text',
    			'published' => true,
    			'views' => rand(0, 100),
    		),
    		array(
    			'title' => 'Title3',
    			'text' => 'This is the text',
    			'published' => false,
    			'views' => rand(0, 100),
    		)
    	);
    }
}
