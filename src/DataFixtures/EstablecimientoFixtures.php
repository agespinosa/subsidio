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
        $this->createMany(Establecimiento::class, 100, function (Establecimiento $establecimiento){

            $establecimiento->setNombre($this->faker->name);
            $establecimiento->setCantidadHectareas($this->faker->numberBetween(1,1000));
            $establecimiento->setPropietario($this->getRandomReference(Propietario::class));

        });

        $manager->flush();
    }
    public function getDependencies()
    {
        return [PropietarioFixtures::class];
    }
}
