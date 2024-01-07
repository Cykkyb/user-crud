<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;

#[Route('/users')]
class UserController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Возвращает список пользователей.
     *
     * @return Response Ответ с массивом пользователей в формате JSON.
     */
    #[Route('/', name: 'user_list', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->userService->getUsers();

        return $this->json($users, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * Создает нового пользователя.
     *
     * @param Request $request Объект запроса, содержащий данные пользователя.
     * @return Response Ответ с созданным пользователем в формате JSON или сообщение об ошибке.
     */
    #[Route('/', name: 'user_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        if (!$this->checkRequest($request)) {
            return new Response('Invalid request', Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->create($request);
        $errors = $this->userService->validate($user);

        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->userService->add($user);

        return $this->json($user, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    /**
     * Возвращает информацию о пользователе по его идентификатору.
     *
     * @param UserService $userService Сервис пользователя.
     * @param int $id Идентификатор пользователя.
     * @return Response Ответ с информацией о пользователе в формате JSON или сообщение об ошибке.
     */
    #[Route('/{id}', name: 'user_read', methods: ['GET'])]
    public function read(UserService $userService, int $id): Response
    {
        $user = $this->userService->getUser($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь не найден');
        }

        return $this->json($user, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * Обновляет данные пользователя.
     *
     * @param Request $request Объект запроса, содержащий данные для обновления.
     * @param int $id Идентификатор пользователя.
     * @return Response Ответ с обновленным пользователем в формате JSON или сообщение об ошибке.
     */
    #[Route('/{id}', name: 'user_update', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, int $id): Response
    {
        if (!$this->checkRequest($request)) {
            return new Response('Invalid request', Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->getUser($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь не найден');
        }

        $errors = $this->userService->update($user, $request);

        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        return $this->json($user, Response::HTTP_OK, [], ['Content-Type' => 'application/json']);
    }

    /**
     * Удаляет пользователя по его идентификатору.
     *
     * @param int $id Идентификатор пользователя.
     * @return Response Ответ с сообщением об успешном удалении или сообщение об ошибке.
     */
    #[Route('/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $user = $this->userService->getUser($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь не найден');
        }

        $this->userService->delete($user);

        return new Response('Пользователь удален', Response::HTTP_OK);
    }

    /**
     * Проверяет корректность запроса.
     *
     * @param Request $request Объект запроса.
     * @return bool Возвращает true, если запрос корректный, иначе false.
     */
    protected function checkRequest(Request $request): bool
    {
        if ($request->getContentType() !== 'json' || !$request->getContent()) {
            return false;
        }

        return true;
    }
}
