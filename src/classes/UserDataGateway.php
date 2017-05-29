<?php

/**
 * Класс, содержащий методы для работы с таблицей пользователей.
 */
class UserDataGateway
{
    /**
     * Интерфейс для доступа к базе данных.
     *
     * @var PDO
     */
    private $pdo;
    /**
     * Переменная для сохранения ошибки.
     *
     * @var string
     */
    private $error;

    /**
     * При создании UserDataGateway создаеться объект для работы с БД.
     *
     * @param array $settings Настройки для подключения
     */
    public function __construct($settings)
    {
        try {
            // MySQL через PDO_MYSQL
            $this->pdo = new PDO('mysql:host='.$settings['host'].';dbname='.$settings['dbname'].';charset='.$settings['charset'], $settings['user'], $settings['password']);
            // Режим выброса исключений
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Строгий режим MySQL
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET sql_mode='STRICT_ALL_TABLES'");
        } catch (PDOException $e) {
            $this->error = 'Произошла ошибка.';
        }
    }

    /**
     * Получить колличество пользователей.
     *
     * @return int Количество пользователей
     */
    public function getCountUsers()
    {
        try {
            $query = 'SELECT count(*) FROM users';
            $st = $this->pdo->prepare($query);
            $st->execute();
            $result = $st->fetch();

            return $result[0];
        } catch (PDOException $e) {
            $this->error = 'Произошла ошибка. Не удалось получить список пользователей.';
        }
    }

    /**
     * Получить массив пользователей из базы.
     *
     * @param int $limit  Количество записей
     * @param int $offset Сколько пропустить
     *
     * @return array Массив пользователей
     */
    public function getUsers($limit, $offset)
    {
        try {
            $query = 'SELECT * FROM users LIMIT '.$limit.' OFFSET '.$offset;
            $st = $this->pdo->prepare($query);
            $st->execute();
            $st->setFetchMode(PDO::FETCH_CLASS, 'User');
            $arrayUsers = $st->fetchAll();

            return $arrayUsers;
        } catch (PDOException $e) {
            $this->error = 'Произошла ошибка. Не удалось получить список пользователей.';
        }
    }

    /**
     * Удалить пользователя по ID.
     *
     * @param int $id Идентификатор пользователя
     *
     * @return int Количество строчек
     */
    public function deleteUserById($id)
    {
        try {
            $query = 'DELETE FROM users WHERE id=:id';
            $st = $this->pdo->prepare($query);
            $st->bindValue(':id', $id);
            $st->execute();

            return $st->rowCount();
        } catch (PDOException $e) {
            $this->error = 'Произошла ошибка. Не удалось удалить пользователя.';
        }
    }

