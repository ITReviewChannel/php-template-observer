<?php

namespace ITReviewChannel;

/**
 * Интерфейс наблюдаемого объекта.
 *
 * @package ITReview
 */
interface SubjectInterface
{
    /**
     * Оповещение наблюдателей.
     */
    public function notifyObservers(): void;

    /**
     * Добавление наблюдателя.
     *
     * @param  ObserverInterface  $observer  Наблюдатель.
     */
    public function addObserver(ObserverInterface $observer): void;

    /**
     * Удаление наблюдателя.
     *
     * @param  ObserverInterface  $observer  Наблюдатель.
     */
    public function removeObserver(ObserverInterface $observer): void;
}

/**
 * Интерфейс наблюдателя.
 *
 * @package ITReview
 */
interface ObserverInterface
{
    /**
     * Обновление состояния.
     *
     * @param  SubjectInterface  $subject  Наблюдаемый объект.
     */
    public function update(SubjectInterface $subject): void;
}

/**
 * Камера.
 *
 * @package ITReview
 */
final class Camera implements ObserverInterface
{
    /**
     * @inheritDoc
     */
    public function update(SubjectInterface $subject): void
    {
        echo 'Камера просто наблюдает.' . PHP_EOL;
    }
}

/**
 * Враг.
 *
 * @package ITReview
 */
final class Enemy implements ObserverInterface
{
    /**
     * @var string $name Имя.
     */
    private string $name;
    /**
     * @var int $damage Урон.
     */
    private int $damage;

    /**
     * Конструктор.
     *
     * @param  string  $name    Имя.
     * @param  int     $damage  Урон.
     */
    public function __construct(string $name, int $damage)
    {
        $this->name = $name;
        $this->damage = $damage;
    }

    /**
     * {@inheritDoc}
     */
    public function update(SubjectInterface $subject): void
    {
        $subject->takeDamage($this->damage);

        echo $this->name . ' нанес урон в размере ' . $this->damage . PHP_EOL;
    }
}

/**
 * Игрок.
 *
 * @package ITReview
 */
final class Gamer implements SubjectInterface
{
    /**
     * @var string $name Имя.
     */
    private string $name;
    /**
     * @var ObserverInterface[] $observers Наблюдатели.
     */
    private array $observers;
    /**
     * @var int $health Уровень здоровья.
     */
    private int $health;

    /**
     * Конструктор.
     *
     * @param  string  $name  Имя.
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        $this->health = 100;
        $this->observers = [];
    }

    /**
     * {@inheritDoc}
     */
    public function addObserver(ObserverInterface $observer): void
    {
        array_push($this->observers, $observer);
    }

    /**
     * {@inheritDoc}
     */
    public function removeObserver(ObserverInterface $observer): void
    {
        foreach ($this->observers as $key => $currentEnemy) {
            if ($currentEnemy == $observer) {
                unset($this->observers[$key]);
                return;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function notifyObservers(): void
    {
        foreach ($this->observers as $enemy) {
            $enemy->update($this);
        }
    }

    /**
     * Получение урона.
     *
     * @param  int  $damage  Урон.
     */
    public function takeDamage(int $damage): void
    {
        $this->health -= $damage;
    }
}

$gamer = new Gamer('Игрок');

$enemyFirst = new Enemy('Враг 1', 1);
$enemySecond = new Enemy('Враг 2', 2);
$enemyThird = new Enemy('Враг 3', 3);

$gamer->addObserver($enemyFirst);
$gamer->addObserver($enemySecond);
$gamer->addObserver($enemyThird);

$camera = new Camera();
$gamer->addObserver($camera);

$gamer->notifyObservers();

print_r($gamer);



