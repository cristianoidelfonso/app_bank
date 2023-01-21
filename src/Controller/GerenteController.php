<?php

namespace App\Controller;

use App\Entity\Agencia;
use App\Entity\Banco;
use App\Entity\Gerente;
use App\Form\GerenteType;
use App\Repository\AgenciaRepository;
use App\Repository\BancoRepository;
use App\Repository\GerenteRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gerente')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class GerenteController extends AbstractController
{
    #[Route('/', name: 'app_gerente_index', methods: ['GET'])]
    public function index(GerenteRepository $gerenteRepository): Response
    {
        return $this->render('gerente/index.html.twig', [
            'gerentes' => $gerenteRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN_AGENCIA')]
    #[Route('/new', name: 'app_gerente_new', methods: ['GET', 'POST'])]
    public function new(Request $request, 
                        GerenteRepository $gerenteRepository, 
                        AgenciaRepository $agenciaRepository): Response
    {
        try{
            $agencias = null;
            if ($this->isGranted('ROLE_SYS_ADMIN')){
                $agencias = $agenciaRepository->findAll();
            }else if($this->isGranted('ROLE_ADMIN_BANCO')){
                // $agencias = $agenciaRepository->findByBanco();
            }

            $agencias = $agenciaRepository->findAll();
            
            $gerente = new Gerente();
            $form = $this->createForm(GerenteType::class, $gerente);
            $form->add('agencia', ChoiceType::class, [
                'choices' => $agencias,
                'choice_label' => function(?Agencia $agencia){
                    $agenciaBanco = $agencia ? $agencia->getNome() .' - '. $agencia->getBanco()->getNome() : '';
                    return strtoupper($agenciaBanco);
                }
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $gerente->setCreatedAt(new \Datetime());
                $gerenteRepository->save($gerente, true);

                return $this->redirectToRoute('app_gerente_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('gerente/new.html.twig', [
                'gerente' => $gerente,
                'form' => $form,
            ]);

        }catch(UniqueConstraintViolationException $ex){
            dump($ex);
            $this->addFlash('error', 'Unable to perform this operation. Contact your system administrator.');
            return $this->redirectToRoute('app_gerente_index', [], Response::HTTP_SEE_OTHER);
        }
        catch(Exception $ex){
            dump($ex);
            $this->addFlash('error', 'An unexpected error has occurred. Contact your system administrator.');
            return $this->redirectToRoute('app_gerente_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/{id}', name: 'app_gerente_show', methods: ['GET'])]
    public function show(Gerente $gerente): Response
    {
        return $this->render('gerente/show.html.twig', [
            'gerente' => $gerente,
        ]);
    }

    #[IsGranted('ROLE_ADMIN_AGENCIA')]
    #[Route('/{id}/edit', name: 'app_gerente_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gerente $gerente, GerenteRepository $gerenteRepository): Response
    {
        $form = $this->createForm(GerenteType::class, $gerente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gerente->setUpdatedAt(new \Datetime());
            $gerenteRepository->save($gerente, true);

            return $this->redirectToRoute('app_gerente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gerente/edit.html.twig', [
            'gerente' => $gerente,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN_BANCO')]
    #[Route('/{id}', name: 'app_gerente_delete', methods: ['POST'])]
    public function delete(Request $request, Gerente $gerente, GerenteRepository $gerenteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gerente->getId(), $request->request->get('_token'))) {
            $gerenteRepository->remove($gerente, true);
        }

        return $this->redirectToRoute('app_gerente_index', [], Response::HTTP_SEE_OTHER);
    }
}
