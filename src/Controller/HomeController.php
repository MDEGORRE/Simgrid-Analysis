<?php

namespace App\Controller;

use App\Form\Type\QualificationGap;
use App\Form\Type\RaceConsistency;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request): Response
    {
        $formForQualiGap = $this->createForm(QualificationGap::class);
        $formForQualiGap->handleRequest($request);
        if ($formForQualiGap->isSubmitted() && $formForQualiGap->isValid()) {
            // $formForQualiGap->getData() holds the submitted values
            $url = $formForQualiGap->getData()['url'];

            $process = new Process(["/usr/bin/python3", "../Simgrid-Qualification-Times-Gap-in-Percent/simgrid-quali-gap.py", 'argument' => $url]);
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
                }
            $data = json_decode($process->getOutput(), true);

            return $this->render('result-quali.html.twig', [
                'data' => $data,
            ]);
        }

        $formForRaceConsistency = $this->createForm(RaceConsistency::class);
        $formForRaceConsistency->handleRequest($request);
        if ($formForRaceConsistency->isSubmitted() && $formForRaceConsistency->isValid()) {
            $url = $formForRaceConsistency->getData()['url'];

            $process = new Process(["/usr/bin/python3", "../Simgrid-Race-Consistency/simgrid-race-consistency.py", 'argument' => $url]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
                }
            $data = json_decode($process->getOutput(), true);

            return $this->render('result.html.twig', [
                'data' => $data,
            ]);
        }

        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
            'formForQualiGap' => $formForQualiGap,
            'formForRaceConsistency' => $formForRaceConsistency,
        ]);

    }
}