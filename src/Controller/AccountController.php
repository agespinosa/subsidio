<?php

namespace App\Controller;

use App\Security\LoginFormAuthentication;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @IsGranted("ROLE_USER")
 */
class AccountController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;


    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

    }

    /**
     * @Route("/account", name="app_account_index")
     */
    public function index()
    {
        $this->logger->debug("Account manage access by ". $this->getUser()->getEmail());
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
}
