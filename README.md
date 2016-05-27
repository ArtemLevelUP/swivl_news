swivl_news
==========

A Symfony project created on May 27, 2016, 12:29 pm.

создаем проект symfony:
symfony new swivl_news

настраиваем доступ к БД в app/config/parameters.yml

создаем новостной бандл:
bin/console generate:bundle --namespace=AcmeNewsBundle --dir=src --format=annotation

cоздаем сущность новости:
bin/console doctrine:generate:entity
The Entity shortcut name: AcmeNewsBundle:Post
Configuration format: annotation

поле для заголовка
New field name (press <return> to stop adding fields): title
Field type [string]:
Field length [255]:
Is nullable [false]:
Unique [false]:

поле для краткого описания
New field name (press <return> to stop adding fields): announce
Field type [string]: text
Is nullable [false]:
Unique [false]:

поле для полного текста
New field name (press <return> to stop adding fields): description
Field type [string]: text
Is nullable [false]:
Unique [false]:

статус публикации
New field name (press <return> to stop adding fields): publishStatus
Field type [string]: boolean
Is nullable [false]:
Unique [false]:

дата создания
New field name (press <return> to stop adding fields): created
Field type [string]: datetime
Is nullable [false]:
Unique [false]:

В созданном файле src/AcmeNewsBundle/Entity/Post.php добавляем конструктор для автоматического заполнения
значения created при добавлении новости
/**
 * Post constructor
 */
public function __construct()
{
    $this->created = new \DateTime();
}

Создаем контроллер src/AcmeNewsBundle/Controller/NewsController.php для отображения web/xml-списка новостей
и страницы под каждую новость

Twig-шаблоны помещаем в папку src/AcmeNewsBundle/Resources/views/News

Настраиваем маршруты в src/AcmeNewsBundle/Resources/config/routing.yml

Так, как есть необходимость получать из БД определенное к-во случайных записей и невозможности сделать это
через EntityManager или QueryBuilder, создаем функцию RAND в src/AcmeNewsBundle/DQL/RandFunction.php с добавлением
метода в src/AcmeNewsBundle/Repository/PostRepository.php

XML вывод пришлось делать с использованием Response шаблонно, т.к. подходящая для этой цели зависимость
michalcarson/symfony-xml-response версии 1.1.0 (самая новая) оказалась несовместима с Symfony 3.0

Для создания первоначальной базы новостей использовались фикстуры, метод для автонаполнения: src/AcmeNewsBundle/DataFixtures/ORM/LoadPostData.php

Так же предполагалось внедрить Doctrine миграции, но для выполнения тестового задания они не пригодятся, т.к.
структура БД создается один раз, а наполнение происходит из фикстур