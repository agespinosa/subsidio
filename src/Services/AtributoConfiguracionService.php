<?php


namespace App\Services;


use App\Entity\AtributoConfiguracion;
use App\Repository\AtributoConfiguracionRepository;
use Psr\Log\LoggerInterface;

class AtributoConfiguracionService
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var AtributoConfiguracionRepository
     */
    private $atributoConfiguracionRepository;
    
    public function __construct(LoggerInterface $logger, AtributoConfiguracionRepository $atributoConfiguracionRepository)
    {
        
        $this->logger = $logger;
        $this->atributoConfiguracionRepository = $atributoConfiguracionRepository;
    }
    
    public function getDiasFeriados():array{
        $diasFeriados = array();
        
        /** @var AtributoConfiguracion $configuracion */
        $configuracion
            = $this->atributoConfiguracionRepository->findAtributoConfiguracionByClave(
                'diasFeriados'
        );
        
        if(!is_null($configuracion)){
            $diasFeriados = explode(",", $configuracion->getValor() );
        }
        
        return $diasFeriados;
        
    
    }
    
    /**
     * Metodo getDiasHabiles
     *
     * Permite devolver un arreglo con los dias habiles
     * entre el rango de fechas dado excluyendo los
     * dias feriados dados (Si existen)
     *
     * @param \DateTime $fechainicio Fecha de inicio en formato Y-m-d
     * @param \DateTime $fechafin Fecha de fin en formato Y-m-d
     * @param array $diasferiados Arreglo de dias feriados en formato Y-m-d
     * @return array $diashabiles Arreglo definitivo de dias habiles
     */
    public function getDiasHabiles($fechainicio, $fechafin, $diasferiados = array())
    {
        // Obtengo dias feriados configurados
        $diasFeriadosConfigurados = $this->getDiasFeriados();
        if((is_null($diasferiados) || count($diasferiados) == 0
                && ( !is_null($diasFeriadosConfigurados) && count($diasFeriadosConfigurados) > 0 ))){
            $diasferiados = $diasFeriadosConfigurados;
        }
    
        $diasFeriadosCompletosByAnio = array();
        $añoFin = $fechafin->format('Y');
        // Agrego el año de las fechas fin a los dias feriados
        foreach ($diasferiados as $diaferiado) {
            array_push($diasFeriadosCompletosByAnio, $diaferiado."-".$añoFin);
        }
    
        //fecha usada temporal para correrme al lunes si caigo domingo o sabado el 10 del mes
        $fechafinClone = clone $fechafin;
        // Si la fecha fin es Sabado o Domingo, sumo dias, para llegar al lunes siguiente
        if(date('N', $fechafinClone->getTimestamp()) == 6){
            //sabado
            $fechafin->add(new \DateInterval('P2D'));
        }
        if(date('N',  $fechafinClone->getTimestamp()) == 7){
            //doming
            $fechafin->add(new \DateInterval('P1D'));
        }
        
        // Convirtiendo en timestamp las fechas
        //$fechainicio->sub(new \DateInterval('P1D'));
        $fechainicio = $fechainicio->getTimestamp();
        $fechafin = $fechafin->getTimestamp();
        
        
        // Incremento en 1 dia
        $diaincremento = 24 * 60 * 60;
        
        // Arreglo de dias habiles, inicianlizacion
        $diashabiles = array();
        
        // Se recorre desde la fecha de inicio a la fecha fin, incrementando en 1 dia
        for ($midia = $fechainicio; $midia <= $fechafin; $midia += $diaincremento) {
            // Si el dia indicado, no es sabado o domingo es habil
            if (!in_array(date('N', $midia), array(6, 7))) { // DOC: http://www.php.net/manual/es/function.date.php
                // Si no es un dia feriado entonces es habil
                if (!in_array(date('Y-m-d', $midia), $diasFeriadosCompletosByAnio)) {
                    array_push($diashabiles, date('Y-m-d', $midia));
                }
            }else{
                // sabado o domingo sumo 1 dia
                $midia += $diaincremento;
            }
        }
        
        return $diashabiles;
    }
    
    
}