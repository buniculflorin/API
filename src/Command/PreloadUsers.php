<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PreloadUsers extends Command
{
    private const USER_DATA = [
        ['firstName' => 'Patrick', 'lastName' => 'Steward', 'username'=>'stwpatrick', 'darkMode'=>true, 'dateCreated' => '24-03-2020'],
        ['firstName' => 'Michael', 'lastName' => 'Kane', 'username'=>'kanemic', 'darkMode'=>true, 'dateCreated' => '12-02-2020'],
        ['firstName' => 'George', 'lastName' => 'Orwell', 'username'=>'ggorwell', 'darkMode'=>false, 'dateCreated' => '05-12-2020'],
        ['firstName' => 'Spock', 'lastName' => 'Vulcan', 'username'=>'spockv', 'darkMode'=>false, 'dateCreated' => '03-09-2020'],
        ['firstName' => 'Sheldon', 'lastName' => 'Cooper', 'username'=>'scshelly', 'darkMode'=>false, 'dateCreated' => '22-08-2020']
    ];
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        string $name = 'app:preload-users'
    ) {
        parent::__construct($name);
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {



        foreach(self::USER_DATA as $userInformation)
        {
            $user = new User();
            $user   ->setFirstName($userInformation['firstName'])
                    ->setLastName($userInformation['lastName'])
                    ->setUsername($userInformation['username'])
                    ->setDarkMode($userInformation['darkMode'])
                    ->setDateCreated($userInformation['dateCreated']);

            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();


        return Command::SUCCESS;

    }
}
