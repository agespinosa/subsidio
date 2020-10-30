<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Security\LoginFormAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $passwordEncoder;
    /**
     * @var Security
     */
    private $security;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,
                                Security $security)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(){
        throw new \Exception('Will be intercepted before getting here');
    }
    
    /**
     * @Route("/perfil", name="app_perfil")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function perfil(Request $request){
        $currentUser = $this->security->getUser();
        $form = $this->createForm(ProfileType::class, $currentUser);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($currentUser);
            $em->flush();
    
            $this->addFlash('successMessage',"Usuario Actualizado");
        }
    
        return $this->render('security/profile.html.twig', [
            'currentUser' => $currentUser,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/register", name="app_register")
     * @IsGranted("ROLE_ADMIN")
     */
    public function register(Request $request, GuardAuthenticatorHandler $guardHandler,
                             LoginFormAuthenticator $formAuthenticator){
        if($request->isMethod('POST')){
            $user= new User();
            $user->setFirstname($request->request->get('firstName'));
            $user->setEmail($request->request->get('email'));
            $user->setRoles([$request->request->get('rol')]);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $request->request->get('password')
            ));
            $em= $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return  $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }
        return $this->render('security/register.html.twig');
    }

}
