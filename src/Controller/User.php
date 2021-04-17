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
     * @Route ("/users", methods = {"GET"})
     */
    public function get()
    {
        if (!$this->validateRequest()){
            return new JsonResponse(['result' => "UnAuthorised"], 403);
        }
        return new JsonResponse(['result' => "GET"]);
    }

    /**
     * @Route ("/users", methods = {"DELETE"}))
     */
    public function delete()
    {
        if (!$this->validateRequest()){
            return new JsonResponse(['result' => "UnAuthorised"], 403);
        }
        return new JsonResponse(['result' => "DELETE"]);
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
     * @Route ("/users", methods = {"PUT"}))
     */
    public function update()
    {
        if (!$this->validateRequest()){
            return new JsonResponse(['result' => "UnAuthorised"], 403);
        }
        return new JsonResponse(['result' => "PATCH"]);
    }



}