<?php
/**
 * Add admin, client and his users
 */
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@bilemo.com');
        $admin->setRoles(['ROLE_BILEMO']);
        $password = $this->hasher->hashPassword($admin, 'pass_1234');
        $admin->setPassword($password);
        $manager->persist($admin);
        
        $client = new User();
        $client->setUsername('client');
        $client->setEmail('client@client.com');
        $client->setRoles(['ROLE_CLIENT']);
        $password = $this->hasher->hashPassword($client, 'pass_1234');
        $client->setPassword($password);
        $manager->persist($client);

        for ($i = 0; $i < 15; $i++) {
            
            $user = new User();

            $user->setUsername('username' . $i);

            $user->setEmail('sample-user' . $i .'@client.com');

            $user->setRoles(['ROLE_USER']);

            $user->setClient($client);

            $client->setUser($user);

            $password = $this->hasher->hashPassword($user, 'pass_1234');
            $user->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();
        
    }

}
