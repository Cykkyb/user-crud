<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use PhpParser\Node\Expr\Array_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserService
{
    private $userRepository;
    private $validator;
    private $serializer;

    public function __construct(UserRepository $userRepository, ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    /**
     * Создает новый объект User на основе данных из запроса.
     *
     * @param Request $request Объект запроса, содержащий данные пользователя.
     * @return User Созданный объект User.
     */
    public function create(Request $request): User
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());

        return $user;
    }

    /**
     * Добавляет пользователя в репозиторий.
     *
     * @param User $user Объект пользователя для добавления.
     * @return void
     */
    public function add(User $user): void
    {
        $this->userRepository->add($user);
    }

    /**
     * Возвращает массив всех пользователей.
     *
     * @return array Массив пользователей.
     */
    public function getUsers(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * Возвращает пользователя с заданным идентификатором.
     *
     * @param mixed $id Идентификатор пользователя.
     * @return User|null Объект пользователя или null, если пользователь не найден.
     */
    public function getUser($id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * Обновляет данные пользователя на основе данных из запроса.
     *
     * @param User $user Объект пользователя для обновления.
     * @param Request $request Объект запроса, содержащий данные для обновления.
     * @return array Массив ошибок валидации или пустой массив, если ошибок нет.
     */
    public function update(User $user, Request $request): array
    {
        $this->serializer->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);
        $user->setUpdatedAt(new \DateTime());

        $errors = $this->validate($user);

        if (!empty($errors)) {
            return $errors;
        }

        $this->userRepository->update();

        return [];
    }

    /**
     * Удаляет пользователя из репозитория.
     *
     * @param User $user Объект пользователя для удаления.
     * @return void
     */
    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }

    /**
     * Выполняет валидацию пользователя.
     *
     * @param User $user Объект пользователя для валидации.
     * @return array Массив сообщений об ошибках валидации или пустой массив, если ошибок нет.
     */
    public function validate(User $user): array
    {
        $errors = $this->validator->validate($user);

        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return $errorMessages;
    }
}