<?php
namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CategoryController extends AbstractController{
    /**
     * @Route("/admin/categories", name="admin_categories")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getCategorieList(){
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render(
            'admin/category/categorieList.html.twig',
            array(
                'categories' => $categories
            )
        );
    }

    /**
     * @Route("/admin/category/{id}", name="admin_category")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getSelectCategory(Request $request, $id=null){
        if($id != null){
            $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
            $form = $this->createForm(CategoryType::class, $category);
        }

        if($id == null){
            $category = new Category();
            $form = $this->createForm(CategoryType::class, $category);
        }

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush($category);

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render(
            'admin/category/categoryCrud.html.twig',
            array(
                'form' => $form->createView(),
                'category' => $category
            )
        );
    }
}