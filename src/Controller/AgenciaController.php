<?php

namespace App\Controller;

use App\Entity\Agencia;
use App\Entity\Banco;
use App\Form\AgenciaType;
use App\Repository\AgenciaRepository;
use App\Repository\BancoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/agencia')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
// #[IsGranted('ROLE_ADMIN_AGENCIA')]
class AgenciaController extends AbstractController
{
    #[Route('/', name: 'app_agencia_index', methods: ['GET'])]
    public function index(AgenciaRepository $agenciaRepository): Response
    {
        return $this->render('agencia/index.html.twig', [
            'agencias' => $agenciaRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN_BANCO')]
    #[Route('/new', name: 'app_agencia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AgenciaRepository $agenciaRepository, BancoRepository $bancoRepository): Response
    {
        $bancos = $bancoRepository->findAll();
        dump($bancos);
        
        $agencia = new Agencia();
        $form = $this->createForm(AgenciaType::class, $agencia);

        $form->add('banco', ChoiceType::class, [
            'choices'=> $bancos,
            'choice_label' => function (?Banco $banco) {
                return $banco ? strtoupper($banco->getNome()) : '';
            }, 
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agencia->setCreatedAt(new \Datetime());
            $agenciaRepository->save($agencia, true);

            return $this->redirectToRoute('app_agencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agencia/new.html.twig', [
            'agencia' => $agencia,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}', name: 'app_agencia_show', methods: ['GET'])]
    public function show(Agencia $agencia): Response
    {
        return $this->render('agencia/show.html.twig', [
            'agencia' => $agencia,
        ]);
    }

    #[IsGranted('ROLE_ADMIN_BANCO')]
    #[Route('/{id}/edit', name: 'app_agencia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agencia $agencia, AgenciaRepository $agenciaRepository): Response
    {
        $form = $this->createForm(AgenciaType::class, $agencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agencia->setUpdatedAt(new \Datetime());
            $agenciaRepository->save($agencia, true);

            return $this->redirectToRoute('app_agencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agencia/edit.html.twig', [
            'agencia' => $agencia,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN_BANCO')]
    #[Route('/{id}', name: 'app_agencia_delete', methods: ['POST'])]
    public function delete(Request $request, Agencia $agencia, AgenciaRepository $agenciaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agencia->getId(), $request->request->get('_token'))) {
            $agenciaRepository->remove($agencia, true);
        }

        return $this->redirectToRoute('app_agencia_index', [], Response::HTTP_SEE_OTHER);
    }
}
