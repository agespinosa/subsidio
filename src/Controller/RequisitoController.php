<?php

namespace App\Controller;

use App\Entity\ExcelIngreso;
use App\Entity\Requisito;
use App\Entity\SubsidioPagoProveedores;
use App\Exception\SimpleMessageException;
use App\Form\RequisitoType;
use App\Repository\ExcelIngresoRepository;
use App\Repository\RequisitoRepository;
use App\Repository\SubsidioPagoProveedoresRepository;
use App\Services\ExcelService;
use App\Services\SubsidioService;
use App\Services\ValidationService;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/")
 * @IsGranted("ROLE_ADMIN")
 */
class RequisitoController extends AbstractController
{
    
    /**
     * @var ExcelReaderService
     */
    private $excelService;
    /**
     * @var ExcelIngresoRepository
     */
    private $excelIngresoRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var RequisitoRepository
     */
    private $requisitoRepository;
    /**
     * @var SubsidioPagoProveedoresRepository
     */
    private $subsidioPagoProveedoresRepository;
    /**
     * @var ValidationService
     */
    private $validationService;
    /**
     * @var SubsidioService
     */
    private $subsidioService;



    /**
     * RequisitoController constructor.
     * @param ExcelService $excelService
     * @param ExcelIngresoRepository $excelIngresoRepository
     * @param LoggerInterface $logger
     * @param RequisitoRepository $requisitoRepository
     * @param SubsidioPagoProveedoresRepository $subsidioPagoProveedoresRepository
     * @param ValidationService $validationService
     * @param SubsidioService $subsidioService
     */
    public function __construct(ExcelService $excelService, ExcelIngresoRepository $excelIngresoRepository,
                                LoggerInterface $logger,
                                RequisitoRepository $requisitoRepository,
                                SubsidioPagoProveedoresRepository $subsidioPagoProveedoresRepository,
                                ValidationService $validationService,
                                SubsidioService $subsidioService
                                )
    {
        $this->excelService = $excelService;
        $this->excelIngresoRepository = $excelIngresoRepository;
        $this->logger = $logger;
        $this->requisitoRepository = $requisitoRepository;
        $this->subsidioPagoProveedoresRepository = $subsidioPagoProveedoresRepository;
        $this->validationService = $validationService;
        $this->subsidioService = $subsidioService;
    }
    
    /**
     *  @Route("/" , name="requisito_index", methods={"GET"})
     */
    public function index( Request $request, PaginatorInterface $paginator): Response
    {
        $publicUploadsPathBase = $this->getParameter('public_uploads_path_base');
        
        $q = $request->query->get('q');

        $queryBuilder =$this->requisitoRepository->getWithSearchQueryBuilder($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('requisito/index.html.twig', [
            'pagination' => $pagination,
                'publicUploadsPathBase' => $publicUploadsPathBase
        ]);

    }

    /**
     * @Route("/new", name="requisito_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $requisito = new Requisito();
        
        $form = $this->createForm(RequisitoType::class, $requisito);
        $form->handleRequest($request);

        $this->logger->debug("RequisitoController, generar nuevo subsidio ");
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('filename')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                $this->logger->debug("Subiendo Excel , nuevo subsidio ".$newFilename);
                try {
                    $file->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $message = "Error Subiendo Excel , nuevo subsidio ".$e->getMessage();
                    $this->logger->error($message);
                    $this->addFlash('errorMessage', $message);
                }
    
                $excelIngresosPath = $this->getParameter('excel_directory_relative_path');
                $relativePath = $excelIngresosPath.'/'.$newFilename;
                $requisito->setFileExcelOriginalPath($relativePath);
                $requisito->setFileExcelOriginalName($newFilename);
                
                $filePath =  $this->getParameter('files_directory').'/'.$newFilename;

                $excelIngresos = array();
                try{
                    // Leo el excel y genero el/los ExcelIngreso, uno por cada fila del Excel
                    $excelIngresos = $this->excelService->readFile($filePath);

                }catch (\Exception | FatalError | \RuntimeException $exception){
                    $message = "Error Leyendo Excel ".$exception->getMessage();
                    $this->logger->error($message);
                    $this->addFlash('errorMessage', $message);
                    return $this->render('requisito/new.html.twig', [
                        'requisito' => $requisito,
                        'form' => $form->createView()
                    ]);
                }catch (SimpleMessageException $sm){
                    $message = "Error Leyendo Excel ".$sm->getMessage();
                    $this->logger->error($message);
                    $this->addFlash('errorMessage', $message);
                    return $this->render('requisito/new.html.twig', [
                        'requisito' => $requisito,
                        'form' => $form->createView()
                    ]);
                }

                /** @var ExcelIngreso $excelIngreso */
                foreach ($excelIngresos as $excelIngreso) {
                    $excelIngreso->setRequisito($requisito);
                    $this->excelIngresoRepository->persist($excelIngreso);
                }
            }
            
            // Persiste Requisito y ExcelIngreso
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($requisito);
            $entityManager->flush();

            $this->logger->debug('Archivo subido y procesado correctamente '.$newFilename);
            $this->addFlash('successMessage','Archivo subido y procesado correctamente. Confirme la generacion');
           
         
            return $this->redirectToRoute('requisito_confirmExcelIngreso',
                                                array('id'=> $requisito->getId()));
         
        }

        return $this->render('requisito/new.html.twig', [
            'requisito' => $requisito,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("confirmExcelIngreso/{id}", name="requisito_confirmExcelIngreso", methods={"GET","POST"})
     */
    public function confirmExcelIngreso(Request $request, Requisito $requisito): Response
    {
        $excelIngresos =
            $this->excelIngresoRepository->findBy(
                array(
                    'requisito' => $requisito,
                )
            );
    
        $validationConstraint =
            $this->validationService->getMessageValidation($excelIngresos);
    
        if(!is_null($validationConstraint) && count($validationConstraint)>0) {
            $this->logger->warning(json_encode($validationConstraint));
        }
        
        $requisito->setTotalMontoPesos($this->subsidioService->getTotalAPagar($excelIngresos));
    
        return $this->render('requisito/confirm.html.twig', [
            'requisito' => $requisito,
            'validationConstraint' => $validationConstraint,
            'excelIngresos' => $excelIngresos
        ]);
        
    }
    
    
    
    /**
     * @Route("edit/{id}", name="requisito_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Requisito $requisito): Response
    {
        $form = $this->createForm(RequisitoType::class, $requisito);
        $form->remove('filename');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('requisito_index');
        }

        return $this->render('requisito/edit.html.twig', [
            'requisito' => $requisito,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("delete/{id}", name="requisito_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Requisito $requisito): Response
    {
        if ($this->isCsrfTokenValid('delete'.$requisito->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($requisito);
            $entityManager->flush();
        }

        return $this->redirectToRoute('requisito_index');
    }
    
    /**
     * @Route("show/{id}", name="requisito_show", methods={"GET"})
     * @param Requisito $requisito
     * @return Response
     */
    public function show(Requisito $requisito): Response
    {
        return $this->render('requisito/show.html.twig', [
            'requisito' => $requisito,
        ]);
    }
    
}
