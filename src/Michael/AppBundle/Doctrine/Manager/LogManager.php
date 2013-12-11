<?php

namespace Michael\AppBundle\Doctrine\Manager;

class LogManager
{
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function generate($user, $request, $message)
    {
            $log = $this->create();

            $log->setUser($user->getUsername())
                    ->setTime(new \DateTime("now"))
                    ->setUri($_SERVER['REQUEST_URI'])
                    ->setController($request->attributes->get('_controller'))
                    ->setMessage($message);

            $this->update($log);
    }
}
