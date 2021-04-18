<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User as UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class User
 * @package App\Controller
 * Use this to manage users via API
 */
class User
{

    const KEY_AUTH_TOKEN = 'AUTH_TOKEN';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {

        $this->entityManager = $entityManager;
    }

    /**
     * This verifies if the right token is provided at an API
     */
    private function validateRequest()
    {
        if(isset($_SERVER['HTTP_AUTH_TOKEN'])){
            $requestToken = $_SERVER['HTTP_AUTH_TOKEN'];
        } else {
            $requestToken = null;
        }
        return $requestToken === AuthToken::TOKEN;

    }

    /**
     *
     * @Route ("/users", methods = {"GET"})
     */
    public function get()
    {
        if (!$this->validateRequest()){
            return new JsonResponse(['result' => "UnAuthorised"], 403);
        }

        $result = [];

        $userRepository = $this->entityManager->getRepository(UserEntity::class);
        foreach ($userRepository->findAll() as $user){
            $result[] = [
                'id' => $user->getId(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getlastName(),
                'username' => $user->getUserName(),
                'darkMode' => $user->getDarkMode(),
                'dateCreated' => $user->getDateCreated()

            ];
        }

        return new JsonResponse(['result' => $result]);
    }

    /**
     * Delete user
     * @Route ("/users", methods={"DELETE"})
     */
    public function delete()
    {
     if (!$this->validateRequest()){
            return new JsonResponse(['result' => "UnAuthorised"], 403);
        }
     /*
      * First retrieve the user from the database using the id from the Request
      */
     $userRepository = $this->entityManager->getRepository(UserEntity::class);
     $user = $userRepository->find($_GET['id']);

     /*
      * Remove the user with the id given in the request
      */
     $this->entityManager->remove($user);
     $this->entityManager->flush();

     return new JsonResponse(['result'=>"Successful delete of the user with {$_GET['id']}"]);
    }

    /**
     * @Route ("/users", methods = {"POST"}))
     */
    public function create()
    {
        if (!$this->validateRequest()){
            return new JsonResponse(['result' => "UnAuthorised"], 403);
        }
        $entityBody = json_decode(
            file_get_contents('php://input'),
            true);

        $user = new UserEntity();
        $user->setFirstName($entityBody['firstName']);
        $user->setLastName($entityBody['lastName']);
        $user->setUsername($entityBody['username']);
        $user->setDarkMode($entityBody['darkMode']);
        $user->setDateCreated($entityBody['dateCreated']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['result' => "Successfully created user with id{$user->getId()}"]);
    }

    /**
     * @Route ("/users", methods = {"PATCH"}))
     */
    public function update()
    {
        if (!$this->validateRequest()){
            return new JsonResponse(['result' => "UnAuthorised"], 403);
        }
        $requestedBody = json_decode(
            file_get_contents('php://input'),
            true
        );

        $userRepository = $this->entityManager->getRepository(UserEntity::class);
        /** @var UserEntity $user  */
        $user = $userRepository->find($requestedBody['id']);

        if(isset($requestedBody['firstName'])) {
            $user->setFirstName($requestedBody['firstName']);
        }

        if(isset($requestedBody['lastName'])) {
            $user->setLastName($requestedBody['lastName']);
        }

        if(isset($requestedBody['username'])) {
            $user->setUsername($requestedBody['username']);
        }

        if(isset($requestedBody['darkMode'])) {
            $user->setDarkMode($requestedBody['darkMode']);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();



        return new JsonResponse(['result' => "Successfully updated user with {$user->getId()}"]);
        }

}