<?php

namespace App\DataFixtures;

use App\Entity\Establecimiento;
use App\Entity\Propietario;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EstablecimientoFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(100, 'main_establecimientos', function() {
            $establecimiento= new Establecimiento();
            $establecimiento->setNombre($this->faker->name);
            $establecimiento->setCantidadHectareas($this->faker->numberBetween(1,1000));
            $establecimiento->setPropietario($this->getRandomReference('main_propietarios'));
            $establecimiento->setIsDeleted($this->faker->boolean(20));
            return $establecimiento;
        });

        $manager->flush();
    }
    public function getDependencies()
    {
        return [PropietarioFixtures::class];
    }
}
