<?php

namespace App\Controller;

use App\Form\Type\QualificationAndRace;
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

            return $this->render('result-race.html.twig', [
                'data' => $data,
            ]);
        }

        $formForQualiAndRace = $this->createForm(QualificationAndRace::class);
        $formForQualiAndRace->handleRequest($request);
        if ($formForQualiAndRace->isSubmitted() && $formForQualiAndRace->isValid()) {
            $url = $formForQualiAndRace->getData()['url'];
            $race_id = explode('=', $url)[1];
            $championship_id = explode('/', $url)[4];

            $processQuali = new Process(["/usr/bin/python3", "../Simgrid-Qualification-Times-Gap-in-Percent/simgrid-quali-gap.py", 
            'argument' => 'https://www.thesimgrid.com/championships/' . $championship_id . '/results?overall=true&race_id=' . $race_id . '&session_type=qualifying']);
            $processQuali->run();
            if (!$processQuali->isSuccessful()) {
                throw new ProcessFailedException($processQuali);
                }
            $dataQuali = json_decode($processQuali->getOutput(), true);

            $processRace = new Process(["/usr/bin/python3", "../Simgrid-Race-Consistency/simgrid-race-consistency.py", 
            'argument' => 'https://www.thesimgrid.com/championships/' . $championship_id . '/results?overall=true&race_id=' . $race_id . '&result_type=laps&session_type=race_1']);
            $processRace->run();
            if (!$processRace->isSuccessful()) {
                throw new ProcessFailedException($processRace);
                }
            $dataRace = json_decode($processRace->getOutput(), true);

            // check if a race 2 exists
            $processRace2 = new Process(["/usr/bin/python3", "../Simgrid-Race-Consistency/simgrid-race-consistency.py", 
            'argument' => 'https://www.thesimgrid.com/championships/' . $championship_id . '/results?overall=true&race_id=' . $race_id . '&result_type=laps&session_type=race_2']);
            $processRace2->run();
            if (!$processRace2->isSuccessful()) {
                return $this->render('result.html.twig', [
                    'dataQuali' => $dataQuali,
                    'dataRace' => $dataRace
                ]);
            }
            $dataRace2 = json_decode($processRace2->getOutput(), true);

            return $this->render('result.html.twig', [
                'dataQuali' => $dataQuali,
                'dataRace' => $dataRace,
                'dataRace2' => $dataRace2
            ]);
        }

        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
            'formForQualiGap' => $formForQualiGap,
            'formForRaceConsistency' => $formForRaceConsistency,
            'formForQualiAndRace' => $formForQualiAndRace
        ]);

    }
}