    /**
     * Метод добавления пользователя в базу.
     *
     * @param User $user Объект пользователя
     */
    public function addUser(User $user)
    {
        try {
            $query = 'INSERT INTO users (firstName, lastName, yearOfBirth, email, passwordHash, sessionId) values (:firstName, :lastName, :yearOfBirth, :email, :passwordHash, :sessionId)';
            $st = $this->pdo->prepare($query);
            $st->bindValue(':firstName', $user->getFirstName());
            $st->bindValue(':lastName', $user->getLastName());
            $st->bindValue(':yearOfBirth', $user->getYearOfBirth());
            $st->bindValue(':email', $user->getEmail());
            $st->bindValue(':passwordHash', $user->getPasswordHash());
            $st->bindValue(':sessionId', $user->getSessionId());
            $st->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->error = 'Пользователь с таким Email уже зарегистрирован.';
            } else {
                $this->error = 'Произошла ошибка.';
            }
        }
    }

    /**
     * Обновить пользователя с у казанном по ID.
     *
     * @param User $user        Объект пользователя
     * @param bool $setPassword Обновить пароль?
     * @param bool $setEmail    Обновить Email?
     */
    public function updateUserById(User $user, $setPassword, $setEmail)
    {
        try {
            $query = 'UPDATE users SET firstName = :firstName, lastName = :lastName, yearOfBirth = :yearOfBirth, isAdmin = :isAdmin, sessionId = null';
            if ($setPassword) {
                $query .= ', passwordHash = :passwordHash';
            }
            if ($setEmail) {
                $query .= ', email = :email';
            }
            $query .= ' WHERE id = :id';
            $st = $this->pdo->prepare($query);
            $st->bindValue(':firstName', $user->getFirstName());
            $st->bindValue(':lastName', $user->getLastName());
            $st->bindValue(':yearOfBirth', $user->getYearOfBirth());
            $st->bindValue(':isAdmin', $user->getIsAdmin());
            $st->bindValue(':id', $user->getId());
            if ($setPassword) {
                $st->bindValue(':passwordHash', $user->getPasswordHash());
            }
            if ($setEmail) {
                $st->bindValue(':email', $user->getEmail());
            }
            $st->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->error = 'Пользователь с таким Email уже зарегистрирован.';
            } else {
                $this->error = 'Произошла ошибка.';
            }
        }
    }

    /**
     * Проверка соответствия id сессии и id юзера в базе.
     *
     * @param User $user Объект пользователя
     *
     * @return bool true - если соответствуют, false - если нет
     */
    public function checkSessionId(User $user)
    {
        try {
            $query = 'SELECT count(*) as count FROM users WHERE id = :id AND sessionId = :sessionId';
            $st = $this->pdo->prepare($query);
            $st->bindValue(':id', $user->getId());
            $st->bindValue(':sessionId', $user->getSessionId());
            $st->execute();
            $result = $st->fetch();

            return $result['count'];
        } catch (PDOException $e) {
            $this->error = 'Произошла ошибка.';
        }
    }

    /**
     * Получить данные пользователя по Email, проверить пароль и обновить id сессии.
     *
     * @param User $user       Класс пользователя
     * @param bool $adminCheck Искать администратора?
     *
     * @return User Возвращает класс пользователя
     */
    public function getUserForLogin(User $user, $adminCheck = false)
    {
        try {
            $query = 'SELECT * FROM users WHERE email = :email';
            if ($adminCheck) {
                $query .= ' AND isAdmin = 1';
            }
            $st = $this->pdo->prepare($query);
            $st->bindValue(':email', $user->getEmail());
            $st->execute();
            $st->setFetchMode(PDO::FETCH_CLASS, 'User');
            $userFromBd = $st->fetch();
            // Проверка пароля по хеш
            if (!$userFromBd or !$userFromBd->verifyPassword($user->getPassword())) {
                $this->error = 'Неправильный логин или пароль.';

                return $user;
            }
            $this->updateUserSessionId($userFromBd->getId(), $user->getSessionId());

            return $userFromBd;
        } catch (PDOException $e) {
            $this->error = 'Произошла ошибка.';

            return $user;
        }
    }

    /**
     * Получить данные пользователя по ID.
     *
     * @param int $id Id пользователя
     *
     * @return bollean Возвращает объект пользователя если найден или false если нет
     */
    public function getUserById($id)
    {
        try {
            $query = 'SELECT * FROM users WHERE id = :id';
            $st = $this->pdo->prepare($query);
            $st->bindValue(':id', $id);
            $st->execute();
            $st->setFetchMode(PDO::FETCH_CLASS, 'User');
            $user = $st->fetch();
            if (!$st->rowCount()) {
                $this->error = 'Пользователя с таким id нет в базе.';

                return false;
            }

            return $user;
        } catch (PDOException $e) {
            $this->error = 'Произошла ошибка.';

            return false;
        }
    }

    /**
     * Обновляем идентификатор сессии по ID пользователя.
     *
     * @param int    $userId    ID пользователя
     * @param string $sessionId ID сессии
     */
    public function updateUserSessionId($userId, $sessionId)
    {
        try {
            $query = 'UPDATE users SET sessionId = :sessionId WHERE id=:id';
            $st = $this->pdo->prepare($query);
            $st->bindValue(':id', $userId);
            $st->bindValue(':sessionId', $sessionId);
            $st->execute();
        } catch (PDOException $e) {
            $this->error = 'Произошла ошибка.';
        }
    }

    /**
     * Get the value of Интерфейс для доступа к базе данных.
     *
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Set the value of Интерфейс для доступа к базе данных.
     *
     * @param PDO pdo
     *
     * @return self
     */
    public function setPdo(PDO $pdo)
    {
        $this->pdo = $pdo;

        return $this;
    }

    /**
     * Get the value of Переменная для сохранения ошибки.
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set the value of Переменная для сохранения ошибки.
     *
     * @param string error
     *
     * @return self
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }
}
