<?php

namespace App\Controller;

use App\Entity\Conta;
use App\Form\ContaType;
use App\Repository\AgenciaRepository;
use App\Repository\BancoRepository;
use App\Repository\ContaRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/conta')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ContaController extends AbstractController
{
    // #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'app_conta_index', methods: ['GET'])]
    public function index(ContaRepository $contaRepository): Response
    {
        $contas = null;
        if ($this->isGranted('ROLE_SYS_ADMIN') || $this->isGranted('ROLE_ADMIN_BANCO')) {
            $contas = $contaRepository->findAll();
        }else if($this->isGranted('ROLE_ADMIN_AGENCIA') || $this->isGranted('ROLE_GERENTE')) {
            $contas = $contaRepository->findByAgencia(1); 
        }else if($this->isGranted('ROLE_USER')) {
            $contas = $contaRepository->findByUser($this->getUser());
        }

        return $this->render('conta/index.html.twig', [
            'contas' => $contas,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'app_conta_new', methods: ['GET', 'POST'])]
    public function new(Request $request, 
                        ContaRepository $contaRepository,
                        BancoRepository $bancoRepository,
                        AgenciaRepository $agenciaRepository
                        ): Response
    {
        $conta = new Conta();
        $form = $this->createForm(ContaType::class, $conta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conta->setCreatedAt(new \Datetime());
            $conta->setUser($this->getUser());
            $contaRepository->save($conta, true);

            return $this->redirectToRoute('app_conta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conta/new.html.twig', [
            'conta' => $conta,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[IsGranted(Conta::SHOW, 'conta')]
    #[Route('/{conta}', name: 'app_conta_show', methods: ['GET'])]
    public function show(Conta $conta): Response
    {
        return $this->render('conta/show.html.twig', [
            'conta' => $conta,
        ]);
    }

    // #[IsGranted('ROLE_GERENTE')]
    #[IsGranted(Conta::UPDATE, 'conta')]
    #[Route('/{conta}/edit', name: 'app_conta_edit', methods: ['GET', 'POST'])]
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

    #[IsGranted('ROLE_ADMIN_AGENCIA')]
    #[Route('/{conta}', name: 'app_conta_delete', methods: ['POST'])]
    public function delete(Request $request, Conta $conta, ContaRepository $contaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conta->getId(), $request->request->get('_token'))) {
            $contaRepository->remove($conta, true);
        }

        return $this->redirectToRoute('app_conta_index', [], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted('ROLE_GERENTE')]
    #[Route('/{conta}/block', name: 'app_conta_block', methods: ['GET', 'POST'])]
    public function block(Request $request, Conta $conta, ContaRepository $contaRepository): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$conta->getId(), $request->request->get('_token'))) {
        //     $contaRepository->remove($conta, true);
        // }

        return $this->redirectToRoute('app_conta_index', [], Response::HTTP_SEE_OTHER);
    }
}
