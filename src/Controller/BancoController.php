<?php

namespace App\Controller;

use App\Entity\Banco;
use App\Form\BancoType;
use App\Repository\BancoRepository;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/banco')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
// #[IsGranted('ROLE_ADMIN_BANCO')]
class BancoController extends AbstractController
{
    public function __construct(private Security $security)
    {

    }
    
    #[Route('/', name: 'app_banco_index', methods: ['GET'])]
    public function index(BancoRepository $bancoRepository): Response
    {
        $bancos = $bancoRepository->findAll();
        return $this->render('banco/index.html.twig', [
            'bancos' => $bancos,
        ]);

    }

    // #[IsGranted('ROLE_SYS_ADMIN')]
    #[Route('/new', name: 'app_banco_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BancoRepository $bancoRepository): Response
    {
        try{

            if(!in_array("ROLE_SYS_ADMIN", $this->getUser()->getRoles() )){
                throw new AccessDeniedException("Access denied.");
            }

            $banco = new Banco();
            $form = $this->createForm(BancoType::class, $banco);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $banco->setCreatedAt(new \DateTime());
                $banco->setCreatedByUser($this->getUser());
                $bancoRepository->save($banco, true);

                $this->addFlash('success', 'Resource created successfully.');

                return $this->redirectToRoute('app_banco_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('banco/new.html.twig', [
                'banco' => $banco,
                'form' => $form,
            ]);

        }
        catch(AccessDeniedException $ex){
            $this->addFlash('notice', "You don't have permission for this operation.");
            return $this->redirectToRoute('app_banco_index', [], Response::HTTP_SEE_OTHER);
        }
        catch(Exception $ex){
            // $this->addFlash('error', $ex->getMessage());
            $this->addFlash('error', 'An unexpected error has occurred. Contact your system administrator.');
            return $this->redirectToRoute('app_banco_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/{banco}', name: 'app_banco_show', methods: ['GET'])]
    public function show(Banco $banco): Response
    {
        return $this->render('banco/show.html.twig', [
            'banco' => $banco,
            'id' => $banco->getId(),
        ]);
    }

    // #[IsGranted(Banco::EDIT, 'banco')]
    #[Route('/{banco}/edit', name: 'app_banco_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Banco $banco, BancoRepository $bancoRepository): Response
    {
        try{
            if(!($this->security->isGranted('ROLE_SYS_ADMIN') || ($this->security->isGranted('ROLE_ADMIN_BANCO') && $banco->getCreatedByUser() === $this->getUSer())) ) {
                throw $this->createAccessDeniedException('You don\'t have permission to edit this resource.');
            }

            $form = $this->createForm(BancoType::class, $banco);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $banco->setUpdatedAt(new \Datetime());
                $bancoRepository->save($banco, true);

                return $this->redirectToRoute('app_banco_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('banco/edit.html.twig', [
                'banco' => $banco,
                'form' => $form,
            ]);
        
        }catch( AccessDeniedException $ex){
            $this->addFlash('notice', $ex->getMessage());
            return $this->redirect($request->headers->get('referer'));
        }
    }

    #[IsGranted('ROLE_SYS_ADMIN')]
    #[Route('/{banco}', name: 'app_banco_delete', methods: ['POST'])]
    public function delete(Request $request, Banco $banco, BancoRepository $bancoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$banco->getId(), $request->request->get('_token'))) {
            $bancoRepository->remove($banco, true);
        }

        return $this->redirectToRoute('app_banco_index', [], Response::HTTP_SEE_OTHER);
    }
}
