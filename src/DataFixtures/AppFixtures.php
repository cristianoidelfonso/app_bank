<?php

namespace App\DataFixtures;

use App\Entity\Agencia;
use App\Entity\Banco;
use App\Entity\User;
use App\Repository\BancoRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private BancoRepository $bancoRepository)
    {

    }
    
    public function load(ObjectManager $manager): void
    {
        // Criando e persistindo Users
        $user = new User();
        $user->setEmail('cristiano@email.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, '123456'));
        $user->setCreatedAt(new \DateTime());
        $user->setRoles(["ROLE_SYS_ADMIN"]);
        $manager->persist($user);

        $user1 = new User();
        $user1->setEmail('sys_admin@email.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, '1231'));
        $user1->setCreatedAt(new \DateTime());
        $user1->setRoles(["ROLE_SYS_ADMIN"]);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('admin_banco@email.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, '1232'));
        $user2->setCreatedAt(new \DateTime());
        $user2->setRoles(["ROLE_ADMIN_BANCO"]);
        $manager->persist($user2);
        
        $user3 = new User();
        $user3->setEmail('admin_agencia@email.com');
        $user3->setPassword($this->passwordHasher->hashPassword($user3, '1233'));
        $user3->setCreatedAt(new \DateTime());
        $user3->setRoles(["ROLE_ADMIN_AGENCIA"]);
        $manager->persist($user3);
        
        $user4 = new User();
        $user4->setEmail('gerente@email.com');
        $user4->setPassword($this->passwordHasher->hashPassword($user4, '1234'));
        $user4->setCreatedAt(new \DateTime());
        $user4->setRoles(["ROLE_GERENTE"]);
        $manager->persist($user4);

        // Criando e persistindo Bancos
        $banco1 = new Banco();
        $banco1->setNome('Caixa Economica Federal');
        $banco1->setCreatedAt(new \Datetime());
        $banco1->setCreatedByUser($user1);
        $manager->persist($banco1);

        // $banco2 = new Banco();
        // $banco2->setNome('Banco do Brasil');
        // $banco2->setCreatedAt(new \Datetime());
        // $banco2->setCreatedByUser($user1);
        // $manager->persist($banco2);

        // $banco3 = new Banco();
        // $banco3->setNome('Banco Bradesco');
        // $banco3->setCreatedAt(new \Datetime());
        // $banco3->setCreatedByUser($user2);
        // $manager->persist($banco3);

        // $banco4 = new Banco();
        // $banco4->setNome('Banco Santander');
        // $banco4->setCreatedAt(new \Datetime());
        // $banco4->setCreatedByUser($user2);
        // $manager->persist($banco4);

        // Criando e persistindo Agências
        // $bancos = [$banco1, $banco2, $banco3, $banco4];
        // foreach($bancos as $banco) {
            for($i = 1; $i <= 10; $i++) {
                $agencia = new Agencia();
                $agencia->setNome('Agência 000'.$i);
                $agencia->setBanco($banco1);
                $agencia->setCreatedAt(new \Datetime());
                $agencia->setCreatedByUser($user1);
                $manager->persist($agencia);
            }
        // }

        $manager->flush();
    }
}
