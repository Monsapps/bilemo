<?php
/**
 * Add admin, client and his users
 */
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $admin = new User('admin', 'admin@bilemo.com', ['ROLE_BILEMO']);
        $manager->persist($admin);
        
        $client = new User('client', 'client@client.com', ['ROLE_CLIENT']);

        for ($i = 0; $i < 15; $i++) {
            
            $user = new User();

            $user->setUsername('username' . $i);

            $user->setEmail('sample-user' . $i .'@client.com');

            $user->setRoles(['ROLE_USER']);

            $user->setClient($client);

            $client->setUser($user);

            $manager->persist($user);
        }

        $manager->flush();
        
    }

}
