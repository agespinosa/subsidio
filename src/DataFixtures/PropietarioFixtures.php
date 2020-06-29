<?php

namespace App\DataFixtures;

use App\Entity\Propietario;
use Doctrine\Persistence\ObjectManager;

class PropietarioFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Propietario::class, 100, function (Propietario $propietario) {
            $propietario->setRenspa($this->faker->numerify('##.###.#.#.#####/##'));
            $propietario->setRazonSocial($this->faker->company);
            $propietario->setCuit($this->faker->numerify('##-########-#'));
            $propietario->setDomicilio($this->faker->address);
            $propietario->setTelefono($this->faker->phoneNumber);
            $propietario->setCodigoPostal($this->faker->numberBetween($min = 1111, $max = 9999));
        });

        $manager->flush();
    }


}
