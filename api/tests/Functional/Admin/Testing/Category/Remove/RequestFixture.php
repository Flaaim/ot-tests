<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Testing\Category\Remove;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Role;
use App\Auth\Test\Builder\UserBuilder;
use App\Testing\Entity\Category\Category;
use App\Testing\Entity\Category\CategoryId;
use App\Testing\Entity\Test\TestId;
use App\Testing\Test\Builder\TestBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use DomainException;
use Symfony\Component\String\Slugger\AsciiSlugger;

final class RequestFixture extends AbstractFixture
{
    public const string CATEGORY_PARENT_ID = '753ac2cb-6f03-4c7d-a68c-f14646503c67';
    public const string CATEGORY_CHILD_ID = '1d655786-d282-4b08-ae39-257408dd7ed9';
    public const string CATEGORY_CLEAR_ID = '23cace44-cf7b-49d0-9a4a-52e2322ad82c';
    public const string CATEGORY_PARENT_NAME = 'Охрана труда';
    public const string CATEGORY_CHILD_NAME = 'ЕИСОТ';
    public const string CATEGORY_CLEAR_NAME = 'Работы повышенной опасности';

    public const string ADMIN_EMAIL = 'admin@mail.ru';
    public const string ADMIN_PASSWORD = 'admin';

    public const string USER_EMAIL = 'user@mail.ru';
    public const string USER_PASSWORD = 'user';

    public const string TEST_ID = 'ff1f1a44-f5f5-4956-9a09-feef0d28cedd';
    public const string TEST_NAME = 'Первая помощь';
    public const string TEST_CIPHER = 'ОТ 201.18';

    public function load(ObjectManager $manager): void
    {
        $admin = new UserBuilder()
            ->withEmail(new Email(self::ADMIN_EMAIL))
            ->withPassword(self::ADMIN_PASSWORD)
            ->withRole(Role::admin())
            ->active()
            ->build();
        $manager->persist($admin);

        $user = new UserBuilder()
            ->withEmail(new Email(self::USER_EMAIL))
            ->withPassword(self::USER_PASSWORD)
            ->active()
            ->build();
        $manager->persist($user);

        $parent = new Category(
            new CategoryId(self::CATEGORY_PARENT_ID),
            self::CATEGORY_PARENT_NAME,
            'Category description',
            $this->generateSlug(self::CATEGORY_PARENT_NAME),
        );
        $manager->persist($parent);

        $clearCategory = new Category(
            new CategoryId(self::CATEGORY_CLEAR_ID),
            self::CATEGORY_CLEAR_NAME,
            'Category description',
            $this->generateSlug(self::CATEGORY_CLEAR_NAME),
            $parent->getId()->getValue()
        );
        $manager->persist($clearCategory);

        $child = new Category(
            new CategoryId(self::CATEGORY_CHILD_ID),
            self::CATEGORY_CHILD_NAME,
            'Category description',
            $this->generateSlug(self::CATEGORY_CHILD_NAME),
            $parent->getId()->getValue()
        );
        $manager->persist($child);

        $test = new TestBuilder()
            ->withId(new TestId(self::TEST_ID))
            ->withName(self::TEST_NAME)
            ->withCipher(self::TEST_CIPHER)
            ->withCategoryId(self::CATEGORY_CHILD_ID)
            ->build();
        $manager->persist($test);

        $manager->flush();
    }

    private function generateSlug(string $value): string
    {
        $slugger = new AsciiSlugger();

        $slug = $slugger->slug($value)->lower()->toString();

        if ('' === $slug) {
            throw new DomainException('Cannot generate slug from the given name.');
        }

        return $slug;
    }
}
