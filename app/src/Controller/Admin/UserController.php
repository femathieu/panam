<?php
namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
    
class UserController extends AbstractController{
    
    /**
     * @Route("/admin/users", name="admin_users")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getUserList(Request $request)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        if(!$users){
            throw $this->createNotFoundException("Aucuns utilisateurs enregistré");
        }
        return $this->render(
            'admin/user/userList.html.twig',
            array('users' => $users)
        );
    }

    /**
     * @Route("/admin/user/{id}", name="admin_user")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getSelectUser(Request $request, $id = null, UserPasswordEncoderInterface $passwordEncoder)
    {      
        $password = true;
        ///Fetch user if we are on update
        if($id != null){
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            $form = $this->createForm(UserType::class, $user);
            $password = false;
        }

        ///Create new user if we are on create
        if($id == null){
            $user = new User();
            $form = $this->createForm(UserType::Class, $user);
        }

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($user->getPlainPassword()){
                ///Encode password
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_users');
        }

        // dump($password);
        // exit();

        return $this->render(
            'admin/user/userCrud.html.twig',
            array(
                'form' => $form->createView(),
                'password' => $password,
                'user' => $user
            )
        );
    }
}