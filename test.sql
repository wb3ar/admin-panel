-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Янв 31 2017 г., 17:26
-- Версия сервера: 10.1.19-MariaDB
-- Версия PHP: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `yearOfBirth` year(4) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `isAdmin` tinyint(4) DEFAULT NULL,
  `sessionId` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `yearOfBirth`, `email`, `passwordHash`, `isAdmin`, `sessionId`) VALUES
(20, 'Антон', 'Соколов', 1973, 'anton@test.ru', '$2y$10$sJ2KgRcN4/VQ0l/O.aE0BuG2CbNHf3yMCc02y9aOFMyGP.M.23hY6', 1, 'stmsf7h3ov03te1290p3kpn5g3'),
(21, 'Марина', 'Цветаева', 1989, 'cvetaeva@test.ru', '$2y$10$KEwXOB6S7Phkk0I24cT6teCoTse6Yc1qbCZAcwR3j4HKXyBGYRXhS', 0, NULL),
(22, 'Марина', 'Лебедева', 1968, 'lebedeva@test.ru', '$2y$10$fjRdwiyoyK42weKIymjbPeoNjc5b/DPFtZawriSHkcfFVs1.G.jgG', 0, NULL),
(23, 'Николай', 'Золотарев', 1989, 'nikola@test.ru', '$2y$10$pXa7LJPPian5BkNVVYVBj.NYN/adBa2ArZ/roO8RlrQMstPuir0Yu', NULL, '08vm7mjd8vd0v5p11kmplre3o4'),
(24, 'Галина', 'Муромцева', 1979, 'galina@test.ru', '$2y$10$CGX3u09YKzNIlJZKo4vmzuZuE5tzhvpeH1kgd5Jee68MyO8Y0TuJK', NULL, '08vm7mjd8vd0v5p11kmplre3o4'),
(25, 'Алексей', 'Никонов', 1996, 'leha@test.ru', '$2y$10$dZqVSzZCLStYpHwgVTyvc.IHJuEIzEAkGkMIsgnyKZ6stkCTNkelS', NULL, '08vm7mjd8vd0v5p11kmplre3o4'),
(26, 'Павел', 'Быков', 1974, 'pavel@test.ru', '$2y$10$vaDtVmRul3lQsuDSH/zSVOuifdYLr32f9bvFniLxCEvfBOO9W1jzq', NULL, '08vm7mjd8vd0v5p11kmplre3o4'),
(28, 'Тимофей', 'Градов', 1985, 'timofei@test.ru', '$2y$10$62WEpYKdUArIxY7G94ArqurTKJkdnVBr2U1HMgQ91OFqrXVZKj4PG', NULL, '08vm7mjd8vd0v5p11kmplre3o4'),
(29, 'Алина', 'Громова', 1995, 'alina@test.ru', '$2y$10$s/lZEB2ucKG8i14/8mX9ou1g41NWDMCi1Qx8Nfon7WXy70LHi6oQi', NULL, 'stmsf7h3ov03te1290p3kpn5g3'),
(30, 'Валерия', 'Сидорова', 1975, 'valeria@test.ru', '$2y$10$XaCtfT0oV8A1j/iIs/ssOetrO5.JW6/glTaXeTbMSEAOW0nuED6fS', NULL, '08vm7mjd8vd0v5p11kmplre3o4'),
(33, 'Леонид', 'Соловьев', 1979, 'leonid@test.ru', '$2y$10$y7lVdUinmBNxcT/Z7HfiFOdVU8l4sXuB.u2lR0jnfJRcVr9MgWNpe', NULL, 'stmsf7h3ov03te1290p3kpn5g3'),
(36, 'Мария', 'Конкина', 1978, 'konkina@test.ru', '$2y$10$KwpZGyVUULPazGLds5XC7et1HNG5CUWZPQF1jH/3Ob4oBdV52C5Eu', NULL, '3n257po6o5fp72o410ft1f6n80'),
(38, 'Галина', 'Шумкина', 1989, 'shumkina@test.ru', '$2y$10$jevjZTJ0PSzEMhiQLpXIO.3qwrGoof1uZoBMY0yM1kNTPY03XLCAm', NULL, '3n257po6o5fp72o410ft1f6n80'),
(39, 'Админ', 'Админ', 1986, 'admin@test.ru', '$2y$10$ls1L/GxKkLhQsJ3N8UtV4.j4aYPrMa/vpNaTMSmDqEZpjepztlCfS', 1, '3n257po6o5fp72o410ft1f6n80');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id` (`id`,`sessionId`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
