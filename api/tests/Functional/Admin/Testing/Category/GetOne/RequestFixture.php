<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Testing\Category\GetOne;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Role;
use App\Auth\Test\Builder\UserBuilder;
use App\Testing\Entity\Category\Category;
use App\Testing\Entity\Category\CategoryId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use DomainException;
use Symfony\Component\String\Slugger\AsciiSlugger;

final class RequestFixture extends AbstractFixture
{
    public const string CATEGORY_ID = '753ac2cb-6f03-4c7d-a68c-f14646503c67';
    public const string CATEGORY_PARENT_ID = '1d655786-d282-4b08-ae39-257408dd7ed9';
    public const string CATEGORY_NAME = 'Охрана труда';
    public const string CATEGORY_PARENT_NAME = 'Другое название';

    public const string ADMIN_EMAIL = 'admin@mail.ru';
    public const string ADMIN_PASSWORD = 'admin';

    public const string USER_EMAIL = 'user@mail.ru';
    public const string USER_PASSWORD = 'user';

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

        $child = new Category(
            new CategoryId(self::CATEGORY_ID),
            self::CATEGORY_NAME,
            'Category description',
            $this->generateSlug(self::CATEGORY_NAME),
            $parent->getId()->getValue(),
        );
        $manager->persist($child);

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
