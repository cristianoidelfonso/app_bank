<?php

namespace App\Controller;

use App\Entity\Banco;
use App\Form\BancoType;
use App\Repository\BancoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/banco')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[IsGranted('ROLE_ADMIN_BANCO')]
class BancoController extends AbstractController
{
    #[Route('/', name: 'app_banco_index', methods: ['GET'])]
    public function index(BancoRepository $bancoRepository): Response
    {
        return $this->render('banco/index.html.twig', [
            'bancos' => $bancoRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_SYS_ADMIN')]
    #[Route('/new', name: 'app_banco_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BancoRepository $bancoRepository): Response
    {
        $banco = new Banco();
        $form = $this->createForm(BancoType::class, $banco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $banco->setCreatedAt(new \DateTime());
            $bancoRepository->save($banco, true);

            return $this->redirectToRoute('app_banco_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('banco/new.html.twig', [
            'banco' => $banco,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_banco_show', methods: ['GET'])]
    public function show(Banco $banco): Response
    {
        dump($banco);
        return $this->render('banco/show.html.twig', [
            'banco' => $banco,
            'id' => $banco->getId(),
        ]);
    }

    #[IsGranted(Banco::EDIT, 'banco')]
    #[Route('/{id}/edit', name: 'app_banco_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Banco $banco, BancoRepository $bancoRepository): Response
    {
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
    }

    #[IsGranted('ROLE_SYS_ADMIN')]
    #[Route('/{id}', name: 'app_banco_delete', methods: ['POST'])]
    public function delete(Request $request, Banco $banco, BancoRepository $bancoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$banco->getId(), $request->request->get('_token'))) {
            $bancoRepository->remove($banco, true);
        }

        return $this->redirectToRoute('app_banco_index', [], Response::HTTP_SEE_OTHER);
    }
}
