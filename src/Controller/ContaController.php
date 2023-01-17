<?php

namespace App\Controller;

use App\Entity\Conta;
use App\Form\ContaType;
use App\Repository\ContaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/conta')]
class ContaController extends AbstractController
{
    #[Route('/', name: 'app_conta_index', methods: ['GET'])]
    public function index(ContaRepository $contaRepository): Response
    {
        return $this->render('conta/index.html.twig', [
            'contas' => $contaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_conta_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContaRepository $contaRepository): Response
    {
        $conta = new Conta();
        $form = $this->createForm(ContaType::class, $conta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conta->setCreatedAt(new \Datetime());
            $contaRepository->save($conta, true);

            return $this->redirectToRoute('app_conta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conta/new.html.twig', [
            'conta' => $conta,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conta_show', methods: ['GET'])]
    public function show(Conta $conta): Response
    {
        return $this->render('conta/show.html.twig', [
            'conta' => $conta,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_conta_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conta $conta, ContaRepository $contaRepository): Response
    {
        $form = $this->createForm(ContaType::class, $conta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conta->setUpdatedAt(new \Datetime());
            $contaRepository->save($conta, true);

            return $this->redirectToRoute('app_conta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conta/edit.html.twig', [
            'conta' => $conta,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conta_delete', methods: ['POST'])]
    public function delete(Request $request, Conta $conta, ContaRepository $contaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conta->getId(), $request->request->get('_token'))) {
            $contaRepository->remove($conta, true);
        }

        return $this->redirectToRoute('app_conta_index', [], Response::HTTP_SEE_OTHER);
    }
}